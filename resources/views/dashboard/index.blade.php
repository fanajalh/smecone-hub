@extends('layouts.app')
@section('title', '| Home')

@section('content')
<style>
    /* Menyembunyikan scrollbar di mobile tapi tetap mulus */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Efek klik ala aplikasi mobile */
    .active-scale:active { transform: scale(0.96); transition: transform 0.1s; }
    
    /* Hover scale untuk desktop */
    @media (hover: hover) {
        .hover-scale:hover { transform: translateY(-4px); transition: transform 0.3s ease; }
    }
</style>

<div class="bg-gradient-to-b from-red-600 to-red-700 w-full h-[220px] md:h-[280px] absolute top-0 left-0 z-0 rounded-b-[40px] md:rounded-b-[60px] shadow-sm transition-all duration-300">
    <div class="absolute top-[-20%] right-[-10%] w-64 h-64 bg-white opacity-10 rounded-full blur-2xl"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-40 h-40 md:w-80 md:h-80 bg-red-400 opacity-20 rounded-full blur-xl"></div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 pt-6 md:pt-10 relative z-10">
    
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 text-white">
        <div class="flex justify-between items-center w-full md:w-auto">
            <div class="flex items-center gap-3 md:gap-4">
                <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-white/20 border-2 border-white/50 flex items-center justify-center overflow-hidden shadow-sm">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="text-[11px] md:text-sm font-medium opacity-90 leading-tight">Selamat datang di Smecone,</p>
                    <h1 class="text-base md:text-2xl font-extrabold leading-tight">{{ explode(' ', $user->name)[0] }}! 👋</h1>
                </div>
            </div>
            <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 active-scale backdrop-blur-sm md:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </a>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-1/2 lg:w-1/3">
            <div class="relative w-full">
                <input type="text" placeholder="Cari jajan, tugas, atau info..." class="w-full bg-white/10 border border-white/20 text-white placeholder-white/70 text-sm rounded-full py-2.5 md:py-3 pl-10 pr-4 focus:outline-none focus:bg-white/20 backdrop-blur-md transition shadow-inner">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <a href="#" class="hidden md:flex shrink-0 w-11 h-11 rounded-full bg-white/10 items-center justify-center hover:bg-white/20 transition-all backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[24px] p-4 sm:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.06)] flex justify-between items-center border border-gray-100 relative mt-2 md:mt-6 active-scale md:active:scale-100 hover-scale">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-red-50 flex items-center justify-center border border-red-100">
                <svg class="w-7 h-7 md:w-8 md:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] md:text-xs text-gray-500 font-bold mb-0.5 md:mb-1">Smecone Poin</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl md:text-3xl font-black text-gray-800">{{ $user->reputation_points ?? 0 }}</span>
                    <span class="text-xs md:text-sm font-bold text-gray-400">Pts</span>
                </div>
            </div>
        </div>
        
        <div class="w-px h-10 md:h-12 bg-gray-100 mx-2 md:mx-6"></div>

        <div class="flex items-center gap-3 text-right">
            <div>
                <p class="text-[11px] md:text-xs text-gray-500 font-bold mb-0.5 md:mb-1">Level Akun</p>
                <span class="text-sm md:text-lg font-extrabold text-red-600">{{ $level ?? 'Rookie' }}</span>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-tr from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white shadow-md">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-4 lg:grid-cols-8 gap-y-6 md:gap-y-8 gap-x-2 md:gap-x-4 mt-8 md:mt-10 px-2 md:px-0">
        <a href="/marketplace" class="flex flex-col items-center gap-2 group active-scale md:hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-orange-100 flex items-center justify-center text-orange-600 shadow-sm group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-bold text-gray-700 text-center leading-tight">Jajan<br>& Jasa</span>
        </a>

        <a href="/lost-found" class="flex flex-col items-center gap-2 group active-scale md:hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-bold text-gray-700 text-center leading-tight">Barang<br>Hilang</span>
        </a>

        <a href="/forum" class="flex flex-col items-center gap-2 group active-scale md:hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-green-100 flex items-center justify-center text-green-600 shadow-sm group-hover:bg-green-600 group-hover:text-white transition-colors">
                <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-bold text-gray-700 text-center leading-tight">Forum<br>Diskusi</span>
        </a>

        <a href="/repository" class="flex flex-col items-center gap-2 group active-scale md:hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-purple-100 flex items-center justify-center text-purple-600 shadow-sm group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-bold text-gray-700 text-center leading-tight">Tugas<br>Akhir</span>
        </a>
        
        <div class="flex flex-col items-center gap-2 opacity-60">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-yellow-50 flex items-center justify-center text-yellow-600 border border-yellow-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-medium text-gray-400 text-center">Prestasi</span>
        </div>

        <div class="flex flex-col items-center gap-2 opacity-60">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-teal-50 flex items-center justify-center text-teal-600 border border-teal-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-medium text-gray-400 text-center">Kantin</span>
        </div>

        <div class="flex flex-col items-center gap-2 opacity-60">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-pink-50 flex items-center justify-center text-pink-600 border border-pink-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-medium text-gray-400 text-center">Event</span>
        </div>

        <a href="#" class="flex flex-col items-center gap-2 group active-scale md:hover:-translate-y-1 transition-transform">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-[18px] md:rounded-[22px] bg-gray-100 flex items-center justify-center text-gray-600 shadow-sm group-hover:bg-gray-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </div>
            <span class="text-[11px] md:text-xs font-bold text-gray-700 text-center">Lainnya</span>
        </a>
    </div>

    <div class="mt-8 flex gap-4 overflow-x-auto hide-scrollbar snap-x snap-mandatory px-1 pb-4 md:grid md:grid-cols-2 md:overflow-visible">
        <div class="min-w-[85%] md:min-w-0 h-36 md:h-44 bg-gradient-to-br from-red-600 to-red-800 rounded-3xl snap-center shrink-0 p-5 md:p-8 flex flex-col justify-center text-white relative overflow-hidden shadow-[0_4px_15px_rgba(220,38,38,0.2)] hover-scale cursor-pointer">
            <div class="absolute -right-4 -bottom-4 w-32 h-32 md:w-48 md:h-48 bg-white opacity-10 rounded-full blur-xl"></div>
            <span class="bg-white/20 backdrop-blur-md border border-white/30 text-[10px] md:text-xs font-extrabold px-2.5 py-1 rounded-full w-fit mb-2 md:mb-3 uppercase tracking-wide">Info OSIS</span>
            <h3 class="font-extrabold text-xl md:text-2xl leading-tight w-3/4">Pendaftaran Ekskul<br>Telah Dibuka!</h3>
        </div>
        <div class="min-w-[85%] md:min-w-0 h-36 md:h-44 bg-gradient-to-br from-blue-600 to-indigo-800 rounded-3xl snap-center shrink-0 p-5 md:p-8 flex flex-col justify-center text-white relative overflow-hidden shadow-[0_4px_15px_rgba(37,99,235,0.2)] hover-scale cursor-pointer">
            <div class="absolute -right-4 -top-4 w-24 h-24 md:w-40 md:h-40 bg-white opacity-10 rounded-full blur-lg"></div>
            <span class="bg-white/20 backdrop-blur-md border border-white/30 text-[10px] md:text-xs font-extrabold px-2.5 py-1 rounded-full w-fit mb-2 md:mb-3 uppercase tracking-wide">Kompetisi</span>
            <h3 class="font-extrabold text-xl md:text-2xl leading-tight w-3/4">Lomba Koding<br>Berhadiah Menarik!</h3>
        </div>
    </div>

    <div class="mt-6 md:mt-10">
        <div class="flex justify-between items-center mb-4 md:mb-6 px-1">
            <h2 class="text-base md:text-xl font-extrabold text-gray-800 tracking-tight">Rekomendasi Jajan 🔥</h2>
            <a href="/marketplace" class="text-[11px] md:text-sm font-extrabold text-red-600 hover:text-red-800 bg-red-50 px-3 py-1.5 md:px-4 md:py-2 rounded-full transition active-scale">Lihat Semua</a>
        </div>
        
        <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-6 snap-x px-1 md:grid md:grid-cols-4 lg:grid-cols-5 md:overflow-visible md:pb-2">
            @forelse($recentMarketplace as $item)
            <a href="/marketplace/{{ $item->id }}" class="min-w-[140px] md:min-w-0 bg-white rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-100 flex flex-col overflow-hidden shrink-0 snap-start hover-scale transition-all tap-effect {{ $item->is_sold ? 'opacity-75 grayscale-[40%]' : '' }}">
                <div class="h-32 md:h-40 bg-gray-100 relative">
                    
                    @if($item->is_sold)
                    <div class="absolute inset-0 bg-white/40 backdrop-blur-[1px] z-20 flex items-center justify-center"></div>
                    @endif

                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50 italic font-black text-xl uppercase">Smecone</div>
                    @endif
                    
                    <div class="absolute top-2 left-2 z-30 flex flex-col gap-1.5">
                        @if($item->is_promoted)
                            <span class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-[8px] md:text-[9px] font-black px-2 py-0.5 md:px-2 md:py-1 rounded shadow-sm border border-yellow-300 uppercase tracking-wider w-fit">
                                ⭐ AD
                            </span>
                        @endif

                        @if($item->is_sold)
                            <span class="bg-gray-900 text-white text-[8px] md:text-[9px] font-black px-2 py-0.5 md:px-2 md:py-1 rounded shadow-sm uppercase tracking-wider w-fit">
                                HABIS
                            </span>
                        @else
                            <span class="bg-green-500 text-white text-[8px] md:text-[9px] font-black px-2 py-0.5 md:px-2 md:py-1 rounded shadow-sm uppercase tracking-wider w-fit">
                                BARU
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-[13px] md:text-sm font-bold text-gray-800 leading-snug line-clamp-2 mb-1">{{ $item->item_name }}</h3>
                        <p class="text-sm md:text-base font-black text-red-600 mb-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-auto">
                        <div class="flex items-center gap-1.5 truncate max-w-[70%]">
                            <div class="w-4 h-4 md:w-5 md:h-5 bg-gray-200 rounded-full flex items-center justify-center text-[7px] md:text-[9px] font-bold text-gray-600 uppercase shrink-0">{{ substr($item->user->name, 0, 1) }}</div>
                            <p class="text-[10px] md:text-xs font-medium text-gray-500 truncate">{{ explode(' ', $item->user->name)[0] }}</p>
                        </div>
                        <div class="flex items-center gap-0.5 shrink-0 text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-[9px] font-bold">{{ $item->views_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="w-full md:col-span-4 lg:col-span-5 bg-gray-50 p-8 rounded-2xl border border-dashed border-gray-200 text-center flex flex-col items-center justify-center gap-2">
                <p class="text-xs md:text-sm text-gray-500 font-medium">Belum ada jualan hari ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="md:grid md:grid-cols-2 md:gap-8 mt-4 md:mt-8">
        
        <div class="bg-white rounded-3xl p-5 md:p-6 shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-gray-100 h-fit">
            <div class="flex justify-between items-center mb-4 md:mb-6">
                <div>
                    <h2 class="text-base md:text-lg font-extrabold text-gray-800 tracking-tight">Channel Obrolan</h2>
                    <p class="text-[11px] md:text-xs text-gray-500 mt-0.5">Kelola grup yang kamu buat.</p>
                </div>
                <a href="/dashboard/channel/create" class="text-[11px] md:text-xs font-extrabold text-red-600 bg-red-50 border border-red-100 px-3 py-1.5 md:px-4 md:py-2 rounded-full transition active-scale">+ Buat</a>
            </div>
            
            <div class="flex flex-col gap-3 max-h-[200px] md:max-h-[300px] overflow-y-auto pr-1">
                @forelse($myChannels as $myChannel)
                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 p-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-black shrink-0">#</div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-[13px] md:text-sm truncate w-32 md:w-48">{{ $myChannel->title }}</h3>
                                <p class="text-[10px] md:text-xs font-medium text-gray-500">{{ $myChannel->replies_count ?? 0 }} Pesan</p>
                            </div>
                        </div>
                        <a href="/dashboard/channel/{{ $myChannel->id }}/manage" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 shadow-sm hover:bg-gray-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="w-full text-center py-4">
                    <p class="text-xs text-gray-400 font-medium">Kamu belum membuat channel.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="mt-8 md:mt-0">
            <div class="flex justify-between items-center mb-4 md:mb-6 px-1 md:px-0">
                <h2 class="text-base md:text-lg font-extrabold text-gray-800 tracking-tight">Info Barang Hilang</h2>
                <a href="/lost-found/create" class="text-[11px] md:text-xs font-extrabold text-white bg-red-500 px-3 py-1.5 md:px-4 md:py-2 rounded-full shadow-sm hover:bg-red-600 transition active-scale">
                    Lapor
                </a>
            </div>
            
            <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-4 snap-x px-1 md:px-0 md:flex-col md:overflow-visible">
                @forelse($recentLostFounds as $item)
                <div class="min-w-[260px] md:min-w-0 bg-white rounded-[20px] shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-gray-100 p-3 shrink-0 snap-start flex items-center gap-3 relative overflow-hidden hover-scale">
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $item->type == 'lost' ? 'bg-red-500' : 'bg-green-500' }}"></div>
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl flex items-center justify-center font-black text-white shrink-0 shadow-inner {{ $item->type == 'lost' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($item->type == 'lost')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1 py-1">
                        <span class="text-[9px] md:text-[10px] font-extrabold uppercase tracking-wider {{ $item->type == 'lost' ? 'text-red-500' : 'text-green-600' }}">
                            {{ $item->type == 'lost' ? 'HILANG' : 'DITEMUKAN' }}
                        </span>
                        <h3 class="text-[13px] md:text-sm font-bold text-gray-800 truncate mt-0.5">{{ $item->item_name }}</h3>
                        <p class="text-[10px] md:text-xs text-gray-500 mt-1 truncate">Oleh: <span class="font-medium">{{ $item->user->name }}</span></p>
                    </div>
                </div>
                @empty
                <div class="w-full bg-white p-6 rounded-2xl border border-gray-100 text-center shadow-sm">
                    <p class="text-xs text-gray-400 font-medium">Aman! Tidak ada laporan barang hilang.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 md:mt-12 mb-10">
        <h2 class="text-base md:text-xl font-extrabold text-gray-800 tracking-tight mb-4 md:mb-6 px-1">Top Projects Smecone 🏆</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
            @foreach($popularRepos as $repo)
            <a href="/repository/{{ $repo->id }}" class="bg-white border border-gray-100 p-4 md:p-5 rounded-2xl md:rounded-[2rem] shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover-scale transition-all flex items-center gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-bold text-gray-800 text-[14px] md:text-base truncate">{{ $repo->name }}</h4>
                    <p class="text-[11px] md:text-xs text-gray-500 mt-0.5">{{ $repo->major ?? 'Umum' }}</p>
                </div>
                <div class="flex items-center gap-1 bg-yellow-50 px-2.5 py-1.5 rounded-lg border border-yellow-100">
                    <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span class="text-[11px] md:text-xs font-bold text-yellow-700">{{ $repo->stars_count }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection