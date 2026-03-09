@extends('layouts.admin')
@section('title', '| Dashboard')

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="bg-gradient-to-b from-red-600 to-red-700 w-full pt-6 pb-24 md:pt-10 md:pb-28 px-4 sm:px-6 lg:px-8 relative rounded-b-[2.5rem] md:rounded-b-[3.5rem] shadow-inner">
    <div class="max-w-7xl mx-auto flex justify-between items-start relative z-10">
        <div class="text-white">
            <div class="flex items-center gap-2 mb-1.5 opacity-90">
                <svg class="w-5 h-5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-xs font-bold uppercase tracking-widest text-red-100">Pusat Kendali Kesiswaan</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, Bapak/Ibu!</h1>
            <p class="text-red-100 text-sm md:text-base mt-1 font-medium opacity-90">Rekapitulasi Lost & Found SMK Negeri 1 Purwokerto</p>
        </div>
        <div class="bg-white/15 backdrop-blur-sm text-white px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-2 border border-white/20 shadow-sm hidden md:flex">
            <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse border-2 border-white"></div>
            {{ now()->translatedFormat('l, d M Y') }}
        </div>
    </div>
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-3xl z-0"></div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 md:-mt-20 relative z-20 pb-12">
    
    <div class="flex gap-3 md:gap-4 overflow-x-auto hide-scrollbar pb-4 snap-x px-1">
        <div class="min-w-[150px] md:min-w-0 flex-1 bg-white p-5 md:p-6 rounded-3xl shadow-lg border border-gray-100 shrink-0 snap-center relative overflow-hidden transition hover:border-red-100">
            <div class="absolute right-0 top-0 w-14 h-14 bg-yellow-50 rounded-bl-full flex items-start justify-end p-2.5 border-l border-b border-yellow-100">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Antrean Baru</p>
            <p class="text-3xl md:text-4xl font-black text-gray-900">{{ $totalPending }}</p>
        </div>
        
        <div class="min-w-[150px] md:min-w-0 flex-1 bg-white p-5 md:p-6 rounded-3xl shadow-lg border border-gray-100 shrink-0 snap-center relative overflow-hidden transition hover:border-red-100">
            <div class="absolute right-0 top-0 w-14 h-14 bg-red-50 rounded-bl-full flex items-start justify-end p-2.5 border-l border-b border-red-100">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tayang di Publik</p>
            <p class="text-3xl md:text-4xl font-black text-red-600">{{ $totalActive }}</p>
        </div>
        
        <div class="min-w-[150px] md:min-w-0 flex-1 bg-white p-5 md:p-6 rounded-3xl shadow-lg border border-gray-100 shrink-0 snap-center relative overflow-hidden transition hover:border-red-100">
            <div class="absolute right-0 top-0 w-14 h-14 bg-green-50 rounded-bl-full flex items-start justify-end p-2.5 border-l border-b border-green-100">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Selesai</p>
            <p class="text-3xl md:text-4xl font-black text-gray-900">{{ $totalResolved }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-2 mb-6 bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl text-xs md:text-sm font-bold shadow-sm flex items-center gap-2.5">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 md:mt-8">
        <div class="flex justify-between items-end mb-4 px-1">
            <div>
                <h2 class="text-lg md:text-xl font-extrabold text-gray-900">1. Antrean Verifikasi Baru</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-0.5">Siswa membuat laporan, mohon verifikasi sebelum dipublikasikan.</p>
            </div>
            <span class="text-xs md:text-sm font-bold text-yellow-700 bg-yellow-50 px-3 py-1 rounded-full border border-yellow-200">{{ $pendingItems->count() }} Antrean</span>
        </div>

        <div class="flex flex-col gap-4">
            @forelse($pendingItems as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 transition hover:border-yellow-200">
                
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-200">
                        @if($item->image) 
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover"> 
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-yellow-100 text-yellow-700 text-[9px] md:text-[10px] font-black px-2 py-0.5 rounded uppercase">{{ $item->type == 'lost' ? 'Kehilangan' : 'Penemuan' }}</span>
                            <span class="text-[10px] md:text-xs text-gray-400 font-medium">{{ $item->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <h3 class="text-base md:text-lg font-bold text-gray-900 leading-tight">{{ $item->item_name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $item->description }}</p>
                        
                        <div class="flex items-center gap-2 mt-2 bg-gray-50 p-1.5 rounded-lg w-fit border border-gray-100">
                            <div class="w-5 h-5 bg-white rounded-full flex items-center justify-center text-[10px] font-bold text-gray-700 shadow-sm">{{ substr($item->user->name, 0, 1) }}</div>
                            <span class="text-[11px] md:text-xs text-gray-600 font-semibold pr-2">Pelapor: {{ $item->user->name }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row gap-2 shrink-0 border-t border-gray-100 md:border-none pt-3 md:pt-0">
                    <form action="/admin/lost-found/{{ $item->id }}" method="POST" class="w-full md:w-auto">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Tolak laporan ini?')" class="w-full bg-white text-red-500 border border-red-200 px-4 py-2.5 rounded-lg font-bold hover:bg-red-50 text-xs flex justify-center items-center shadow-sm">
                            Tolak
                        </button>
                    </form>
                    <a href="/chat/item/{{ $item->id }}" class="w-full md:w-auto bg-white text-gray-700 border border-gray-300 px-4 py-2.5 rounded-lg font-bold hover:bg-gray-50 text-xs flex justify-center items-center shadow-sm">
                        Chat Pelapor
                    </a>
                    <form action="/admin/lost-found/{{ $item->id }}/confirm" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-blue-700 text-xs flex justify-center items-center shadow-md">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            Publikasikan
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="font-bold text-gray-800">Antrean Bersih!</p>
                <p class="text-sm text-gray-500 mt-1">Tidak ada laporan baru yang menunggu verifikasi.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10 md:mt-12">
        <div class="flex justify-between items-end mb-4 px-1">
            <div>
                <h2 class="text-lg md:text-xl font-extrabold text-gray-900">2. Sedang Tayang di Publik</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-0.5">Daftar barang yang sedang dicari/menunggu diklaim siswa.</p>
            </div>
            <span class="text-xs md:text-sm font-bold text-red-700 bg-red-50 px-3 py-1 rounded-full border border-red-200">{{ $activeItems->count() }} Laporan</span>
        </div>

        <div class="flex flex-col gap-4">
            @forelse($activeItems as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border-l-4 {{ $item->type == 'lost' ? 'border-l-red-500' : 'border-l-green-500' }}">
                
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-200">
                        @if($item->image) 
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover"> 
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-6 h-6 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-[9px] font-black uppercase tracking-wider {{ $item->type == 'lost' ? 'text-red-500' : 'text-green-600' }}">{{ $item->type == 'lost' ? 'Kehilangan' : 'Penemuan' }}</span>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 leading-tight"><a href="/lost-found/{{ $item->id }}" target="_blank" class="hover:text-red-600">{{ $item->item_name }}</a></h3>
                        <p class="text-xs text-gray-500 mt-1">Pelapor: <span class="font-bold">{{ $item->user->name }}</span></p>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row gap-2 shrink-0 border-t border-gray-100 md:border-none pt-3 md:pt-0 w-full md:w-auto">
                    <a href="/chat/item/{{ $item->id }}" class="w-full md:w-auto bg-white text-gray-700 border border-gray-300 px-4 py-2.5 rounded-lg font-bold hover:bg-gray-50 text-xs flex justify-center items-center shadow-sm">
                        Chat Pelapor / Pengklaim
                    </a>
                    <form action="/admin/lost-found/{{ $item->id }}/resolve" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="submit" onclick="return confirm('Tandai selesai? Barang ini akan ditarik/dihapus dari halaman publik.')" class="w-full bg-gray-900 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-black text-xs flex justify-center items-center shadow-md">
                            Tandai Kasus Selesai
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center shadow-sm">
                <p class="font-bold text-gray-800">Tidak ada laporan yang tayang.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection