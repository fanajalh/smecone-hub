@extends('layouts.app')
@section('title', '| Lost & Found')

@section('content')
<div class="max-w-7xl mx-auto pt-4 px-3 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <div class="bg-white px-5 py-4 md:px-6 md:py-5 md:mt-4 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 rounded-2xl border border-gray-100">
        <div>
            <h1 class="text-xl md:text-2xl font-extrabold text-red-600 tracking-tight">Lost & Found</h1>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Pusat pelaporan barang sekolah.</p>
        </div>
        <a href="/lost-found/create" class="w-full md:w-auto bg-red-600 text-white p-3 md:px-5 md:py-3 rounded-xl shadow-md hover:bg-red-700 active:scale-95 transition-all flex items-center justify-center gap-2 font-bold">
            <svg class="w-5 h-5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Lapor Sekarang</span>
        </a>
    </div>

    <div class="mt-4 flex flex-col md:flex-row gap-3">
        <form action="/lost-found" method="GET" class="flex-grow relative">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang, misal: Dompet..." 
                   class="w-full pl-4 pr-12 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 shadow-sm text-sm font-medium">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>

        <div class="flex gap-2 shrink-0 overflow-x-auto hide-scrollbar">
            <a href="/lost-found?search={{ $search }}" class="px-4 py-3 rounded-xl text-sm font-bold shadow-sm whitespace-nowrap {{ !$type ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Semua</a>
            <a href="/lost-found?type=lost&search={{ $search }}" class="px-4 py-3 rounded-xl text-sm font-bold shadow-sm whitespace-nowrap {{ $type == 'lost' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Cari Hilang</a>
            <a href="/lost-found?type=found&search={{ $search }}" class="px-4 py-3 rounded-xl text-sm font-bold shadow-sm whitespace-nowrap {{ $type == 'found' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Penemuan</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">
        @if($items->isEmpty())
            <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-gray-100">
                <p class="text-gray-500 font-bold text-base">Tidak ada data yang cocok.</p>
            </div>
        @endif

        @foreach($items as $item)
        <a href="/lost-found/{{ $item->id }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md hover:border-red-200 transition-all duration-300 relative group">
            
            <div class="absolute top-2 left-2 z-10 px-2 py-1 text-[9px] md:text-[10px] font-black tracking-wider uppercase rounded-md shadow-sm {{ $item->type == 'lost' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ $item->type == 'lost' ? 'Hilang' : 'Ditemukan' }}
            </div>

            @if($item->status == 'pending')
            <div class="absolute inset-0 bg-white/70 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center text-center px-2">
                <svg class="w-6 h-6 text-yellow-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-yellow-800 text-[10px] font-black uppercase tracking-wider bg-yellow-100 px-2 py-1 rounded">Proses Kesiswaan</span>
            </div>
            @elseif($item->status == 'resolved')
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-20 flex items-center justify-center">
                <span class="bg-gray-800 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg">Selesai</span>
            </div>
            @endif

            <div class="aspect-square w-full bg-gray-50 overflow-hidden relative">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                        <svg class="w-8 h-8 opacity-40 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest opacity-50">No Foto</span>
                    </div>
                @endif
            </div>
            
            <div class="p-3 flex flex-col flex-grow justify-between">
                <h2 class="text-sm md:text-base font-bold text-gray-800 leading-tight line-clamp-2">{{ $item->item_name }}</h2>
                <div class="mt-3 flex items-center justify-between border-t border-gray-50 pt-2">
                    <div class="flex items-center gap-1.5 truncate">
                        <div class="w-4 h-4 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
                            <span class="text-[8px] font-bold text-gray-600">{{ substr($item->user->name, 0, 1) }}</span>
                        </div>
                        <span class="text-[10px] text-gray-500 font-semibold truncate">{{ explode(' ', $item->user->name)[0] }}</span>
                    </div>
                    <span class="text-[9px] text-gray-400 shrink-0">{{ $item->created_at->diffForHumans(null, true, true) }}</span>
                </div>
            </div>
        </a>
        @endforeach

    </div>
</div>
@endsection