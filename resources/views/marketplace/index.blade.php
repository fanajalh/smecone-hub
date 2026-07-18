@extends('layouts.app')
@section('title', '| Marketplace')

@section('content')
<style>
    /* Menyembunyikan scrollbar horizontal tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Efek tekan pada tombol/card */
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1); }
    
    /* Mencegah Alpine.js berkedip sebelum diload */
    [x-cloak] { display: none !important; }

    /* Custom shadow untuk efek glow */
    .glow-effect { box-shadow: 0 0 40px -10px rgba(226,31,38,0.5); }
</style>

{{-- Script Ionicons CDN (Web Components for Icons) --}}
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

{{-- CSRF Token untuk fetch requests API --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div x-data="cartApp()" x-init="fetchCart()" class="bg-[#F8F9FA] min-h-screen pt-4 lg:pt-36 pb-24 md:pb-12 relative overflow-hidden">
    
    {{-- Latar Belakang Dekoratif --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-red-50/50 to-transparent pointer-events-none"></div>

    {{-- Drawer Keranjang Dihapus, diganti ke halaman full /keranjang --}}

    {{-- ======================== MAIN CONTENT ======================== --}}
    <div class="max-w-7xl mx-auto relative z-10">

        {{-- 1. STICKY HEADER --}}
        <div class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-200/50 md:border md:rounded-[2rem] md:top-4 md:mt-4 md:mx-4 px-4 py-3 shadow-[0_4px_20px_rgba(0,0,0,0.02)] md:shadow-md flex items-center gap-3 transition-all ring-1 ring-black/5">

            {{-- Search Bar --}}
            <form action="/marketplace" method="GET" class="flex-grow relative flex items-center">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                    <ion-icon name="search" class="text-xl"></ion-icon>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jajan atau barang..."
                       class="w-full py-3 pl-12 pr-4 bg-gray-100/50 border border-gray-200/50 rounded-2xl text-[14px] font-bold text-gray-900 placeholder-gray-400 focus:bg-white focus:border-[#E21F26] focus:ring-4 focus:ring-red-50 transition-all outline-none">
            </form>

            {{-- MOBILE: Cart & Menu --}}
            <div class="flex items-center gap-1.5 md:hidden">
                <a href="/keranjang" class="relative flex items-center justify-center w-[52px] h-[52px] rounded-[1.2rem] bg-gray-50 hover:bg-red-50 transition-colors group tap-effect border border-gray-100 shrink-0">
                    <ion-icon name="cart-outline" class="text-[26px] text-gray-700 group-hover:text-[#E21F26] transition-colors"></ion-icon>
                    <span x-show="cartCount > 0" class="absolute top-0 right-0 bg-[#E21F26] text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm" x-text="cartCount > 99 ? '99+' : cartCount"></span>
                </a>
                
                {{-- Burger Menu (Dropdown) --}}
                <div x-data="{ menuOpen: false }" class="relative">
                    <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" class="relative flex items-center justify-center w-[52px] h-[52px] rounded-[1.2rem] bg-gray-50 hover:bg-red-50 transition-colors group tap-effect border border-gray-100 shrink-0">
                        <ion-icon name="menu" class="text-[28px] text-gray-700 group-hover:text-[#E21F26] transition-colors"></ion-icon>
                    </button>
                    <div x-show="menuOpen" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="absolute right-0 top-[110%] mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden" x-cloak>
                        
                        <div class="px-4 py-3 border-b border-gray-50 mb-1">
                            <span class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest">Menu Lapak</span>
                        </div>
                        <a href="{{ route('marketplace.purchases') }}" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold text-gray-700 hover:text-[#E21F26] hover:bg-red-50 transition-colors">
                            <ion-icon name="time-outline" class="text-xl"></ion-icon> Riwayat Belanja
                        </a>
                        @if(!empty(auth()->user()->store_name))
                        <a href="{{ route('marketplace.sales') }}" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold text-gray-700 hover:text-[#E21F26] hover:bg-red-50 transition-colors">
                            <ion-icon name="receipt-outline" class="text-xl"></ion-icon> Riwayat Jualan
                        </a>
                        @endif
                        <a href="/marketplace/lapak-saya" class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold text-gray-700 hover:text-[#E21F26] hover:bg-red-50 transition-colors">
                            <ion-icon name="storefront-outline" class="text-xl"></ion-icon> Kelola Lapak
                        </a>
                    </div>
                </div>
            </div>

            {{-- DESKTOP: Cart button and Menu --}}
            <div class="hidden md:flex items-center gap-2 shrink-0">
                <a href="{{ route('marketplace.purchases') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#E21F26] bg-transparent hover:bg-red-50 px-4 py-3 rounded-2xl transition-all tap-effect font-bold text-[14px]">
                    <ion-icon name="time-outline" class="text-xl"></ion-icon>
                    Riwayat Belanja
                </a>
                @if(!empty(auth()->user()->store_name))
                <a href="{{ route('marketplace.sales') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#E21F26] bg-transparent hover:bg-red-50 px-4 py-3 rounded-2xl transition-all tap-effect font-bold text-[14px]">
                    <ion-icon name="receipt-outline" class="text-xl"></ion-icon>
                    Riwayat Jualan
                </a>
                @endif
                <a href="/keranjang" class="flex items-center gap-2 bg-gradient-to-br from-gray-50 to-gray-100 hover:from-red-50 hover:to-red-100 border border-gray-200 hover:border-red-200 text-gray-800 hover:text-[#E21F26] px-6 py-3 rounded-2xl font-black text-[14px] transition-all relative shrink-0 tap-effect shadow-sm hover:shadow-md group">
                    <ion-icon name="cart" class="text-xl group-hover:scale-110 transition-transform"></ion-icon>
                    Keranjang
                    <span x-show="cartCount > 0" class="bg-[#E21F26] text-white text-[11px] font-black px-2.5 py-0.5 rounded-full ml-1 shadow-sm" x-text="cartCount"></span>
                </a>
            </div>
        </div>

        {{-- 2. SELLER ACTIONS --}}
        <div class="px-4 py-6 md:py-8 relative overflow-hidden mt-2 md:mt-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="md:flex-1">
                    <h1 class="text-2xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        Smecone Marketplace <span class="hidden md:inline text-3xl">🛍️</span>
                    </h1>
                    <p class="text-[14px] md:text-[16px] text-gray-500 font-bold mt-2 max-w-md">Pusat jajan, pre-loved, dan karya kreatif warga Smecone. Dijamin seru!</p>
                </div>
                <div class="flex gap-3 md:gap-4 md:shrink-0">
                    <a href="/marketplace/create" class="flex-1 md:flex-none bg-gradient-to-r from-[#E21F26] to-[#ff4b51] text-white py-3.5 md:py-4 px-5 md:px-8 rounded-2xl shadow-[0_8px_20px_rgba(226,31,38,0.3)] hover:shadow-[0_12px_25px_rgba(226,31,38,0.4)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 tap-effect font-black text-[14px] md:text-[15px] border border-red-500">
                        <ion-icon name="add-circle" class="text-xl"></ion-icon>
                        Jual Barang
                    </a>
                    <a href="/marketplace/lapak-saya" class="flex-1 md:flex-none bg-white text-gray-900 border-2 border-gray-200 py-3.5 md:py-4 px-5 md:px-8 rounded-2xl hover:border-gray-900 hover:shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2 tap-effect font-black text-[14px] md:text-[15px]">
                        <ion-icon name="storefront" class="text-xl text-gray-700"></ion-icon>
                        Kelola Lapak
                    </a>
                </div>
            </div>
        </div>

        {{-- 3. HOT PROMO BANNER & KATEGORI --}}
        <div class="px-4 py-2 md:py-4 md:flex md:gap-6">
            {{-- Banner --}}
            <div class="md:w-7/12 lg:w-2/3 bg-gradient-to-br from-red-600 via-[#E21F26] to-orange-500 rounded-[2rem] md:rounded-[3rem] p-7 md:p-12 shadow-[0_15px_40px_rgba(226,31,38,0.3)] relative overflow-hidden mb-6 md:mb-0 group border border-red-400 min-h-[220px] md:min-h-0 flex flex-col justify-center">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] group-hover:scale-110 transition-transform duration-1000 pointer-events-none"></div>
                
                {{-- Glow --}}
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-10 w-72 h-72 bg-orange-400/40 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 w-[70%] md:w-3/4">
                    <span class="bg-white/20 backdrop-blur-md text-white border border-white/40 text-[10px] md:text-[12px] font-black px-4 py-2 rounded-lg uppercase tracking-widest inline-flex items-center gap-1.5 mb-4 shadow-sm">
                        <ion-icon name="flame"></ion-icon> Hot Promo
                    </span>
                    <h2 class="text-white font-black text-3xl md:text-5xl lg:text-6xl leading-tight md:mb-3 drop-shadow-md tracking-tight">Jajan Hemat<br>Bebas Lapar!</h2>
                    <p class="hidden md:block text-red-50 text-[15px] lg:text-[17px] font-bold max-w-md mt-4 drop-shadow-sm">Temukan jajanan favorit dan barang incaran dari teman-temanmu dengan harga spesial hari ini.</p>
                </div>
                <div class="absolute -right-4 -bottom-6 md:right-8 md:bottom-auto md:top-1/2 md:-translate-y-1/2 z-10 text-[130px] md:text-[150px] lg:text-[180px] drop-shadow-2xl transform -rotate-12 group-hover:rotate-6 group-hover:scale-110 transition-transform duration-500 leading-none">🍔</div>
            </div>

            {{-- Categories --}}
            <div class="md:w-5/12 lg:w-1/3 md:bg-white md:p-8 md:rounded-[3rem] md:shadow-lg md:border md:border-gray-100 flex flex-col justify-center">
                <h3 class="hidden md:block text-gray-900 font-black text-2xl mb-6 tracking-tight">Kategori Pilihan</h3>
                <div class="flex overflow-x-auto hide-scrollbar gap-4 md:gap-5 pb-4 md:pb-0 snap-x md:flex-wrap">
                    @php
                        $categories = [
                            'Semua' => ['icon' => 'grid', 'active' => !request('category')],
                            'Makanan' => ['icon' => 'fast-food', 'active' => request('category') === 'Makanan'],
                            'Minuman' => ['icon' => 'cafe', 'active' => request('category') === 'Minuman'],
                            'Jasa' => ['icon' => 'construct', 'active' => request('category') === 'Jasa'],
                            'Elektronik' => ['icon' => 'laptop', 'active' => request('category') === 'Elektronik'],
                        ];
                    @endphp
                    @foreach($categories as $catName => $catData)
                        @php
                            $href = $catName === 'Semua' ? '/marketplace' : '/marketplace?category=' . $catName . '&search=' . request('search');
                            $isActive = $catData['active'];
                        @endphp
                        <a href="{{ $href }}" class="flex flex-col items-center gap-2.5 min-w-[80px] md:min-w-[90px] md:flex-1 snap-start tap-effect group">
                            <div class="w-[4rem] h-[4rem] md:w-[4.5rem] md:h-[4.5rem] rounded-[1.5rem] md:rounded-[1.8rem] flex items-center justify-center transition-all duration-300 shadow-sm
                                {{ $isActive 
                                    ? 'bg-gradient-to-br from-[#E21F26] to-red-600 text-white shadow-[0_8px_20px_rgba(226,31,38,0.3)] ring-4 ring-red-100 scale-105' 
                                    : 'bg-white border border-gray-200 text-gray-500 group-hover:border-red-300 group-hover:text-[#E21F26] group-hover:shadow-md' }}">
                                <ion-icon name="{{ $catData['icon'] }}{{ $isActive ? '' : '-outline' }}" class="text-[28px] md:text-[32px]"></ion-icon>
                            </div>
                            <span class="text-[12px] md:text-[13px] {{ $isActive ? 'text-[#E21F26] font-black' : 'text-gray-600 font-bold group-hover:text-[#E21F26]' }} text-center truncate w-full px-1 transition-colors">{{ $catName }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 4. PRODUCT GRID --}}
        <div class="px-4 mt-8 md:mt-12">
            <div class="flex justify-between items-end mb-6 md:mb-8 border-b border-gray-200/60 pb-4">
                <div>
                    <h2 class="text-2xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-2">Eksplor Jajan <ion-icon name="sparkles" class="text-yellow-400"></ion-icon></h2>
                    <p class="text-[14px] md:text-[16px] text-gray-500 font-bold mt-1">Beragam pilihan untukmu hari ini</p>
                </div>
                <div class="flex items-center gap-2 bg-white border border-gray-200 shadow-sm px-4 py-2 rounded-xl">
                    <ion-icon name="layers" class="text-gray-400"></ion-icon>
                    <span class="text-[13px] font-black text-gray-700">{{ $products->total() }} Item</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgba(226,31,38,0.1)] hover:border-red-200 hover:-translate-y-1 flex flex-col transition-all duration-300 overflow-hidden relative group">

                    @if($product->is_promoted)
                    <div class="absolute top-3 left-3 md:top-4 md:left-4 z-10 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-lg border border-yellow-300 flex items-center gap-1">
                        <ion-icon name="star"></ion-icon> PROMO
                    </div>
                    @endif

                    {{-- Image --}}
                    <div class="aspect-square w-full bg-gray-50 relative overflow-hidden">
                        <a href="/marketplace/{{ $product->id }}" class="block w-full h-full">
                            @if($product->image)
                                @php 
                                    $decoded = json_decode($product->image, true);
                                    $firstImage = is_array($decoded) ? $decoded[0] : $product->image;
                                @endphp
                                <img src="{{ asset('storage/' . $firstImage) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" alt="{{ $product->item_name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 group-hover:bg-gray-200 transition-colors">
                                    <ion-icon name="image-outline" class="text-5xl opacity-40"></ion-icon>
                                </div>
                            @endif
                        </a>

                        @if($product->is_sold)
                        <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center z-10">
                            <div class="bg-gray-900 text-white rounded-xl px-4 py-2 text-[12px] font-black uppercase tracking-widest shadow-xl -rotate-12 border-2 border-gray-700">Habis Terjual</div>
                        </div>
                        @else
                        {{-- Quick Add (hover) --}}
                        <button
                            @click="addToCart({{ $product->id }}, '{{ addslashes($product->item_name) }}')"
                            class="absolute bottom-3 left-3 right-3 bg-white/95 backdrop-blur-md border border-white text-gray-900 text-[13px] font-black py-3 rounded-[1rem] opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:bg-[#E21F26] hover:text-white hover:border-[#E21F26] z-10 tap-effect hidden md:flex items-center justify-center gap-2">
                            <ion-icon name="cart"></ion-icon> + Keranjang
                        </button>
                        @endif
                    </div>

                    {{-- Detail --}}
                    <div class="p-4 md:p-5 flex flex-col flex-grow bg-white relative z-20">
                        <a href="/marketplace/{{ $product->id }}">
                            <h3 class="text-[14px] md:text-[16px] text-gray-900 font-extrabold leading-snug line-clamp-2 h-[42px] md:h-[48px] group-hover:text-[#E21F26] transition-colors">{{ $product->item_name }}</h3>
                        </a>
                        <div class="mt-auto pt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-[#E21F26] font-black text-[18px] md:text-[20px] tracking-tight">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                @if(!$product->is_sold)
                                {{-- Mobile Quick Add Button --}}
                                <button
                                    @click="addToCart({{ $product->id }}, '{{ addslashes($product->item_name) }}')"
                                    class="w-10 h-10 rounded-[1rem] bg-red-50 flex items-center justify-center text-[#E21F26] hover:bg-[#E21F26] hover:text-white transition-colors tap-effect shrink-0 md:hidden shadow-sm">
                                    <ion-icon name="add" class="text-xl"></ion-icon>
                                </button>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-2 truncate max-w-[65%]">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-[10px] font-black text-gray-600 shrink-0">{{ substr($product->user->name, 0, 1) }}</div>
                                    <span class="text-[12px] text-gray-500 font-bold truncate">{{ explode(' ', $product->user->name)[0] }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-[11px] font-black text-yellow-600 bg-yellow-50 px-2 py-1 rounded-lg border border-yellow-200/50">
                                    <ion-icon name="star"></ion-icon>
                                    <span>{{ $product->reviews_count > 0 ? $product->average_rating : 'Baru' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 flex flex-col items-center justify-center text-center bg-white rounded-[3rem] border border-dashed border-gray-300 shadow-sm relative overflow-hidden">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-6 ring-8 ring-white shadow-sm">
                        <ion-icon name="storefront-outline" class="text-5xl"></ion-icon>
                    </div>
                    <h3 class="text-gray-900 font-black mb-2 text-2xl tracking-tight">Yah, belum ada produk</h3>
                    <p class="text-[15px] text-gray-500 font-medium mb-8 max-w-[300px]">Jadilah yang pertama berjualan dan pamerkan produk buatanmu kepada warga Smecone!</p>
                    <a href="/marketplace/create" class="bg-[#E21F26] text-white text-[15px] font-black px-10 py-4 rounded-2xl shadow-[0_8px_20px_rgba(226,31,38,0.3)] hover:bg-red-700 transition-all tap-effect hover:-translate-y-1 flex items-center gap-2">
                        <ion-icon name="add-circle" class="text-xl"></ion-icon> Buka Lapak Sekarang
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- 5. PAGINATION --}}
        @if($products->hasPages())
        <div class="px-4 mt-12 mb-12 flex justify-center">
            <div class="bg-white p-3 rounded-[1.5rem] shadow-sm border border-gray-100">
                {{ $products->links() }}
            </div>
        </div>
        @endif

    </div>

    {{-- Toast Notification --}}
    <div x-show="toast.show" 
         x-cloak 
         class="fixed bottom-24 md:bottom-10 left-1/2 -translate-x-1/2 z-[200] bg-gray-900/95 backdrop-blur-md text-white px-6 py-4 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] text-[14px] md:text-[15px] font-bold flex items-center gap-3 whitespace-nowrap border border-gray-700/50" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-8 scale-90" 
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 scale-90">
        <ion-icon name="checkmark-circle" class="text-2xl text-green-400 shrink-0"></ion-icon>
        <span x-text="toast.message" class="tracking-wide"></span>
    </div>

</div>

<script>
/**
 * Aplikasi Alpine.js untuk fitur Keranjang (Cart)
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('cartApp', () => ({
        cartCount: 0,
        cartCount: 0,
        cartTotal: 0,
        cartLoading: false,
        toast: { show: false, message: '' },

        // Mengambil Token CSRF dari Meta Tag
        getCsrf() {
            return document.querySelector('meta[name="csrf-token"]').content;
        },

        // Mengambil data keranjang dari server saat pertama load
        async fetchCart() {
            this.cartLoading = true;
            try {
                const res = await fetch('/cart', { 
                    headers: { 'Accept': 'application/json' } 
                });
                this.cartCount = data.count;
            } finally {
                this.cartLoading = false;
            }
        },

        // Menambah item ke keranjang
        async addToCart(productId, name) {
            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': this.getCsrf(), 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const data = await res.json();
                
                if (!res.ok) { 
                    this.showToast('⚠️ ' + data.message); 
                    return; 
                }
                
                this.cartCount = data.count;
                
                // Jika nama kepanjangan, potong untuk tampilan toast
                const shortName = name.length > 25 ? name.substring(0, 25) + '...' : name;
                this.showToast(shortName + ' ditambahkan!');
                
                
            } catch(e) {
                this.showToast('⚠️ Terjadi kesalahan jaringan.');
            }
        },
        // Menampilkan notifikasi popup (Toast)
        showToast(message) {
            this.toast.message = message;
            this.toast.show = true;
            
            // Auto hide setelah 3 detik
            setTimeout(() => { 
                this.toast.show = false; 
            }, 3000);
        }
    }));
});
</script>
@endsection
