<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Transaction;
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

        return redirect('/marketplace/lapak-saya')->with('success', 'Selamat! Lapak berhasil dibuka.');
    }

    public function myLapak()
    {
        $user = auth()->user();

        if (empty($user->store_name)) {
            return view('marketplace.register-store');
        }

        $products = Marketplace::where('user_id', auth()->id())->latest()->get();
        
        $totalViews = $products->sum('views_count');
        $totalProducts = $products->count();
        $soldProducts = $products->where('is_sold', true)->count();
        $activeProducts = $totalProducts - $soldProducts;

        // Hitung Total Pendapatan dari Transaksi yang berstatus PAID (Lunas via Xendit)
        $totalRevenue = \App\Models\Transaction::whereHas('marketplaceItem', function($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'PAID')->sum('amount');

        // Pastikan variabel $totalRevenue ditambahkan ke compact()
        return view('marketplace.lapak', compact('products', 'totalViews', 'totalProducts', 'soldProducts', 'activeProducts', 'totalRevenue'));
    }

    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        $user = auth()->user();

        if ($user->store_balance < $request->amount) {
            return back()->with('error', 'Saldo tidak mencukupi untuk penarikan.');
        }

        $user->decrement('store_balance', $request->amount);

        \App\Models\Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'PENDING',
        ]);

        return back()->with('success', 'Permintaan penarikan dana diproses! Mohon tunggu admin mencairkannya.');
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
            'stock' => 'required|integer|min:1',
            'description' => 'required',
            'category' => 'required',
            'format' => 'required|in:Fisik,Digital',
            'digital_link' => 'nullable|url',
            'variants_config' => 'nullable|string',
            'image' => 'nullable|array|max:5',
            'image.*' => 'image|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('marketplaces', 'public');
            }
        }

        // Parse variants string to JSON array (e.g., "Keju, Cokelat" -> ["Keju", "Cokelat"])
        $variants = null;
        if ($request->variants_config) {
            $variantsInput = array_map('trim', explode(',', $request->variants_config));
            $variants = json_encode(array_filter($variantsInput));
        }

        Marketplace::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type ?? 'Ready Stock',
            'location' => $request->location,
            'format' => $request->format,
            'digital_link' => $request->format === 'Digital' ? $request->digital_link : null,
            'variants_config' => $request->format === 'Fisik' ? $variants : null,
            'image' => !empty($imagePaths) ? json_encode($imagePaths) : null,
            'is_sold' => false,
            'views_count' => 0,
            'is_promoted' => false,
        ]);

        return redirect('/marketplace')->with('success', 'Produk berhasil dipasang!');
    }

    public function edit($id)
    {
        $product = Marketplace::findOrFail($id);

        // Hanya pemilik yang boleh edit
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengedit produk ini.');
        }

        return view('marketplace.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Marketplace::findOrFail($id);

        if ($product->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengedit produk ini.');
        }

        $request->validate([
            'item_name'   => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required',
            'category'    => 'required',
            'format'      => 'required|in:Fisik,Digital',
            'digital_link'=> 'nullable|url',
            'variants_config' => 'nullable|string',
            'image'       => 'nullable|array|max:5',
            'image.*'     => 'image|max:2048',
        ]);

        $currentImages = [];
        if ($product->image) {
            $decoded = json_decode($product->image, true);
            $currentImages = is_array($decoded) ? $decoded : [$product->image];
        }

        if ($request->has('deleted_images')) {
            $deletedImages = $request->input('deleted_images');
            foreach ($deletedImages as $delImg) {
                if (($key = array_search($delImg, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                    if (Storage::disk('public')->exists($delImg)) {
                        Storage::disk('public')->delete($delImg);
                    }
                }
            }
            $currentImages = array_values($currentImages);
        }

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                if (count($currentImages) < 5) {
                    $currentImages[] = $file->store('marketplaces', 'public');
                }
            }
        }
        
        $product->image = !empty($currentImages) ? json_encode($currentImages) : null;

        $variants = null;
        if ($request->variants_config) {
            $variantsInput = array_map('trim', explode(',', $request->variants_config));
            $variants = json_encode(array_filter($variantsInput));
        }

        $product->update([
            'item_name'   => $request->item_name,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'description' => $request->description,
            'category'    => $request->category,
            'type'        => $request->type ?? $product->type,
            'location'    => $request->location,
            'format'      => $request->format,
            'digital_link'=> $request->format === 'Digital' ? $request->digital_link : null,
            'variants_config' => $request->format === 'Fisik' ? $variants : null,
            'image'       => $product->image,
        ]);

        return redirect('/marketplace/lapak-saya')->with('success', 'Produk berhasil diperbarui!');
    }

    public function broadcastPage($id)
    {
        $product = Marketplace::findOrFail($id);
        
        if ($product->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Anda tidak berhak menyiarkan produk ini.');
        }

        return view('marketplace.broadcast', compact('product'));
    }

    public function show($id)
    {
        $product = Marketplace::with(['user', 'reviews.user'])->findOrFail($id);
        
        if ($product->user_id !== auth()->id()) {
            $product->increment('views_count');
        }

        $recommendations = Marketplace::where('category', $product->category)
                            ->where('id', '!=', $id)
                            ->where('is_sold', false)
                            ->latest()
                            ->take(6)
                            ->get();

        $canReview = false;
        $unreviewedTransaction = null;
        
        if (auth()->check()) {
            // Cek apakah user sudah beli (transaksi status bebas atau completed/paid tergantung sistem)
            // Di sini kita asumsikan semua status transaksi di Smecone adalah valid (atau bisa filter status tertentu)
            $unreviewedTransaction = \App\Models\Transaction::where('user_id', auth()->id())
                                        ->where('marketplace_item_id', $product->id)
                                        // ->where('status', 'PAID') // Jika ada status pembayaran
                                        ->whereDoesntHave('review')
                                        ->first();
            
            if ($unreviewedTransaction) {
                $canReview = true;
            }
        }

        // Kalkulasi rata-rata rating
        $averageRating = $product->reviews->avg('rating') ?? 0;
        $totalReviews = $product->reviews->count();

        return view('marketplace.show', compact('product', 'recommendations', 'canReview', 'unreviewedTransaction', 'averageRating', 'totalReviews'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Marketplace::findOrFail($id);

        $unreviewedTransaction = \App\Models\Transaction::where('user_id', auth()->id())
                                    ->where('marketplace_item_id', $product->id)
                                    ->whereDoesntHave('review')
                                    ->first();

        if (!$unreviewedTransaction) {
            return back()->with('error', 'Anda tidak berhak memberikan ulasan atau sudah memberikan ulasan.');
        }

        \App\Models\MarketplaceReview::create([
            'user_id' => auth()->id(),
            'marketplace_item_id' => $product->id,
            'transaction_id' => $unreviewedTransaction->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
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

    public function updateStoreWa(Request $request)
    {
        $request->validate(['whatsapp_number' => 'required']);
        
        $waNumber = preg_replace('/[^0-9]/', '', $request->whatsapp_number);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        } elseif (!str_starts_with($waNumber, '62')) {
            $waNumber = '62' . $waNumber;
        }

        auth()->user()->update(['whatsapp_number' => $waNumber]);
        return back()->with('success', 'Nomor WA Toko berhasil diperbarui!');
    }

    public function updateStoreProfile(Request $request)
    {
        $request->validate([
            'store_photo' => 'nullable|image|max:2048',
            'store_banner' => 'nullable|image|max:4096'
        ]);

        $user = auth()->user();

        if ($request->hasFile('store_photo')) {
            if ($user->store_photo && Storage::disk('public')->exists($user->store_photo)) {
                Storage::disk('public')->delete($user->store_photo);
            }
            $user->store_photo = $request->file('store_photo')->store('store_profiles', 'public');
        }

        if ($request->hasFile('store_banner')) {
            if ($user->store_banner && Storage::disk('public')->exists($user->store_banner)) {
                Storage::disk('public')->delete($user->store_banner);
            }
            $user->store_banner = $request->file('store_banner')->store('store_banners', 'public');
        }

        $user->save();

        return back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}