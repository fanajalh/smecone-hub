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
                    'name'       => $c->item->item_name ?? 'Produk Dihapus',
                    'price'      => (int) ($c->item->price ?? 0),
                    'image'      => $c->item && $c->item->image
                                        ? asset('storage/' . $c->item->image)
                                        : null,
                    'qty'        => $c->qty,
                    'is_sold'    => (bool) ($c->item->is_sold ?? false),
                    'seller'     => $c->item->user->name ?? '-',
                ];
            });

        return response()->json([
            'items' => $cartItems,
            'count' => $cartItems->sum('qty'),
            'total' => $cartItems->sum(fn($i) => $i['price'] * $i['qty']),
        ]);
    }

    // ── POST /cart/add ─────────────────────────────────────────────────────────
    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:marketplaces,id']);

        $product = Marketplace::findOrFail($request->product_id);

        if ($product->is_sold) {
            return response()->json(['message' => 'Produk sudah habis terjual.'], 422);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'marketplace_id' => $product->id],
            ['qty' => 0]
        );

        $cart->increment('qty');

        return response()->json([
            'message' => $product->item_name . ' ditambahkan ke keranjang!',
            'count'   => Cart::where('user_id', Auth::id())->sum('qty'),
        ]);
    }

    // ── PATCH /cart/{id}/qty ───────────────────────────────────────────────────
    public function updateQty(Request $request, Cart $cart)
    {
        // Pastikan cart milik user yang login
        if ($cart->user_id !== Auth::id()) abort(403);

        $request->validate(['qty' => 'required|integer|min:1|max:99']);
        $cart->update(['qty' => $request->qty]);

        return response()->json(['message' => 'Qty diperbarui.']);
    }

    // ── DELETE /cart/{id} ──────────────────────────────────────────────────────
    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) abort(403);
        $cart->delete();

        return response()->json([
            'message' => 'Item dihapus dari keranjang.',
            'count'   => Cart::where('user_id', Auth::id())->sum('qty'),
        ]);
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
