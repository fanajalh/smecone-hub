@extends('layouts.app')
@section('title', '| Beranda')

@section('content')
<style>
    /* Menyembunyikan scrollbar tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- Premium Header Background with Gradient and Decorative Elements --}}
<div class="relative bg-gradient-to-br from-[#E21F26] via-[#D31920] to-[#B31217] w-full pt-10 pb-28 rounded-b-[2.5rem] md:rounded-b-[3.5rem] overflow-hidden shadow-lg shadow-red-900/20">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-32 h-32 bg-black/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-5 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-6 z-10">
        
        {{-- Profile Section --}}
        <div class="flex items-center gap-4 text-white">
            <div class="relative w-16 h-16 rounded-full border-[3px] border-white/40 shadow-xl overflow-hidden bg-white shrink-0 transition-transform hover:scale-105">
                @if($user && $user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=fff&color=E21F26&bold=true" alt="Avatar" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <p class="text-[13px] font-medium text-red-100 tracking-wide mb-0.5 opacity-90">Selamat Datang,</p>
                <h1 class="text-2xl font-extrabold flex items-center gap-2 drop-shadow-sm">
                    {{ explode(' ', $user->name ?? 'Warga')[0] }} 👋
                </h1>
            </div>
        </div>

        {{-- Points & Stats (Glassmorphism) --}}
        <div class="flex items-center gap-3 md:gap-4">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-3 text-white border border-white/20 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:bg-white/20 transition duration-300">
                <p class="text-[10px] text-red-100 uppercase tracking-widest font-semibold mb-1">Peran Kamu</p>
                <p class="text-sm font-black uppercase leading-tight mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                    @if(auth()->user()->is_admin) Admin @elseif(auth()->user()->is_teacher) Guru @else Siswa @endif
                </p>
            </div>
            <button onclick="document.getElementById('logout-form').submit();" class="bg-white/10 backdrop-blur-md rounded-2xl px-4 py-3 text-white border border-white/20 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:bg-red-800 transition duration-300 text-left outline-none cursor-pointer">
                <p class="text-[10px] text-red-100 uppercase tracking-widest font-semibold mb-1">Aksi Sistem</p>
                <p class="text-sm font-black uppercase leading-tight mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar / Logout
                </p>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                 @csrf
            </form>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="max-w-6xl mx-auto px-4 lg:px-8 -mt-16 relative z-20 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        {{-- Left Column --}}
        <div class="lg:col-span-8 flex flex-col gap-8">
            
            {{-- Navigation Menu (Modern Grid Cards) --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-5 md:p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white">
                <div class="grid grid-cols-4 gap-3 md:gap-5">
                    <a href="/marketplace" class="group flex flex-col items-center gap-3 p-3 rounded-2xl hover:bg-red-50 hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-gradient-to-br from-red-100 to-red-50 text-[#E21F26] flex items-center justify-center shadow-sm group-hover:shadow-md transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <span class="text-[12px] md:text-sm font-bold text-gray-700 group-hover:text-[#E21F26] transition-colors">Jajan</span>
                    </a>
                    
                    <a href="/forum" class="group flex flex-col items-center gap-3 p-3 rounded-2xl hover:bg-orange-50 hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-gradient-to-br from-orange-100 to-orange-50 text-orange-600 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        </div>
                        <span class="text-[12px] md:text-sm font-bold text-gray-700 group-hover:text-orange-600 transition-colors">Forum</span>
                    </a>
                    
                    <a href="/repository" class="group flex flex-col items-center gap-3 p-3 rounded-2xl hover:bg-indigo-50 hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <span class="text-[12px] md:text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Repo</span>
                    </a>
                    
                    <button onclick="showAllShortcuts()" class="group flex flex-col items-center gap-3 p-3 rounded-2xl hover:bg-gray-100 hover:scale-105 transition-all duration-300 outline-none">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-gradient-to-br from-gray-200 to-gray-100 text-gray-600 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </div>
                        <span class="text-[12px] md:text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">Lainnya</span>
                    </button>
                </div>
            </div>

            {{-- Horizontal Highlight Section --}}
            <div class="mt-2">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Update Sekolah</h2>
                </div>
                <div class="flex gap-4 overflow-x-auto hide-scrollbar snap-x pb-4">
                    @if($latestEvent)
                    <a href="/event" class="group min-w-[85%] md:min-w-[360px] h-44 rounded-[2rem] overflow-hidden relative snap-center shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        @if($latestEvent->gambar && count($latestEvent->gambar) > 0)
                            <img src="{{ asset('storage/' . $latestEvent->gambar[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent"></div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900"></div>
                        @endif
                        <div class="absolute bottom-5 left-5 right-5 text-white">
                            <span class="bg-[#E21F26] text-[10px] font-black px-2.5 py-1.5 rounded-lg uppercase tracking-widest mb-3 inline-block shadow-lg">Mendatang</span>
                            <h3 class="font-bold text-xl leading-snug line-clamp-2 text-white">{{ $latestEvent->judul }}</h3>
                        </div>
                    </a>
                    @endif

                    @if($latestPrestasi)
                    <a href="/prestasi" class="group min-w-[85%] md:min-w-[360px] h-44 rounded-[2rem] bg-gradient-to-br from-amber-400 to-amber-600 overflow-hidden relative snap-center shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-8 -mr-8 blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
                        <div class="absolute bottom-5 left-5 right-5 text-white z-10">
                            <span class="bg-white/30 backdrop-blur-md text-[10px] font-black px-2.5 py-1.5 rounded-lg uppercase tracking-widest mb-3 inline-block shadow-lg">Prestasi</span>
                            <h3 class="font-bold text-xl leading-snug line-clamp-2 text-white drop-shadow-md">{{ $latestPrestasi->judul }}</h3>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Marketplace Grid --}}
            <div>
                <div class="flex justify-between items-end mb-4 px-2">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Rekomendasi Jajan</h2>
                    </div>
                    <a href="/marketplace" class="text-sm font-bold text-[#E21F26] hover:text-red-700 transition flex items-center gap-1">
                        Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
                    @forelse($recentMarketplace as $item)
                    <a href="/marketplace/{{ $item->id }}" class="group bg-white rounded-3xl p-3 md:p-4 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 hover:shadow-xl hover:border-red-100 transition-all duration-300 flex flex-col transform hover:-translate-y-1">
                        <div class="aspect-square bg-gray-50 rounded-2xl mb-4 overflow-hidden relative">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 group-hover:bg-gray-100 transition-colors">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-bold py-1.5 px-3 rounded-full shadow-lg">Lihat</span>
                            </div>
                        </div>
                        <h3 class="text-[14px] md:text-base font-bold text-gray-800 line-clamp-1 group-hover:text-[#E21F26] transition-colors">{{ $item->item_name }}</h3>
                        <div class="flex items-center justify-between mt-1.5 mb-2">
                            <p class="text-[#E21F26] font-black text-sm md:text-base">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600">
                                {{ substr($item->user->name, 0, 1) }}
                            </div>
                            <p class="text-[11px] md:text-xs text-gray-500 font-medium truncate">{{ explode(' ', $item->user->name)[0] }}</p>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full bg-white rounded-3xl p-10 text-center border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">Belum ada jajan hari ini.</p>
                        <p class="text-xs text-gray-400 mt-1">Coba cek lagi nanti ya!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-4 flex flex-col gap-6 lg:gap-8 mt-4 lg:mt-0">
            
            {{-- Channel Saya --}}
            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-extrabold text-gray-900">Channel Saya</h2>
                    <a href="/dashboard/channel/create" class="bg-red-50 text-[#E21F26] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#E21F26] hover:text-white transition-colors">+ Baru</a>
                </div>
                <div class="flex flex-col gap-2">
                    @forelse($myChannels as $channel)
                    <div class="group flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-[1rem] bg-red-50 text-[#E21F26] flex items-center justify-center font-black text-lg group-hover:bg-[#E21F26] group-hover:text-white transition-colors">
                                {{ substr($channel->title, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 line-clamp-1">{{ $channel->title }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    {{ $channel->replies_count }} Pesan
                                </p>
                            </div>
                        </div>
                        <a href="/dashboard/channel/{{ $channel->id }}/manage" class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 group-hover:text-[#E21F26] group-hover:border-red-100 group-hover:shadow-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">Belum ada channel.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Repo Populer --}}
            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-extrabold text-gray-900">Repo Populer</h2>
                    <a href="/repository" class="text-sm font-bold text-[#E21F26] hover:text-red-700 hover:underline">Semua</a>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($popularRepos as $repo)
                    <a href="/repository/{{ $repo->id }}" class="group block p-4 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all duration-300">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-bold text-gray-800 line-clamp-1 group-hover:text-indigo-600 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                {{ $repo->name }}
                            </h3>
                            <div class="flex items-center gap-1.5 bg-yellow-50 px-2 py-0.5 rounded-md text-amber-600 text-xs font-black">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                {{ $repo->stars_count }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                            <div class="w-4 h-4 rounded-full bg-gray-200 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($repo->user->name) }}&background=e2e8f0&color=475569" class="w-full h-full object-cover">
                            </div>
                            {{ $repo->user->name }}
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6 border border-dashed border-gray-200 rounded-2xl">
                        <p class="text-sm font-semibold text-gray-500">Belum ada repo.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showAllShortcuts() {
        Swal.fire({
            html: `
                <div class="text-left mb-4 mt-2 border-b border-gray-100 pb-3">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-tight">Navigasi Penuh</h2>
                    <p class="text-xs font-semibold text-gray-500 mt-0.5">Semua fitur Smecone Hub & Riwayat Aktivitasmu</p>
                </div>
                
                <h3 class="text-left text-[11px] font-black tracking-widest text-[#E21F26] uppercase mb-3">Menu Utama</h3>
                <div class="grid grid-cols-4 gap-2 mb-5">
                    <a href="/prestasi" class="group flex flex-col items-center gap-2 p-2 hover:bg-red-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-50 rounded-xl flex items-center justify-center text-[#E21F26] group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight">Prestasi</span>
                    </a>
                    <a href="/event" class="group flex flex-col items-center gap-2 p-2 hover:bg-orange-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-50 rounded-xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight">Event</span>
                    </a>
                    <a href="/profile" class="group flex flex-col items-center gap-2 p-2 hover:bg-blue-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight">Profil</span>
                    </a>
                    <a href="/keranjang" class="group flex flex-col items-center gap-2 p-2 hover:bg-emerald-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight">Keranjang</span>
                    </a>
                </div>

                <h3 class="text-left text-[11px] font-black tracking-widest text-[#E21F26] uppercase mb-3">Aktivitas & Riwayat</h3>
                <div class="grid grid-cols-4 gap-2">
                    <a href="/marketplace/purchases" class="group flex flex-col items-center gap-2 p-2 hover:bg-emerald-50 rounded-2xl transition-all text-center">
                        <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 leading-tight group-hover:text-emerald-700">Terbeli</span>
                    </a>
                    <a href="/marketplace/penjualan" class="group flex flex-col items-center gap-2 p-2 hover:bg-red-50 rounded-2xl transition-all text-center">
                        <div class="w-12 h-12 bg-red-50 border border-red-100 rounded-xl flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 leading-tight group-hover:text-red-700">Terjual</span>
                    </a>
                    <a href="/marketplace/lapak-saya" class="group flex flex-col items-center gap-2 p-2 hover:bg-orange-50 rounded-2xl transition-all text-center">
                        <div class="w-12 h-12 bg-orange-50 border border-orange-100 rounded-xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 leading-tight group-hover:text-orange-700">Lapakku</span>
                    </a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-[2rem] p-4 shadow-2xl border border-gray-100',
                closeButton: 'focus:outline-none hover:text-red-500 transition text-gray-400 mt-2 mr-2'
            },
            width: '90%',
            maxWidth: '420px'
        });
    }
</script>
@endsection