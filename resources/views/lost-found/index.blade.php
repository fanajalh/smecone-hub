@extends('layouts.app')
@section('title', '| Lost & Found')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-6 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10 w-full md:w-auto">
            <div class="inline-flex items-center justify-center bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 md:hidden">Pusat Bantuan</div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Lost & <span class="text-red-600">Found</span></h1>
            <p class="text-[13px] md:text-sm text-gray-500 mt-1.5 font-medium">Bantu teman temukan barangnya, dapatkan pahala & poin!</p>
        </div>
        
        <a href="/lost-found/create" class="w-full md:w-auto bg-red-600 text-white px-6 py-3.5 rounded-[20px] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-2 font-extrabold text-[14px] relative z-10 tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Lapor Barang</span>
        </a>
    </div>

    <div class="mb-8 flex flex-col md:flex-row gap-4 relative z-10">
        <form action="/lost-found" method="GET" class="flex-grow relative tap-effect">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang, misal: Dompet..." 
                   class="w-full pl-11 pr-14 py-3.5 bg-white border border-gray-200 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 shadow-[0_2px_10px_rgba(0,0,0,0.02)] text-[13px] md:text-sm font-bold placeholder-gray-400 transition-all">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-9 h-9 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center hover:bg-red-100 transition-colors active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </form>

        <div class="flex gap-2.5 overflow-x-auto hide-scrollbar shrink-0 pb-2 md:pb-0 snap-x">
            <a href="/lost-found?search={{ $search }}" class="snap-start px-5 py-3.5 rounded-[20px] text-[13px] font-extrabold whitespace-nowrap transition-all shadow-sm tap-effect {{ !$type ? 'bg-gray-900 text-white shadow-gray-900/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-gray-800' }}">
                Semua
            </a>
            <a href="/lost-found?type=lost&search={{ $search }}" class="snap-start px-5 py-3.5 rounded-[20px] text-[13px] font-extrabold whitespace-nowrap transition-all shadow-sm tap-effect flex items-center gap-1.5 {{ $type == 'lost' ? 'bg-red-600 text-white shadow-red-600/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-red-600' }}">
                <span class="w-2 h-2 rounded-full {{ $type == 'lost' ? 'bg-white' : 'bg-red-500' }}"></span> Kehilangan
            </a>
            <a href="/lost-found?type=found&search={{ $search }}" class="snap-start px-5 py-3.5 rounded-[20px] text-[13px] font-extrabold whitespace-nowrap transition-all shadow-sm tap-effect flex items-center gap-1.5 {{ $type == 'found' ? 'bg-green-600 text-white shadow-green-600/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-green-600' }}">
                <span class="w-2 h-2 rounded-full {{ $type == 'found' ? 'bg-white' : 'bg-green-500' }}"></span> Penemuan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
        
        @forelse($items as $item)
        <a href="/lost-found/{{ $item->id }}" class="bg-white rounded-[24px] shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group tap-effect">
            
            <div class="absolute top-3 left-3 z-10 px-2.5 py-1 text-[9px] md:text-[10px] font-black tracking-widest uppercase rounded-lg shadow-md backdrop-blur-md {{ $item->type == 'lost' ? 'bg-red-500/90 text-white border border-red-400/50' : 'bg-green-500/90 text-white border border-green-400/50' }}">
                {{ $item->type == 'lost' ? 'HILANG' : 'DITEMUKAN' }}
            </div>

            <div class="aspect-square w-full bg-gray-50 overflow-hidden relative">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-100/50">
                        <svg class="w-10 h-10 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-40">No Foto</span>
                    </div>
                @endif

                @if($item->status == 'pending')
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-yellow-900/80 to-transparent pt-8 pb-2 px-3 z-20 flex items-end justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                            </span>
                            <span class="text-white text-[9px] md:text-[10px] font-extrabold uppercase tracking-wider text-shadow-sm">Verifikasi</span>
                        </div>
                    </div>
                @elseif($item->status == 'resolved')
                    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px] z-20 flex items-center justify-center">
                        <div class="bg-white/90 backdrop-blur-md text-gray-900 border border-white/50 px-4 py-2 rounded-xl flex items-center gap-1.5 shadow-xl transform -rotate-12 scale-110">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-[11px] font-black uppercase tracking-widest">Selesai</span>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="p-3 md:p-4 flex flex-col flex-grow bg-white">
                <h2 class="text-[13px] md:text-[15px] font-extrabold text-gray-800 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">{{ $item->item_name }}</h2>
                
                <div class="mt-auto pt-3 flex items-center justify-between border-t border-gray-50">
                    <div class="flex items-center gap-1.5 truncate max-w-[65%]">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-gray-100 flex items-center justify-center shrink-0 border border-gray-200">
                            <span class="text-[9px] md:text-[10px] font-black text-gray-600 uppercase">{{ substr($item->user->name, 0, 1) }}</span>
                        </div>
                        <span class="text-[10px] md:text-[11px] text-gray-500 font-bold truncate">{{ explode(' ', $item->user->name)[0] }}</span>
                    </div>
                    <span class="text-[9px] md:text-[10px] font-bold text-gray-400 shrink-0 uppercase tracking-tighter">{{ $item->created_at->diffForHumans(null, true, true) }}</span>
                </div>
            </div>
        </a>
        @empty
            <div class="col-span-full py-16 md:py-24 bg-white rounded-[32px] border border-gray-100 text-center flex flex-col items-center justify-center shadow-sm">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5 relative">
                    <svg class="w-12 h-12 text-gray-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <div class="absolute top-2 right-2 text-xl opacity-20">❓</div>
                </div>
                <h3 class="text-lg font-extrabold text-gray-900 mb-1">Tidak ada barang yang cocok</h3>
                <p class="text-[13px] text-gray-500 max-w-xs mx-auto mb-6">Mungkin barang yang kamu cari belum dilaporkan atau coba gunakan kata kunci lain.</p>
                
                @if($search || $type)
                    <a href="/lost-found" class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-5 py-2.5 rounded-xl font-extrabold text-[13px] hover:bg-red-100 transition tap-effect">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset Pencarian
                    </a>
                @endif
            </div>
        @endforelse

    </div>
</div>
@endsection