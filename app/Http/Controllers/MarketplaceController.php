<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $products = Marketplace::with('user')
            ->when($search, function($query, $search) {
                return $query->where('item_name', 'like', "%{$search}%");
            })
            ->when($category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->orderBy('is_promoted', 'desc') 
            ->orderBy('is_sold', 'asc') 
            ->latest()
            ->paginate(15)->withQueryString();

        return view('marketplace.index', compact('products', 'search', 'category'));
    }

    public function broadcastKeWa(Request $request, $itemId)
    {
        // 1. Validasi input dari modal
        $request->validate([
            'pesan' => 'required|string',
            'custom_image' => 'nullable|image|max:2048' // Maksimal 2MB
        ]);

        $item = Marketplace::findOrFail($itemId);
        $idGrupSmecone = '120363425273294200@g.us'; 
        
        // 2. Gunakan pesan yang diketik/diedit user dari Modal
        $pesan = $request->pesan;

        // 3. Cek apakah user mengunggah gambar custom
        $imageUrl = null;
        if ($request->hasFile('custom_image')) {
            // Simpan gambar custom ke folder storage/app/public/broadcasts
            $path = $request->file('custom_image')->store('broadcasts', 'public');
            $imageUrl = asset('storage/' . $path);
        } else {
            // Jika tidak upload gambar custom, pakai gambar asli produk (jika ada)
            $imageUrl = $item->image ? asset('storage/' . $item->image) : null;
        }

        try {
            // Tembak API Bot
            $response = Http::post('http://localhost:3000/api/broadcast-iklan', [
                'groupId' => $idGrupSmecone,
                'pesan' => $pesan,
                'imageUrl' => $imageUrl
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Iklan custom berhasil dikirim ke grup WhatsApp!');
            }

            return back()->with('error', 'Gagal mengirim iklan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak bisa terhubung ke API Bot. Pastikan server Node.js menyala!');
        }
    }

    public function registerStore(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:50|unique:users,store_name',
            'whatsapp_number' => 'required|string|min:10|max:15',
        ]);

        $user = auth()->user();
        
        $user->update([
            'store_name' => $request->store_name,
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        return redirect('/marketplace/create')->with('success', 'Selamat! Lapak berhasil dibuka. Sekarang kamu bisa tambah produk.');
    }

    public function myLapak()
    {
        $products = Marketplace::where('user_id', auth()->id())->latest()->get();
        
        $totalViews = $products->sum('views_count');
        $totalProducts = $products->count();
        $soldProducts = $products->where('is_sold', true)->count();
        $activeProducts = $totalProducts - $soldProducts;

        return view('marketplace.lapak', compact('products', 'totalViews', 'totalProducts', 'soldProducts', 'activeProducts'));
    }

    public function create() 
    { 
        $user = auth()->user();

        if (empty($user->store_name)) {
            return view('marketplace.register-store');
        }

        return view('marketplace.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'category' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('marketplaces', 'public');
        }

        Marketplace::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type ?? 'Ready Stock',
            'location' => $request->location,
            'image' => $path,
            'is_sold' => false,
            'views_count' => 0,
            'is_promoted' => false,
        ]);

        return redirect('/marketplace')->with('success', 'Produk berhasil dipasang!');
    }

    public function show($id)
    {
        $product = Marketplace::with('user')->findOrFail($id);
        
        if ($product->user_id !== auth()->id()) {
            $product->increment('views_count');
        }

        $recommendations = Marketplace::where('category', $product->category)
                            ->where('id', '!=', $id)
                            ->where('is_sold', false)
                            ->latest()
                            ->take(6)
                            ->get();

        return view('marketplace.show', compact('product', 'recommendations'));
    }

    public function toko($id)
    {
        $seller = \App\Models\User::findOrFail($id);
        
        $products = Marketplace::where('user_id', $id)
                        ->orderBy('is_sold', 'asc')
                        ->latest()
                        ->paginate(15);
        
        $totalProducts = Marketplace::where('user_id', $id)->count();
        $soldProducts = Marketplace::where('user_id', $id)->where('is_sold', true)->count();
        
        return view('marketplace.toko', compact('seller', 'products', 'totalProducts', 'soldProducts'));
    }

    public function destroy($id)
    {
        $product = Marketplace::findOrFail($id);
        if ($product->user_id !== auth()->id() && !auth()->user()->is_admin) abort(403);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return back()->with('success', 'Barang berhasil ditarik dari etalase.');
    }

    public function toggleSold($id)
    {
        $product = Marketplace::findOrFail($id);
        if ($product->user_id !== auth()->id() && !auth()->user()->is_admin) abort(403);

        $product->update(['is_sold' => !$product->is_sold]);
        return back()->with('success', 'Status barang diperbarui!');
    }

    public function salesHistory()
{
    // Ambil transaksi yang barangnya milik si penjual yang sedang login
    $sales = Transaction::with(['user', 'marketplaceItem'])
        ->whereHas('marketplaceItem', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->latest()
        ->get();

    return view('marketplace.sales', compact('sales'));
}
}