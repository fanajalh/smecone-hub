<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
            // 🔥 Barang yang is_promoted (Iklan) bakal ditaruh paling atas!
            ->orderBy('is_promoted', 'desc') 
            ->orderBy('is_sold', 'asc') 
            ->latest()
            ->paginate(15)->withQueryString();

        return view('marketplace.index', compact('products', 'search', 'category'));
    }

    public function broadcastKeWa($itemId)
    {
        $item = \App\Models\Marketplace::find($itemId);
            
        // ID Grup yang tadi kamu copy dari terminal Node.js
        $idGrupSmecone = '123456789098765432@g.us'; 
        
        // Susun pesan iklannya
        $pesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n";
        $pesan .= "Barang: *" . $item->title . "*\n";
        $pesan .= "Harga: Rp " . number_format($item->price, 0, ',', '.') . "\n";
        $pesan .= "Cek selengkapnya di web: " . url('/marketplace/' . $item->id);

        // Tembak API Bot Node.js yang sedang menyala
        $response = Http::post('http://localhost:3000/api/broadcast-iklan', [
            'groupId' => $idGrupSmecone,
            'pesan' => $pesan
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Iklan berhasil dikirim ke grup WhatsApp!');
        }

        return back()->with('error', 'Gagal mengirim iklan.');
    }

    /**
     * 🔥 FUNGSI BARU: Proses Pendaftaran Lapak
     */
    public function registerStore(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:50|unique:users,store_name',
            'whatsapp_number' => 'required|string|min:10|max:15',
        ]);

        $user = auth()->user();
        
        // Simpan data lapak ke tabel user
        $user->update([
            'store_name' => $request->store_name,
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        return redirect('/marketplace/create')->with('success', 'Selamat! Lapak berhasil dibuka. Sekarang kamu bisa tambah produk.');
    }

    // 🔥 FITUR BARU: Dashboard Pemantauan Lapak
    public function myLapak()
    {
        $products = Marketplace::where('user_id', auth()->id())->latest()->get();
        
        // Statistik untuk dashboard
        $totalViews = $products->sum('views_count');
        $totalProducts = $products->count();
        $soldProducts = $products->where('is_sold', true)->count();
        $activeProducts = $totalProducts - $soldProducts;

        return view('marketplace.lapak', compact('products', 'totalViews', 'totalProducts', 'soldProducts', 'activeProducts'));
    }

    public function create() 
    { 
        $user = auth()->user();

        // Kalau belum punya nama lapak, arahkan ke halaman Daftar Lapak
        if (empty($user->store_name)) {
            return view('marketplace.register-store');
        }

        // Kalau sudah punya, lanjut ke form tambah barang
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
        
        // Nambah view count kalau bukan pemilik yang liat
        if ($product->user_id !== auth()->id()) {
            $product->increment('views_count');
        }

        // 🔥 AMBIL DATA REKOMENDASI LAINNYA (Maksimal 6 barang dari kategori yang sama)
        $recommendations = Marketplace::where('category', $product->category)
                            ->where('id', '!=', $id)
                            ->where('is_sold', false) // Usahakan yang direkomendasikan belum habis
                            ->latest()
                            ->take(6)
                            ->get();

        return view('marketplace.show', compact('product', 'recommendations'));
    }

    // 🔥 FITUR BARU: Halaman Kunjungi Toko
    public function toko($id)
    {
        // Cari data penjual berdasarkan ID User
        $seller = \App\Models\User::findOrFail($id);
        
        // Ambil semua produk milik penjual ini
        $products = Marketplace::where('user_id', $id)
                        ->orderBy('is_sold', 'asc') // Yang belum laku di atas
                        ->latest()
                        ->paginate(15);
        
        // Hitung statistik toko
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
}