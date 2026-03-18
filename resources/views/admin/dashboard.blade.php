@extends('layouts.admin')
@section('title', '| Dashboard Admin')

@section('content')
<style>
    /* Sembunyikan scrollbar tapi tetap bisa di-swipe */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Efek klik ala aplikasi iOS/Android */
    .tap-effect { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
    .tap-effect:active { transform: scale(0.96); }
    
    /* Hover scale hanya aktif di desktop (mencegah bug nyangkut di mobile) */
    @media (hover: hover) {
        .hover-scale:hover { transform: translateY(-4px); box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
    }
</style>

<div class="bg-gradient-to-br from-red-600 via-red-600 to-red-800 w-full pt-8 pb-24 md:pt-12 md:pb-32 px-4 sm:px-6 lg:px-8 relative rounded-b-[40px] md:rounded-b-[60px] shadow-sm overflow-hidden">
    <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-10 rounded-full blur-2xl"></div>
    <div class="absolute -left-10 bottom-0 w-40 h-40 bg-red-400 opacity-20 rounded-full blur-xl"></div>

    <div class="max-w-7xl mx-auto flex justify-between items-center relative z-10">
        <div class="text-white">
            <p class="text-red-100 text-[11px] md:text-sm font-bold uppercase tracking-widest mb-1 opacity-90">Pusat Kendali</p>
            <h1 class="text-2xl md:text-4xl font-black tracking-tight leading-tight">Halo, Admin! 👋</h1>
        </div>
        
        <div class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-4 py-2 rounded-2xl text-[10px] md:text-xs font-bold flex flex-col items-end shadow-sm">
            <span class="opacity-80 font-medium">Hari ini</span>
            <span>{{ now()->translatedFormat('d M Y') }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-14 md:-mt-20 relative z-20 pb-8">
    <div class="grid grid-cols-2 gap-3 md:gap-6">
        
        <div class="bg-white p-4 md:p-6 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-50 flex flex-col justify-between tap-effect hover-scale group cursor-pointer">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-14 md:h-14 bg-yellow-50 rounded-2xl flex items-center justify-center border border-yellow-100 group-hover:bg-yellow-100 transition-colors">
                    <span class="text-lg md:text-2xl">🏆</span>
                </div>
                <span class="bg-green-50 text-green-600 text-[9px] md:text-[10px] font-black px-2 py-1 rounded-lg">LIVE</span>
            </div>
            <div>
                <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Prestasi</p>
                <div class="flex items-baseline gap-1">
                    <p class="text-2xl md:text-4xl font-black text-gray-900">{{ $totalPrestasi ?? 0 }}</p>
                    <span class="text-[10px] md:text-xs font-bold text-gray-400">Data</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-50 flex flex-col justify-between tap-effect hover-scale group cursor-pointer">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 md:w-14 md:h-14 bg-pink-50 rounded-2xl flex items-center justify-center border border-pink-100 group-hover:bg-pink-100 transition-colors">
                    <span class="text-lg md:text-2xl">🎉</span>
                </div>
                <span class="bg-blue-50 text-blue-600 text-[9px] md:text-[10px] font-black px-2 py-1 rounded-lg">AKTIF</span>
            </div>
            <div>
                <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-0.5">Event Sekolah</p>
                <div class="flex items-baseline gap-1">
                    <p class="text-2xl md:text-4xl font-black text-gray-900">{{ $totalEvent ?? 0 }}</p>
                    <span class="text-[10px] md:text-xs font-bold text-gray-400">Acara</span>
                </div>
            </div>
        </div>

    </div>

    @if(session('success'))
        <div class="mt-6 bg-gray-900 text-white px-4 py-3 md:py-4 rounded-2xl text-xs md:text-sm font-bold shadow-lg flex items-center gap-3 animate-bounce">
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif
</div>

<div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 mt-2 mb-8">
    <div class="flex justify-between items-end px-4 sm:px-0 mb-4">
        <div>
            <h2 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">Prestasi Terbaru</h2>
            <p class="text-[11px] md:text-sm text-gray-500 font-medium mt-0.5">Hall of Fame Smecone.</p>
        </div>
    </div>

    <div class="flex gap-4 overflow-x-auto hide-scrollbar snap-x px-4 sm:px-0 pb-6 md:grid md:grid-cols-3 md:overflow-visible">
        @forelse($prestasis ?? [] as $prestasi)
            @empty
        <div class="min-w-[260px] md:min-w-0 bg-white rounded-[24px] shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-gray-100 p-5 shrink-0 snap-center relative overflow-hidden tap-effect hover-scale flex flex-col">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-full opacity-50 z-0"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-[9px] font-black uppercase px-2.5 py-1 rounded-md shadow-sm tracking-wider">Juara 1</span>
                <div class="flex gap-1.5">
                    <button class="w-7 h-7 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                    <button class="w-7 h-7 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </div>
            </div>
            
            <h3 class="font-extrabold text-gray-900 text-[15px] md:text-lg leading-snug relative z-10 mb-2 line-clamp-2">LKS Web Technologies Tingkat Provinsi 2025</h3>
            
            <div class="mt-auto relative z-10 pt-4 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-500">F</div>
                    <p class="text-[11px] md:text-xs font-bold text-gray-600">Fana Jalaludin</p>
                </div>
                <span class="text-[10px] font-bold text-gray-400">12 Nov</span>
            </div>
        </div>

        <div class="min-w-[260px] md:min-w-0 bg-white rounded-[24px] shadow-[0_4px_20px_rgb(0,0,0,0.04)] border border-gray-100 p-5 shrink-0 snap-center relative overflow-hidden tap-effect hover-scale flex flex-col">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-50 rounded-full opacity-50 z-0"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-gradient-to-r from-gray-400 to-gray-500 text-white text-[9px] font-black uppercase px-2.5 py-1 rounded-md shadow-sm tracking-wider">Juara 2</span>
            </div>
            <h3 class="font-extrabold text-gray-900 text-[15px] md:text-lg leading-snug relative z-10 mb-2 line-clamp-2">Turnamen Futsal Antar SMK Se-Banyumas</h3>
            <div class="mt-auto relative z-10 pt-4 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-500">T</div>
                    <p class="text-[11px] md:text-xs font-bold text-gray-600">Tim Futsal Inti</p>
                </div>
                <span class="text-[10px] font-bold text-gray-400">08 Okt</span>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
    <div class="flex justify-between items-end mb-4">
        <div>
            <h2 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">Agenda & Event</h2>
            <p class="text-[11px] md:text-sm text-gray-500 font-medium mt-0.5">Kelola acara sekolah.</p>
        </div>
    </div>

    <div class="flex flex-col gap-3 md:gap-4">
        @forelse($events ?? [] as $event)
            @empty
        <div class="bg-white rounded-[20px] shadow-[0_2px_15px_rgb(0,0,0,0.03)] border border-gray-100 p-3 md:p-4 flex flex-row items-center gap-3 md:gap-5 tap-effect group">
            
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[14px] bg-pink-50 text-pink-600 flex flex-col items-center justify-center shrink-0 border border-pink-100 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest leading-none">NOV</span>
                <span class="text-xl md:text-2xl font-black leading-none mt-0.5">15</span>
            </div>
            
            <div class="flex-1 min-w-0">
                <h3 class="text-[14px] md:text-base font-extrabold text-gray-900 leading-tight truncate">Smecone Photography Contest</h3>
                <div class="flex items-center gap-2 mt-1 md:mt-1.5">
                    <span class="bg-blue-50 text-blue-600 text-[8px] md:text-[9px] font-black uppercase px-2 py-0.5 rounded tracking-wider shrink-0">LOMBA</span>
                    <p class="text-[10px] md:text-xs text-gray-500 truncate"><span class="hidden md:inline">Lokasi: </span>Lapangan Basket</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-1.5 md:gap-2 shrink-0">
                <button class="w-8 h-8 md:w-auto md:px-4 md:py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 md:text-gray-700 md:text-xs font-bold rounded-xl flex items-center justify-center transition">
                    <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span class="hidden md:inline">Edit</span>
                </button>
                <button class="w-8 h-8 md:w-auto md:px-4 md:py-2 bg-red-50 hover:bg-red-100 text-red-500 md:text-red-600 md:text-xs font-bold rounded-xl flex items-center justify-center transition">
                    <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span class="hidden md:inline">Hapus</span>
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="fixed bottom-4 left-0 w-full z-50 flex justify-center px-4 pointer-events-none">
    <div class="bg-gray-900/80 backdrop-blur-xl border border-gray-700/50 p-2 rounded-[2rem] shadow-2xl flex items-center gap-1.5 md:gap-2 pointer-events-auto">
        
        <a href="/admin/dashboard" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white/10 text-white rounded-[1.5rem] transition tap-effect hover:bg-white/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-xs font-bold hidden md:block">Home</span>
        </a>

        <div class="w-px h-6 bg-gray-700 mx-1"></div>

        <a href="/admin/prestasi/create" class="flex items-center justify-center gap-2 px-4 py-2.5 text-gray-300 hover:bg-yellow-500/20 hover:text-yellow-400 rounded-[1.5rem] transition tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="text-xs font-bold whitespace-nowrap">Prestasi</span>
            <span class="text-base leading-none">🏆</span>
        </a>

        <a href="/admin/event/create" class="flex items-center justify-center gap-2 px-4 py-2.5 text-gray-300 hover:bg-pink-500/20 hover:text-pink-400 rounded-[1.5rem] transition tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="text-xs font-bold whitespace-nowrap">Event</span>
            <span class="text-base leading-none">🎉</span>
        </a>
        
    </div>
</div>
@endsection