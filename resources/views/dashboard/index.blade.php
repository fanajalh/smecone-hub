@extends('layouts.app')
@section('title', '| Beranda')

@section('content')
<style>
    /* Hanya menyimpan class untuk menyembunyikan scrollbar pada area horizontal */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- Simple Flat Header Background --}}
<div class="bg-[#E21F26] w-full pt-8 pb-24 rounded-b-[2rem] md:rounded-b-[3rem]">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row md:items-center justify-between gap-6">
        
        {{-- Profile --}}
        <div class="flex items-center gap-4 text-white">
            <div class="w-14 h-14 rounded-full border-2 border-white/30 overflow-hidden bg-white shrink-0">
                @if($user && $user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=fff&color=E21F26" alt="Avatar" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <p class="text-xs font-medium text-white/80 tracking-wider">Selamat Datang,</p>
                <h1 class="text-xl md:text-2xl font-bold mt-0.5">{{ explode(' ', $user->name ?? 'Warga')[0] }}</h1>
            </div>
        </div>

        {{-- Points & Stats --}}
        <div class="flex items-center gap-3 md:gap-4">
            <div class="bg-white/10 rounded-xl px-4 py-2 text-white border border-white/20">
                <p class="text-[10px] text-white/70 uppercase tracking-wider mb-0.5">Poin Kamu</p>
                <p class="text-lg font-bold leading-none">{{ number_format($user->reputation_points ?? 0) }} <span class="text-[10px] font-normal">PTS</span></p>
            </div>
            <div class="bg-white/10 rounded-xl px-4 py-2 text-white border border-white/20">
                <p class="text-[10px] text-white/70 uppercase tracking-wider mb-0.5">Level</p>
                <p class="text-sm font-bold uppercase leading-tight mt-1">{{ $level ?? 'Newcomer' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="max-w-6xl mx-auto px-4 -mt-12 relative z-10 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        {{-- Left Column --}}
        <div class="lg:col-span-8 flex flex-col gap-8">
            
            {{-- Navigation Menu --}}
            <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100">
                <div class="grid grid-cols-4 gap-2 md:gap-4">
                    <a href="/marketplace" class="flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-gray-50 transition">
                        <div class="w-12 h-12 rounded-full bg-red-50 text-[#E21F26] flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <span class="text-[11px] md:text-xs font-semibold text-gray-700">Jajan</span>
                    </a>
                    <a href="/forum" class="flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-gray-50 transition">
                        <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        </div>
                        <span class="text-[11px] md:text-xs font-semibold text-gray-700">Forum</span>
                    </a>
                    <a href="/repository" class="flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-gray-50 transition">
                        <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <span class="text-[11px] md:text-xs font-semibold text-gray-700">Repo</span>
                    </a>
                    <button onclick="showAllShortcuts()" class="flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-gray-50 transition outline-none">
                        <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </div>
                        <span class="text-[11px] md:text-xs font-semibold text-gray-700">Lainnya</span>
                    </button>
                </div>
            </div>

            {{-- Horizontal Highlight Section --}}
            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-3 px-1">Update Sekolah</h2>
                <div class="flex gap-4 overflow-x-auto hide-scrollbar snap-x pb-2">
                    @if($latestEvent)
                    <a href="/event" class="min-w-[85%] md:min-w-[350px] h-40 rounded-2xl overflow-hidden relative snap-center shadow-sm border border-gray-100">
                        @if($latestEvent->gambar && count($latestEvent->gambar) > 0)
                            <img src="{{ asset('storage/' . $latestEvent->gambar[0]) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        @else
                            <div class="w-full h-full bg-slate-800"></div>
                        @endif
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <span class="bg-[#E21F26] text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider mb-2 inline-block">Mendatang</span>
                            <h3 class="font-bold text-lg leading-tight line-clamp-2">{{ $latestEvent->judul }}</h3>
                        </div>
                    </a>
                    @endif

                    @if($latestPrestasi)
                    <a href="/prestasi" class="min-w-[85%] md:min-w-[350px] h-40 rounded-2xl bg-amber-500 overflow-hidden relative snap-center shadow-sm border border-amber-600/20">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <span class="bg-white/20 backdrop-blur-sm text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider mb-2 inline-block">Prestasi</span>
                            <h3 class="font-bold text-lg leading-tight line-clamp-2">{{ $latestPrestasi->judul }}</h3>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Marketplace Grid --}}
            <div>
                <div class="flex justify-between items-end mb-3 px-1">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Rekomendasi Jajan</h2>
                    </div>
                    <a href="/marketplace" class="text-sm font-semibold text-[#E21F26] hover:underline">Semua</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($recentMarketplace as $item)
                    <a href="/marketplace/{{ $item->id }}" class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col">
                        <div class="aspect-square bg-gray-100 rounded-xl mb-3 overflow-hidden relative">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $item->item_name }}</h3>
                        <p class="text-[#E21F26] font-bold text-sm mt-1">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        <p class="text-[11px] text-gray-500 mt-2 truncate">{{ explode(' ', $item->user->name)[0] }}</p>
                    </a>
                    @empty
                    <div class="col-span-full bg-white rounded-2xl p-8 text-center border border-gray-100">
                        <p class="text-sm text-gray-500">Belum ada jajan hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            {{-- Channel Saya --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Channel Saya</h2>
                    <a href="/dashboard/channel/create" class="text-sm font-semibold text-[#E21F26] hover:underline">+ Baru</a>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($myChannels as $channel)
                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-50 text-[#E21F26] flex items-center justify-center font-bold">
                                {{ substr($channel->title, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">{{ $channel->title }}</h3>
                                <p class="text-xs text-gray-500">{{ $channel->replies_count }} Pesan</p>
                            </div>
                        </div>
                        <a href="/dashboard/channel/{{ $channel->id }}/manage" class="text-gray-400 hover:text-[#E21F26] p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    @empty
                    <p class="text-center text-sm text-gray-500 py-4">Belum ada channel.</p>
                    @endforelse
                </div>
            </div>

            {{-- Repo Populer --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Repo Populer</h2>
                    <a href="/repository" class="text-sm font-semibold text-[#E21F26] hover:underline">Semua</a>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($popularRepos as $repo)
                    <a href="/repository/{{ $repo->id }}" class="block p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $repo->name }}</h3>
                            <div class="flex items-center gap-1 text-amber-500 text-xs font-bold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                {{ $repo->stars_count }}
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">{{ $repo->user->name }}</p>
                    </a>
                    @empty
                    <p class="text-center text-sm text-gray-500 py-4">Belum ada repo.</p>
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
                <div class="text-left mb-5">
                    <h2 class="text-lg font-bold text-gray-900">Menu Utama</h2>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <a href="/prestasi" class="flex flex-col items-center gap-2 p-2 hover:bg-gray-50 rounded-xl transition">
                        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#E21F26]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700">Prestasi</span>
                    </a>
                    <a href="/lost-found" class="flex flex-col items-center gap-2 p-2 hover:bg-gray-50 rounded-xl transition">
                        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#E21F26]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700">Barang</span>
                    </a>
                    <a href="/profile" class="flex flex-col items-center gap-2 p-2 hover:bg-gray-50 rounded-xl transition">
                        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#E21F26]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700">Profil</span>
                    </a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-2xl p-6 shadow-xl border border-gray-100',
            },
            width: '90%',
            maxWidth: '400px'
        });
    }
</script>
@endsection