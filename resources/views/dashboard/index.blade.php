@extends('layouts.app')
@section('title', '| Home')

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="bg-red-600 w-full h-48 absolute top-0 left-0 z-0 rounded-b-[40px] shadow-sm hidden md:block"></div>
<div class="bg-red-600 w-full h-40 absolute top-0 left-0 z-0 rounded-b-[30px] shadow-sm md:hidden"></div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 pt-4 relative z-10">
    
    <div class="flex justify-between items-center mb-4 md:hidden text-white">
        <div>
            <p class="text-xs font-medium opacity-90">Selamat datang,</p>
            <h1 class="text-lg font-bold">{{ explode(' ', $user->name)[0] }}! 👋</h1>
        </div>
        <a href="#" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </a>
    </div>

    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex justify-between items-center border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Smecone Poin</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-gray-800">{{ $user->reputation_points ?? 0 }}</span>
                    <span class="text-sm font-bold text-red-600 pt-1">Pts</span>
                </div>
            </div>
        </div>
        <div class="text-right border-l border-gray-100 pl-4">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Level</p>
            <span class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-extrabold">{{ $level }}</span>
        </div>
    </div>

    <div class="grid grid-cols-4 md:grid-cols-8 gap-y-6 gap-x-2 mt-8 px-2">
        <a href="/marketplace" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-orange-100 flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Jajan</span>
        </a>

        <a href="/lost-found" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Kehilangan</span>
        </a>

        <a href="/forum" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Forum</span>
        </a>

        <a href="/repository" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-purple-100 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Tugas</span>
        </a>

        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-yellow-100 flex items-center justify-center text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Peringkat</span>
        </a>

        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-teal-100 flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Kantin</span>
        </a>

        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-pink-100 flex items-center justify-center text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Jadwal</span>
        </a>

        <a href="#" class="flex flex-col items-center gap-2 group">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gray-200 flex items-center justify-center text-gray-600 group-hover:bg-gray-600 group-hover:text-white transition-all shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012-2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <span class="text-[11px] font-bold text-gray-700 text-center">Lainnya</span>
        </a>
    </div>

    <div class="mt-8 flex gap-4 overflow-x-auto hide-scrollbar snap-x snap-mandatory">
        <div class="min-w-[85%] md:min-w-[400px] h-32 md:h-40 bg-gradient-to-r from-red-600 to-red-800 rounded-3xl snap-center shrink-0 p-5 flex flex-col justify-center text-white relative overflow-hidden shadow-md">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-20 rounded-full"></div>
            <span class="bg-red-500/50 text-xs font-black px-2 py-1 rounded w-fit mb-2">INFO OSIS</span>
            <h3 class="font-extrabold text-lg md:text-xl leading-tight">Pendaftaran Ekskul<br>Telah Dibuka!</h3>
        </div>
        <div class="min-w-[85%] md:min-w-[400px] h-32 md:h-40 bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl snap-center shrink-0 p-5 flex flex-col justify-center text-white relative overflow-hidden shadow-md">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-20 rounded-full"></div>
            <span class="bg-blue-500/50 text-xs font-black px-2 py-1 rounded w-fit mb-2">EVENT</span>
            <h3 class="font-extrabold text-lg md:text-xl leading-tight">Lomba Koding Smecone<br>Berhadiah Menarik!</h3>
        </div>
    </div>

    <div class="mt-10 bg-red-50/50 border border-red-100 rounded-3xl p-5">
        <div class="flex justify-between items-end mb-4">
            <div>
                <h2 class="text-lg font-extrabold text-red-800">Channel Obrolan Saya</h2>
                <p class="text-xs text-red-600 font-medium mt-1">Kelola grup yang kamu buat.</p>
            </div>
            <a href="/dashboard/channel/create" class="text-xs font-bold text-red-600 hover:text-red-800 bg-white px-3 py-1.5 rounded-full shadow-sm border border-red-100">+ Buat Baru</a>
        </div>
        
        <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-2 snap-x">
            @forelse($myChannels as $myChannel)
            <div class="min-w-[240px] md:min-w-[300px] bg-white rounded-2xl shadow-sm border border-red-100 p-4 shrink-0 snap-start">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center font-black text-red-600 text-xl shrink-0">#</div>
                        <div>
                            <h3 class="font-extrabold text-gray-800 truncate w-32 md:w-40">{{ $myChannel->title }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 mt-0.5">{{ $myChannel->replies_count }} Pesan</p>
                        </div>
                    </div>
                    <a href="/dashboard/channel/{{ $myChannel->id }}/manage" class="bg-red-600 text-white p-2 rounded-xl hover:bg-red-700 transition shadow-sm" title="Kelola Channel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="w-full bg-white p-4 rounded-2xl border border-red-100 text-center">
                <p class="text-sm text-gray-500 font-medium">Kamu belum membuat channel apa pun.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        <div class="flex justify-between items-end mb-4 px-1">
            <h2 class="text-lg font-extrabold text-gray-800">Rekomendasi Jajan & Jasa</h2>
            <a href="/marketplace" class="text-xs font-bold text-red-600 hover:text-red-800 bg-white px-3 py-1.5 rounded-full shadow-sm border border-red-100">Lihat Semua</a>
        </div>
        
        <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-4 snap-x px-1">
            @forelse($recentMarketplace as $item)
            <a href="/marketplace" class="min-w-[140px] md:min-w-[180px] bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden shrink-0 snap-start hover:shadow-md transition">
                <div class="h-28 md:h-32 bg-gray-100 relative">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="text-xs md:text-sm font-bold text-gray-800 truncate">{{ $item->title }}</h3>
                    <p class="text-sm md:text-base font-black text-red-600 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
            </a>
            @empty
            <div class="w-full bg-white p-4 rounded-2xl border border-gray-100 text-center">
                <p class="text-sm text-gray-500 font-medium">Belum ada jualan baru.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8">
        <div class="flex justify-between items-end mb-4 px-1">
            <div>
                <h2 class="text-lg font-extrabold text-gray-800">Info Barang Hilang</h2>
                <p class="text-xs text-gray-500 mt-0.5">Bantu teman, dapatkan Poin!</p>
            </div>
            
            <div class="flex gap-2">
                <a href="/lost-found/create" class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm hover:bg-red-100 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    Lapor
                </a>
                <a href="/lost-found" class="bg-white text-gray-600 border border-gray-200 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm hover:bg-gray-50 transition">Semua</a>
            </div>
        </div>
        
        <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-4 snap-x px-1">
            @forelse($recentLostFounds as $item)
            <div class="min-w-[220px] md:min-w-[280px] bg-white rounded-2xl shadow-sm border border-gray-100 p-4 shrink-0 snap-start flex items-center gap-4 hover:border-red-100 transition cursor-pointer">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-white shrink-0 shadow-inner {{ $item->type == 'lost' ? 'bg-red-500' : 'bg-green-500' }}">
                    {{ $item->type == 'lost' ? '!' : '✓' }}
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider {{ $item->type == 'lost' ? 'text-red-500' : 'text-green-500' }}">
                        {{ $item->type == 'lost' ? 'HILANG' : 'DITEMUKAN' }}
                    </span>
                    <h3 class="text-sm font-bold text-gray-800 leading-tight mt-0.5 truncate w-32 md:w-48">{{ $item->item_name }}</h3>
                    <p class="text-xs text-gray-500 mt-1 truncate w-32 md:w-48">Oleh: {{ $item->user->name }}</p>
                </div>
            </div>
            @empty
            <div class="w-full bg-white p-4 rounded-2xl border border-gray-100 text-center">
                <p class="text-sm text-gray-500 font-medium">Aman! Tidak ada info barang hilang.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection