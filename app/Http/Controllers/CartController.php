<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ── GET /keranjang ─────────────────────────────────────────────────────────
    // Halaman HTML dedicated keranjang
    public function page()
    {
        $cartRows = Cart::with('item.user')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartRows->sum(fn($c) => ($c->item->price ?? 0) * $c->qty);

        return view('cart.index', compact('cartRows', 'total'));
    }

    // ── GET /cart ──────────────────────────────────────────────────────────────
    // Mengembalikan data cart sebagai JSON (dipanggil via fetch dari halaman)
    public function index()
    {
        $cartItems = Cart::with('item.user')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($c) {
                return [
                    'id'         => $c->id,
                    'product_id' => $c->marketplace_id,
                    'name'       => ($c->item->item_name ?? 'Produk Dihapus') . ($c->variant_selected ? ' ('.$c->variant_selected.')' : ''),
                    'price'      => (int) ($c->item->price ?? 0),
                    'image'      => $c->item && $c->item->image
                                        ? asset('storage/' . (is_array(json_decode($c->item->image, true)) ? json_decode($c->item->image, true)[0] : $c->item->image))
                                        : null,
                    'qty'        => $c->qty,
                    'is_sold'    => (bool) ($c->item->is_sold ?? false),
                    'seller'     => $c->item->user->name ?? '-',
                    'variant'    => $c->variant_selected,
                ];
            });

        return response()->json([
            'items' => $cartItems,
            'count' => $cartItems->sum('qty'),
            'total' => $cartItems->sum(fn($i) => $i['price'] * $i['qty']),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:marketplaces,id',
            'qty' => 'nullable|integer|min:1',
            'variant' => 'nullable|string'
        ]);

        $product = Marketplace::findOrFail($request->product_id);
        
        // Ambil stok produk, kalau kolom stok belum diisi default ke 999
        $stock = $product->stock ?? 999;

        if ($product->is_sold || $stock < 1) {
            return response()->json(['message' => 'Produk sudah habis terjual / stok kosong.'], 422);
        }

        $cart = Cart::firstOrCreate(
            [
                'user_id' => Auth::id(), 
                'marketplace_id' => $product->id,
                'variant_selected' => $request->variant
            ],
            ['qty' => 0]
        );

        $qtyToAdd = $request->qty ?? 1;

        if ($cart->qty + $qtyToAdd > $stock) {
            return response()->json(['message' => 'Gagal, pembelian melebihi sisa stok ('.$stock.').'], 422);
        }

        $cart->qty += $qtyToAdd;
        $cart->save();

        return response()->json([
            'message' => $product->item_name . ($request->variant ? ' ('.$request->variant.')' : '') . ' ditambahkan ke keranjang!',
            'count'   => Cart::where('user_id', Auth::id())->sum('qty'),
        ]);
    }

    // ── PATCH /cart/{id}/qty ───────────────────────────────────────────────────
    public function updateQty(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) abort(403);
        $request->validate(['qty' => 'required|integer|min:1']);

        // Validasi Stok
        $stock = $cart->item->stock ?? 0;
        if ($request->qty > $stock) {
            return response()->json(['message' => 'Melebihi stok yang tersedia (' . $stock . ')'], 422);
        }

        $cart->update(['qty' => $request->qty]);
        return response()->json(['message' => 'Qty diperbarui.']);
    }

    // ── DELETE /cart/clear ─────────────────────────────────────────────────────
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Keranjang dikosongkan.']);
    }

    // ── GET /cart/count ────────────────────────────────────────────────────────
    // Endpoint ringan untuk polling badge
    public function count()
    {
        return response()->json([
            'count' => Cart::where('user_id', Auth::id())->sum('qty'),
        ]);
    }
}
