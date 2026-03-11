@extends('layouts.app')
@section('title', '| Marketplace')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-6 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-orange-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-red-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10 w-full md:w-auto">
            <div class="inline-flex items-center justify-center bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 md:hidden">Marketplace</div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Smecone <span class="text-red-600">Mart</span></h1>
            <p class="text-[13px] md:text-sm text-gray-500 mt-1.5 font-medium">Pusat jajan dan jasa karya warga sekolah Smecone.</p>
        </div>
        
        <a href="/marketplace/create" class="w-full md:w-auto bg-red-600 text-white px-6 py-3.5 rounded-[20px] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-2 font-extrabold text-[14px] relative z-10 tap-effect animate-float">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Buka Lapak</span>
        </a>
    </div>

    <div class="mb-8 flex flex-col md:flex-row gap-4 relative z-10">
        
        <form action="/marketplace" method="GET" class="w-full md:w-[340px] shrink-0 relative tap-effect">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jajanan atau jasa..." 
                   class="w-full pl-11 pr-14 py-3.5 bg-white border border-gray-200 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 shadow-[0_2px_10px_rgba(0,0,0,0.02)] text-[13px] md:text-sm font-bold placeholder-gray-400 transition-all">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-9 h-9 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center hover:bg-red-100 transition-colors active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </form>

        <div class="flex gap-2.5 overflow-x-auto hide-scrollbar pb-2 md:pb-0 snap-x items-center w-full">
            <a href="/marketplace?search={{ request('search') }}" class="snap-start px-5 py-3 rounded-[18px] text-[12px] font-extrabold whitespace-nowrap transition-all tap-effect {{ !request('category') ? 'bg-gray-900 text-white shadow-md shadow-gray-900/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-gray-800 shadow-sm' }}">
                Semua
            </a>
            @foreach(['Makanan', 'Jasa', 'Alat Tulis', 'Elektronik'] as $cat)
                <a href="/marketplace?category={{ $cat }}&search={{ request('search') }}" class="snap-start px-5 py-3 rounded-[18px] text-[12px] font-extrabold whitespace-nowrap transition-all tap-effect {{ request('category') == $cat ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-red-600 shadow-sm' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">
        @forelse($products as $product)
        <a href="/marketplace/{{ $product->id }}" class="group bg-white rounded-[24px] border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_8px_30px_rgba(220,38,38,0.08)] hover:border-red-100 transition-all duration-300 flex flex-col hover:-translate-y-1 tap-effect">
            
            <div class="aspect-square relative overflow-hidden bg-gray-50">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-100/50">
                        <svg class="w-10 h-10 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-40">No Foto</span>
                    </div>
                @endif
                
                <div class="absolute top-2 left-2 md:top-3 md:left-3 z-10">
                    <span class="px-2.5 py-1 rounded-lg bg-gray-900/60 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-wider border border-white/20 shadow-sm">
                        {{ $product->category }}
                    </span>
                </div>
                
                @if($product->type == 'Pre-Order')
                    <div class="absolute top-2 right-2 md:top-3 md:right-3 z-10">
                        <span class="px-2.5 py-1 rounded-lg bg-orange-500/90 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-wider shadow-sm border border-orange-400/50 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> PO
                        </span>
                    </div>
                @endif
            </div>

            <div class="p-3 md:p-4 flex flex-col flex-1 bg-white">
                <div class="text-red-600 font-black text-base md:text-[18px] mb-1 leading-none tracking-tight">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                
                <h3 class="text-[13px] md:text-[14px] font-bold text-gray-800 line-clamp-2 mb-3 leading-snug group-hover:text-red-600 transition-colors">
                    {{ $product->item_name }}
                </h3>
                
                <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 truncate max-w-[70%]">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-[9px] md:text-[10px] font-black text-gray-500 uppercase shrink-0">
                            {{ substr($product->user->name, 0, 1) }}
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-500 truncate">
                            {{ explode(' ', $product->user->name)[0] }}
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-0.5 shrink-0 text-yellow-500">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="text-[10px] font-black text-gray-400">Baru</span>
                    </div>
                </div>
            </div>
            
        </a>
        @empty
        <div class="col-span-full py-16 md:py-24 bg-white rounded-[32px] border border-gray-100 text-center flex flex-col items-center justify-center shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5 relative group">
                <svg class="w-12 h-12 text-gray-300 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <div class="absolute top-2 right-2 text-xl opacity-30">🛒</div>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 mb-1">Yah, tokonya masih kosong</h3>
            <p class="text-[13px] text-gray-500 max-w-sm mx-auto mb-6">Belum ada barang atau jasa yang dijual di kategori ini. Coba kata kunci atau kategori lain.</p>
            
            @if(request('search') || request('category'))
                <a href="/marketplace" class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-6 py-3 rounded-xl font-extrabold text-[13px] hover:bg-red-100 hover:shadow-sm transition-all tap-effect active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Lihat Semua Barang
                </a>
            @else
                <a href="/marketplace/create" class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-xl font-extrabold text-[13px] hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 transition-all tap-effect active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Jadilah Penjual Pertama!
                </a>
            @endif
        </div>
        @endforelse
    </div>
</div>
@endsection