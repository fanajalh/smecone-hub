@extends('layouts.app')
@section('title', '| Marketplace')

@section('content')
<style>
    /* Menyembunyikan scrollbar horizontal tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Efek tekan pada tombol/card */
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s; }
    
    /* Mencegah Alpine.js berkedip sebelum diload */
    [x-cloak] { display: none !important; }
</style>

{{-- CSRF Token untuk fetch requests API --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div x-data="cartApp()" x-init="fetchCart()" class="bg-gray-50 min-h-screen pb-24 md:pb-12">

    {{-- ======================== CART DRAWER (KERANJANG) ======================== --}}
    {{-- Overlay Gelap --}}
    <div x-show="cartOpen" 
         x-cloak 
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100]" 
         @click="cartOpen = false" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0">
    </div>

    {{-- Panel Drawer Keranjang --}}
    <div x-show="cartOpen" 
         x-cloak 
         class="fixed top-0 right-0 bottom-0 w-[90vw] max-w-[400px] bg-white z-[101] flex flex-col rounded-l-[2rem] shadow-2xl overflow-hidden" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full">

        {{-- Header Drawer --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-white z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-[#E21F26]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-gray-900 text-lg">Keranjang Belanja</h3>
                    <p class="text-xs text-gray-500 font-medium" x-text="cartCount + ' item dipilih'"></p>
                </div>
            </div>
            <button @click="cartOpen = false" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-red-50 hover:text-red-500 transition-colors tap-effect">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Loading Spinner --}}
        <div x-show="cartLoading" class="flex-grow flex items-center justify-center bg-gray-50/50">
            <div class="w-10 h-10 border-4 border-gray-200 border-t-[#E21F26] rounded-full animate-spin"></div>
        </div>

        {{-- Item List Area --}}
        <div x-show="!cartLoading" class="flex-grow overflow-y-auto px-6 py-4 flex flex-col gap-4 bg-gray-50/30 hide-scrollbar">
            
            {{-- State Kosong --}}
            <template x-if="cartItems.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-center pb-10">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-5 border-2 border-dashed border-gray-200">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="font-extrabold text-gray-800 mb-1">Keranjang masih kosong</p>
                    <p class="text-sm text-gray-500 font-medium">Yuk tambah jajan dulu ke keranjangmu!</p>
                </div>
            </template>

            {{-- Looping Item Keranjang --}}
            <template x-for="item in cartItems" :key="item.id">
                <div class="flex items-start gap-4 bg-white border border-gray-100 rounded-2xl p-3 shadow-sm hover:border-red-100 transition-colors">
                    <div class="w-20 h-20 rounded-[1rem] overflow-hidden bg-gray-100 shrink-0">
                        <img :src="item.image || 'https://via.placeholder.com/150'" :alt="item.name" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow min-w-0 py-1">
                        <p class="text-[14px] font-bold text-gray-800 line-clamp-2 leading-tight" x-text="item.name"></p>
                        <p class="text-[13px] font-black text-[#E21F26] mt-1" x-text="'Rp ' + Number(item.price).toLocaleString('id-ID')"></p>
                        
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center bg-gray-50 border border-gray-100 rounded-lg p-0.5">
                                <button @click="updateQty(item, item.qty - 1)" class="w-7 h-7 rounded-md bg-white flex items-center justify-center text-gray-600 hover:text-red-600 hover:bg-red-50 shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                                </button>
                                <span class="text-[13px] font-extrabold text-gray-900 w-8 text-center" x-text="item.qty"></span>
                                <button @click="updateQty(item, item.qty + 1)" class="w-7 h-7 rounded-md bg-white flex items-center justify-center text-gray-600 hover:text-red-600 hover:bg-red-50 shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                            <button @click="removeItem(item)" class="text-gray-400 hover:text-[#E21F26] bg-gray-50 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition-colors tap-effect">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer Drawer (Total Harga) --}}
        <div x-show="!cartLoading && cartItems.length > 0" class="shrink-0 px-6 py-5 border-t border-gray-100 bg-white shadow-[0_-4px_20px_rgba(0,0,0,0.03)] z-10">
            <div class="flex justify-between items-end mb-4">
                <span class="text-sm font-bold text-gray-500">Total Belanja</span>
                <span class="text-2xl font-black text-gray-900" x-text="'Rp ' + Number(cartTotal).toLocaleString('id-ID')"></span>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 text-center mb-4">
                <p class="text-xs text-gray-500 font-medium">Checkout per-item dapat dilakukan di halaman <span class="font-bold text-gray-700">detail produk</span>.</p>
            </div>
            <button @click="clearCart()" class="w-full text-sm font-bold text-red-500 hover:text-red-700 hover:bg-red-50 py-3 rounded-xl transition-colors border border-transparent hover:border-red-100 tap-effect">
                Kosongkan Keranjang
            </button>
        </div>
    </div>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <div class="max-w-7xl mx-auto">

        {{-- 1. STICKY HEADER --}}
        <div class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 md:border md:rounded-2xl md:top-4 md:mt-4 md:mx-4 px-4 py-3 shadow-[0_2px_10px_rgba(0,0,0,0.02)] md:shadow-sm flex items-center gap-3 transition-all">

            {{-- Search Bar --}}
            <form action="/marketplace" method="GET" class="flex-grow relative flex items-center">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="absolute inset-y-0 left-3 md:left-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jajan atau barang..."
                       class="w-full py-2.5 md:py-3 pl-10 md:pl-12 pr-4 bg-gray-100/80 border-transparent rounded-xl text-[13px] md:text-[14px] font-medium text-gray-900 placeholder-gray-500 focus:bg-white focus:border-red-400 focus:ring-2 focus:ring-red-100 transition-all">
            </form>

            {{-- MOBILE: Kelola Lapak & Riwayat & Cart --}}
            <div class="flex items-center gap-1 md:hidden">
                <a href="{{ route('marketplace.purchases') }}" class="flex flex-col items-center justify-center w-11 h-11 rounded-xl hover:bg-red-50 transition-colors group tap-effect" title="Riwayat Belanja">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#E21F26] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[8px] font-bold text-gray-500 group-hover:text-[#E21F26] leading-none mt-1">Belanja</span>
                </a>
                <a href="{{ route('marketplace.sales') }}" class="flex flex-col items-center justify-center w-11 h-11 rounded-xl hover:bg-red-50 transition-colors group tap-effect" title="Riwayat Penjualan">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#E21F26] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[8px] font-bold text-gray-500 group-hover:text-[#E21F26] leading-none mt-1">Jualan</span>
                </a>
                <a href="/marketplace/lapak-saya" class="flex flex-col items-center justify-center w-11 h-11 rounded-xl hover:bg-red-50 transition-colors group tap-effect">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#E21F26] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="text-[8px] font-bold text-gray-500 group-hover:text-[#E21F26] leading-none mt-1">Lapak</span>
                </a>
                <a href="/keranjang" class="relative flex flex-col items-center justify-center w-11 h-11 rounded-xl hover:bg-red-50 transition-colors group tap-effect">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#E21F26] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-show="cartCount > 0" class="absolute -top-1.5 right-0.5 bg-[#E21F26] text-white text-[9px] font-black w-[18px] h-[18px] rounded-full flex items-center justify-center border-2 border-white shadow-sm" x-text="cartCount > 99 ? '99+' : cartCount"></span>
                    <span class="text-[8px] font-bold text-gray-500 group-hover:text-[#E21F26] leading-none mt-1">Cart</span>
                </a>
            </div>

            {{-- DESKTOP: Cart button and Menu --}}
            <div class="hidden md:flex items-center gap-1 shrink-0">
                <a href="{{ route('marketplace.purchases') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#E21F26] bg-transparent hover:bg-red-50 px-4 py-2.5 rounded-xl transition-all tap-effect font-bold text-[13px] mr-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Belanja
                </a>
                <a href="{{ route('marketplace.sales') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#E21F26] bg-transparent hover:bg-red-50 px-4 py-2.5 rounded-xl transition-all tap-effect font-bold text-[13px] mr-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Riwayat Jualan
                </a>
                <a href="/keranjang" class="flex items-center gap-2 bg-gray-50 hover:bg-red-50 border border-gray-100 hover:border-red-100 text-gray-700 hover:text-[#E21F26] px-5 py-2.5 rounded-xl font-extrabold text-sm transition-all relative shrink-0 tap-effect shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Keranjang
                    <span x-show="cartCount > 0" class="bg-[#E21F26] text-white text-[10px] font-black px-2 py-0.5 rounded-full ml-1" x-text="cartCount"></span>
                </a>
            </div>
        </div>

        {{-- 2. SELLER ACTIONS --}}
        <div class="bg-white md:bg-transparent px-4 py-5 md:py-8 shadow-sm md:shadow-none rounded-b-3xl md:rounded-none relative overflow-hidden mb-4 md:mb-0 md:mt-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div class="md:bg-white md:p-6 md:rounded-[2rem] md:shadow-sm md:border md:border-gray-100 md:flex-1">
                    <h1 class="text-xl md:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">Smecone Marketplace <span class="hidden md:inline">🛍️</span></h1>
                    <p class="text-[13px] md:text-[15px] text-gray-500 font-medium mt-1 md:mt-2 max-w-md">Pusat jajan, pre-loved, dan karya kreatif warga Smecone.</p>
                </div>
                <div class="flex gap-3 md:gap-4 md:shrink-0">
                    <a href="/marketplace/create" class="flex-1 md:flex-none bg-[#E21F26] text-white py-3.5 md:py-4 px-4 md:px-6 rounded-2xl shadow-[0_4px_12px_rgba(226,31,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 tap-effect font-bold text-[13px] md:text-[15px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Jual Barang
                    </a>
                    <a href="/marketplace/lapak-saya" class="flex-1 md:flex-none bg-gray-900 text-white py-3.5 md:py-4 px-4 md:px-6 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.15)] hover:bg-black hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 tap-effect font-bold text-[13px] md:text-[15px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Kelola Lapak
                    </a>
                </div>
            </div>
        </div>

        {{-- 3. HOT PROMO BANNER & KATEGORI --}}
        <div class="px-4 py-2 md:py-4 md:flex md:gap-6">
            <div class="md:w-7/12 lg:w-2/3 bg-gradient-to-r from-orange-400 to-[#E21F26] rounded-[1.5rem] md:rounded-[2.5rem] p-6 md:p-10 flex items-center justify-between shadow-sm md:shadow-md relative overflow-hidden mb-5 md:mb-0 group">
                <div class="absolute right-0 top-0 w-1/2 h-full opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] group-hover:scale-105 transition-transform duration-700 pointer-events-none"></div>
                <div class="relative z-10">
                    <span class="bg-white/20 backdrop-blur-sm text-white border border-white/30 text-[10px] md:text-[11px] font-black px-3 py-1.5 rounded-md uppercase tracking-widest inline-block mb-2 md:mb-4 shadow-sm">🔥 Hot Promo</span>
                    <h2 class="text-white font-black text-2xl md:text-4xl lg:text-5xl leading-tight md:mb-2 text-shadow-sm">Jajan Hemat<br>Bebas Lapar!</h2>
                    <p class="hidden md:block text-white/90 text-[15px] font-medium max-w-sm mt-3">Temukan jajanan favorit dan barang incaran dari teman-temanmu.</p>
                </div>
                <div class="relative z-10 text-6xl md:text-8xl lg:text-9xl drop-shadow-2xl transform -rotate-12 group-hover:rotate-0 group-hover:scale-110 transition-transform duration-500">🍔</div>
            </div>

            <div class="md:w-5/12 lg:w-1/3 md:bg-white md:p-6 md:rounded-[2.5rem] md:shadow-sm md:border md:border-gray-100 flex flex-col justify-center">
                <h3 class="hidden md:block text-gray-900 font-extrabold text-xl mb-5">Kategori Pilihan</h3>
                <div class="flex overflow-x-auto hide-scrollbar gap-3 md:gap-4 pb-2 md:pb-0 snap-x md:flex-wrap">
                    @php
                        $categories = [
                            'Semua' => ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'active' => !request('category')],
                            'Makanan' => ['icon' => 'M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z', 'active' => request('category') === 'Makanan'],
                            'Minuman' => ['icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18', 'active' => request('category') === 'Minuman'],
                            'Jasa' => ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'active' => request('category') === 'Jasa'],
                            'Elektronik' => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'active' => request('category') === 'Elektronik'],
                        ];
                    @endphp
                    @foreach($categories as $catName => $catData)
                        @php
                            $href = $catName === 'Semua' ? '/marketplace' : '/marketplace?category=' . $catName . '&search=' . request('search');
                            $btnClass = $catData['active']
                                ? 'bg-red-100 border-2 border-red-200 text-red-600'
                                : 'bg-white border border-gray-100 text-gray-500 shadow-[0_2px_8px_rgba(0,0,0,0.03)] hover:bg-red-50 hover:text-red-600 hover:border-red-100';
                            $labelClass = $catData['active'] ? 'text-red-600 font-bold' : 'text-gray-600 font-medium';
                        @endphp
                        <a href="{{ $href }}" class="flex flex-col items-center gap-2 min-w-[75px] md:min-w-[80px] md:flex-1 snap-start tap-effect group">
                            <div class="w-[3.5rem] h-[3.5rem] md:w-[4rem] md:h-[4rem] rounded-[1.25rem] md:rounded-[1.5rem] {{ $btnClass }} flex items-center justify-center transition-all duration-300">
                                <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $catData['icon'] }}"></path></svg>
                            </div>
                            <span class="text-[11px] md:text-[12px] {{ $labelClass }} text-center truncate w-full px-1 group-hover:text-red-600 transition-colors">{{ $catName }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 4. PRODUCT GRID --}}
        <div class="px-4 mt-6 md:mt-10">
            <div class="flex justify-between items-end mb-5 md:mb-6">
                <div>
                    <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Eksplor Jajan 🔥</h2>
                    <p class="hidden md:block text-[15px] text-gray-500 font-medium mt-1">Beragam pilihan untukmu hari ini</p>
                </div>
                <span class="text-[11px] md:text-[13px] font-bold text-gray-500 bg-gray-200/60 md:bg-white md:border md:border-gray-200 md:shadow-sm px-3 py-1.5 rounded-lg">{{ $products->total() }} Item</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3.5 md:gap-5">
                @forelse($products as $product)
                <div class="bg-white rounded-[1.25rem] md:rounded-[1.5rem] border border-gray-100 shadow-[0_4px_15px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_30px_rgba(226,31,38,0.08)] hover:border-red-100 flex flex-col transition-all duration-300 overflow-hidden relative group">

                    @if($product->is_promoted)
                    <div class="absolute top-2 left-2 md:top-3 md:left-3 z-10 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-[9px] md:text-[10px] font-black px-2.5 py-1 rounded-full shadow-md border border-yellow-300">PROMO</div>
                    @endif

                    {{-- Image --}}
                    <div class="aspect-square w-full bg-gray-50 relative overflow-hidden">
                        <a href="/marketplace/{{ $product->id }}" class="block w-full h-full">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" alt="{{ $product->item_name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 group-hover:bg-gray-100 transition-colors">
                                    <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </a>

                        @if($product->is_sold)
                        <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px] flex items-center justify-center z-10">
                            <div class="bg-white/95 text-gray-900 rounded-xl px-3 py-1.5 text-[11px] font-black uppercase tracking-wider shadow-lg -rotate-6 border-2 border-white">Habis</div>
                        </div>
                        @else
                        {{-- Quick Add (hover) --}}
                        <button
                            @click="addToCart({{ $product->id }}, '{{ addslashes($product->item_name) }}')"
                            class="absolute bottom-2 md:bottom-3 left-2 md:left-3 right-2 md:right-3 bg-white/90 backdrop-blur-sm border border-white text-gray-900 text-[11px] md:text-[12px] font-extrabold py-2 md:py-2.5 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 shadow-lg hover:bg-[#E21F26] hover:text-white z-10 tap-effect hidden md:block">
                            + Keranjang
                        </button>
                        @endif
                    </div>

                    {{-- Detail --}}
                    <div class="p-3 md:p-4 flex flex-col flex-grow">
                        <a href="/marketplace/{{ $product->id }}">
                            <h3 class="text-[13px] md:text-[15px] text-gray-800 font-bold leading-snug line-clamp-2 h-[38px] md:h-[44px] group-hover:text-[#E21F26] transition-colors">{{ $product->item_name }}</h3>
                        </a>
                        <div class="mt-auto pt-3">
                            <div class="flex items-center justify-between">
                                <div class="text-[#E21F26] font-black text-[16px] md:text-[18px]">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                @if(!$product->is_sold)
                                {{-- Mobile Quick Add Button --}}
                                <button
                                    @click="addToCart({{ $product->id }}, '{{ addslashes($product->item_name) }}')"
                                    class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center text-[#E21F26] hover:bg-[#E21F26] hover:text-white transition-colors tap-effect shrink-0 md:hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-50">
                                <div class="flex items-center gap-1.5 truncate max-w-[65%]">
                                    <div class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-[9px] font-bold text-gray-600 shrink-0">{{ substr($product->user->name, 0, 1) }}</div>
                                    <span class="text-[11px] text-gray-500 font-medium truncate">{{ explode(' ', $product->user->name)[0] }}</span>
                                </div>
                                <div class="flex items-center gap-0.5 text-[10px] font-bold text-yellow-500 bg-yellow-50 px-1.5 py-0.5 rounded border border-yellow-100/50">
                                    <span>★</span>
                                    <span>4.{{ rand(5,9) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-white rounded-[2rem] border border-dashed border-gray-200 shadow-sm relative">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-5 ring-8 ring-white shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-extrabold mb-2 text-lg">Yah, belum ada produk</h3>
                    <p class="text-[14px] text-gray-500 mb-6 max-w-[250px]">Jadilah yang pertama berjualan dan pamerkan produk buatanmu!</p>
                    <a href="/marketplace/create" class="bg-[#E21F26] text-white text-[14px] font-bold px-8 py-3.5 rounded-xl shadow-[0_4px_15px_rgba(226,31,38,0.25)] hover:bg-red-700 transition-all tap-effect hover:-translate-y-0.5">Buka Lapak Sekarang</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- 5. PAGINATION --}}
        @if($products->hasPages())
        <div class="px-4 mt-10 mb-10 flex justify-center">
            <div class="bg-white p-2.5 rounded-2xl shadow-sm border border-gray-100">
                {{ $products->links() }}
            </div>
        </div>
        @endif

    </div>

    {{-- Toast Notification --}}
    <div x-show="toast.show" 
         x-cloak 
         class="fixed bottom-24 md:bottom-8 left-1/2 -translate-x-1/2 z-[200] bg-gray-900/95 backdrop-blur-sm text-white px-5 py-3.5 rounded-[1rem] shadow-2xl text-[13px] md:text-sm font-bold flex items-center gap-3 whitespace-nowrap border border-gray-700" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 scale-95">
        <svg class="w-5 h-5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        <span x-text="toast.message"></span>
    </div>

</div>

<script>
/**
 * Aplikasi Alpine.js untuk fitur Keranjang (Cart)
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('cartApp', () => ({
        cartOpen: false,
        cartItems: [],
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
                const data = await res.json();
                this.cartItems = data.items;
                this.cartCount = data.count;
                this.cartTotal = data.total;
            } catch(e) {
                console.error("Gagal mengambil data keranjang", e);
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
                
                // Jika drawer sedang terbuka, update list itemnya
                if (this.cartOpen) this.fetchCart();
                
            } catch(e) {
                this.showToast('⚠️ Terjadi kesalahan jaringan.');
            }
        },

        // Memperbarui kuantitas (Qty) item di keranjang
        async updateQty(item, newQty) {
            // Jika Qty jadi 0, maka hapus item
            if (newQty < 1) { 
                this.removeItem(item); 
                return; 
            }
            
            try {
                await fetch('/cart/' + item.id + '/qty', {
                    method: 'PATCH',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': this.getCsrf(), 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ qty: newQty })
                });
                
                // Update UI secara optimistik (tanpa reload full data)
                item.qty = newQty;
                this.calculateTotals();
                
            } catch(e) {
                console.error("Gagal update kuantitas", e);
            }
        },

        // Menghapus 1 jenis item dari keranjang
        async removeItem(item) {
            try {
                const res = await fetch('/cart/' + item.id, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': this.getCsrf(), 
                        'Accept': 'application/json' 
                    }
                });
                
                const data = await res.json();
                
                // Filter hapus dari array lokal
                this.cartItems = this.cartItems.filter(i => i.id !== item.id);
                this.cartCount = data.count;
                this.calculateTotals();
                
            } catch(e) {
                console.error("Gagal hapus item", e);
            }
        },

        // Menghapus SEMUA isi keranjang
        async clearCart() {
            const result = await Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: "Yakin ingin membuang semua barang di keranjang belanja Anda?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[28px] shadow-2xl border border-gray-100 p-6',
                    title: 'font-black text-2xl tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-sm font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-red-200 active:scale-95 transition-transform mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-200 active:scale-95 transition-transform mx-2'
                }
            });
            
            if (!result.isConfirmed) return;
            
            try {
                await fetch('/cart', {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': this.getCsrf(), 
                        'Accept': 'application/json' 
                    }
                });
                
                // Bersihkan data lokal
                this.cartItems = [];
                this.cartCount = 0;
                this.cartTotal = 0;
                this.cartOpen = false; // Tutup laci otomatis
                this.showToast('Keranjang berhasil dikosongkan');
                
            } catch(e) {
                console.error("Gagal mengosongkan keranjang", e);
            }
        },
        
        // Kalkulasi ulang Total (Digunakan untuk optimis UI)
        calculateTotals() {
            this.cartTotal = this.cartItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
            this.cartCount = this.cartItems.reduce((sum, item) => sum + item.qty, 0);
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