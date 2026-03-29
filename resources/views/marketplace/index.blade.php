@extends('layouts.app')
@section('title', '| Marketplace')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    {{-- HEADER CARD --}}
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-8 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-orange-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center justify-center bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 md:hidden">Marketplace</div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Smecone <span class="text-red-600">Marketplace</span></h1>
            <p class="text-[13px] md:text-sm text-gray-500 mt-1.5 font-medium">Temukan jajan, barang, dan jasa kreatif dari warga Smecone.</p>
        </div>
        
        <div class="flex gap-3 relative z-10">
            <a href="/marketplace/create" class="bg-red-600 text-white px-6 py-3.5 rounded-[20px] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-2 font-extrabold text-[14px] tap-effect">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Jual Barang</span>
            </a>
            <a href="/marketplace/lapak-saya" class="bg-gray-900 text-white px-6 py-3.5 rounded-[20px] shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:bg-black hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-2 font-extrabold text-[14px] tap-effect">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span>Lapak Saya</span>
            </a>
        </div>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="mb-8 flex flex-col md:flex-row gap-4 relative z-10">
        <form action="/marketplace" method="GET" class="flex-grow relative tap-effect">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Mau jajan apa hari ini?..." 
                   class="w-full pl-11 pr-14 py-3.5 bg-white border border-gray-200 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 shadow-[0_2px_10px_rgba(0,0,0,0.02)] text-[13px] md:text-sm font-bold placeholder-gray-400 transition-all">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-9 h-9 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center hover:bg-red-100 transition-colors active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </form>

        <div class="flex gap-2.5 overflow-x-auto hide-scrollbar shrink-0 pb-2 md:pb-0 snap-x">
            <a href="/marketplace?search={{ request('search') }}" class="snap-start px-5 py-3.5 rounded-[20px] text-[13px] font-extrabold whitespace-nowrap transition-all shadow-sm tap-effect {{ !request('category') ? 'bg-gray-900 text-white shadow-gray-900/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-gray-800' }}">
                Semua
            </a>
            @foreach(['Makanan', 'Minuman', 'Jasa', 'Alat Tulis', 'Elektronik'] as $cat)
                <a href="/marketplace?category={{ $cat }}&search={{ request('search') }}" class="snap-start px-5 py-3.5 rounded-[20px] text-[13px] font-extrabold whitespace-nowrap transition-all shadow-sm tap-effect {{ request('category') == $cat ? 'bg-red-600 text-white shadow-red-600/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-red-600' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- HOT PROMO BANNER --}}
    <div class="bg-gradient-to-r from-red-600 via-red-500 to-orange-500 rounded-[32px] p-8 mb-10 text-white relative overflow-hidden shadow-[0_20px_50px_rgba(220,38,38,0.2)] group">
        <div class="relative z-10 md:w-1/2">
            <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block border border-white/20">Hot Promo 🔥</span>
            <h2 class="text-2xl md:text-4xl font-black mb-2 leading-tight tracking-tight">Jajan Hemat Bebas Lapar!</h2>
            <p class="text-[13px] md:text-base text-red-50 mb-6 font-medium leading-relaxed opacity-90">Dukung lapak temanmu dan temukan jajan favorit sebelum kehabisan!</p>
            <a href="#" class="inline-flex items-center gap-2 bg-white text-red-600 px-6 py-3 rounded-xl font-black text-[13px] hover:shadow-xl hover:scale-105 transition-all active:scale-95">
                Eksplor Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute right-10 top-1/2 -translate-y-1/2 text-[120px] md:text-[180px] opacity-20 transform -rotate-12 group-hover:rotate-0 transition-transform duration-700 pointer-events-none">🍔</div>
    </div>

    {{-- PRODUCT LIST --}}
    <div class="flex justify-between items-center mb-6 px-1">
        <h2 class="text-lg md:text-xl font-extrabold text-gray-900 tracking-tight">Eksplor Produk 🔥</h2>
        <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg">{{ $products->total() }} Produk</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
        @forelse($products as $product)
        <a href="/marketplace/{{ $product->id }}" class="bg-white rounded-[28px] p-3 shadow-[0_2px_15px_rgba(0,0,0,0.02)] border border-gray-100 hover:border-red-100 hover:shadow-[0_8px_30px_rgba(220,38,38,0.08)] transition-all duration-300 group flex flex-col tap-effect">
            
            <div class="relative aspect-square rounded-[22px] overflow-hidden bg-gray-50 mb-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $product->item_name }}">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-100/50">
                        <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif

                <div class="absolute bottom-2.5 left-2.5 right-2.5 flex justify-between items-center">
                    <div class="bg-white/95 backdrop-blur-sm text-gray-900 px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 font-black text-[12px] md:text-[13px]">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>

                @if($product->is_sold)
                    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px] flex items-center justify-center z-10">
                        <span class="bg-white text-gray-900 px-4 py-1.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl">Habis</span>
                    </div>
                @elseif($product->is_promoted)
                    <div class="absolute top-2.5 left-2.5 z-10">
                        <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-[9px] font-black px-2.5 py-1 rounded-lg shadow-md border border-yellow-300 uppercase tracking-widest">
                            ⭐ Promo
                        </span>
                    </div>
                @endif
            </div>

            <div class="px-1 flex-grow flex flex-col">
                <h3 class="text-[14px] md:text-[15px] font-extrabold text-gray-800 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors mb-2">
                    {{ $product->item_name }}
                </h3>
                
                <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2 truncate max-w-[70%]">
                        <div class="w-6 h-6 rounded-full bg-red-50 flex items-center justify-center text-[10px] font-black text-red-600 shrink-0 border border-red-100 shadow-inner">
                            {{ substr($product->user->name, 0, 1) }}
                        </div>
                        <span class="text-[11px] font-bold text-gray-500 truncate">{{ explode(' ', $product->user->name)[0] }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-yellow-500">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="text-[11px] font-black text-gray-700">4.{{ rand(5,9) }}</span>
                    </div>
                </div>
            </div>
        </a>
        @empty
            <div class="col-span-full py-20 bg-white rounded-[32px] border border-gray-100 text-center flex flex-col items-center justify-center shadow-sm">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5 relative group">
                    <svg class="w-12 h-12 text-gray-300 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 mb-2">Belum ada produk</h3>
                <p class="text-[13px] text-gray-500 max-w-sm mx-auto mb-8">Jadilah yang pertama membuka lapak dan berjualan di Smecone!</p>
                <a href="/marketplace/create" class="inline-flex items-center gap-2 bg-red-600 text-white px-8 py-3.5 rounded-xl font-extrabold text-[14px] hover:bg-red-700 hover:shadow-lg transition-all tap-effect active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Buka Lapak Sekarang
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $products->links() }}
    </div>
</div>
@endsection