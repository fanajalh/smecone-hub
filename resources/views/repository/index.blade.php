@extends('layouts.app')
@section('title', '| Repositori')

@section('content')
<style>
    /* Menyembunyikan scrollbar tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Efek klik (Tap effect khas aplikasi mobile) */
    .tap-effect:active { transform: scale(0.96); transition: transform 0.1s ease-in-out; }
    
    /* Animasi masuk halaman */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-page-in { animation: fadeInUp 0.4s ease-out forwards; }
    .animate-card-in { opacity: 0; animation: fadeInUp 0.4s ease-out forwards; }
</style>

<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-28 md:pb-16 animate-page-in font-sans">
    
    {{-- HEADER HERO SECTION (Gojek Style Light Theme) --}}
    <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-[0_4px_20px_rgba(0,0,0,0.04)] border border-gray-100 mb-8 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        {{-- Background Ornament Ringkas --}}
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-red-50 rounded-full blur-3xl pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10 w-full md:w-auto">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight leading-snug mb-2">
                Smecone <span class="text-[#EE2737]">Repositori</span>
            </h1>
            <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-md leading-relaxed">
                Tempat menyimpan, membagikan, dan mengeksplorasi proyek digital inovatif dari seluruh siswa Smecone.
            </p>
        </div>
        
        <a href="/repository/create" class="w-full md:w-auto bg-[#EE2737] text-white px-7 py-3.5 rounded-full shadow-[0_4px_15px_rgba(238,39,55,0.25)] hover:bg-[#D41C29] hover:shadow-[0_6px_20px_rgba(238,39,55,0.35)] transition-all duration-300 flex items-center justify-center gap-2 font-bold text-sm relative z-10 tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Buat Repo Baru</span>
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-2xl text-[13px] font-semibold flex items-center gap-3 animate-page-in">
            <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH & FILTER --}}
    <div class="mb-8 flex flex-col lg:flex-row gap-4 relative z-10">
        {{-- Search Bar --}}
        <form action="/repository" method="GET" class="w-full lg:max-w-md shrink-0 relative group">
            @if(request('major'))
                <input type="hidden" name="major" value="{{ request('major') }}">
            @endif
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#EE2737] transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari karya, tugas, atau nama siswa..." 
                   class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-full focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#EE2737]/20 focus:border-[#EE2737] shadow-sm text-[13px] md:text-sm font-medium placeholder-gray-400 transition-all duration-300">
        </form>

        {{-- Filter Chips (Pill shaped) --}}
        <div class="flex gap-2.5 overflow-x-auto hide-scrollbar pb-1 snap-x items-center w-full">
            <a href="/repository?search={{ request('search') }}" class="snap-start px-5 py-2.5 rounded-full text-[13px] font-bold whitespace-nowrap transition-all duration-200 tap-effect {{ !$major ? 'bg-[#EE2737] text-white shadow-md shadow-red-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                Semua Jurusan
            </a>
            @foreach(['DKV', 'TJKT', 'AKL', 'MPLB', 'PM', 'TF'] as $m)
                <a href="/repository?major={{ $m }}&search={{ request('search') }}" class="snap-start px-5 py-2.5 rounded-full text-[13px] font-bold whitespace-nowrap transition-all duration-200 tap-effect {{ $major == $m ? 'bg-[#EE2737] text-white shadow-md shadow-red-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-[#EE2737] hover:border-[#EE2737]/30' }}">
                    {{ $m }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- REPOSITORY GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @forelse($repositories as $repo)
        {{-- Card Item --}}
        <a href="/repository/{{ $repo->id }}" class="bg-white rounded-3xl p-5 shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-[#EE2737]/40 hover:shadow-[0_8px_25px_rgba(238,39,55,0.08)] transition-all duration-300 flex flex-col tap-effect h-full animate-card-in" style="animation-delay: {{ $loop->index * 0.05 }}s;">
            
            <div class="flex justify-between items-start mb-4">
                {{-- Info Pemilik (Square Avatar ala Gojek App Icon) --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#EE2737] flex items-center justify-center shrink-0 shadow-sm">
                        <span class="text-[15px] font-bold text-white uppercase">{{ substr($repo->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-gray-900 truncate max-w-[140px] leading-tight mb-0.5">{{ explode(' ', $repo->user->name)[0] }}</p>
                        <p class="text-[11px] text-gray-400 font-medium leading-none">Pemilik Repo</p>
                    </div>
                </div>
                
                {{-- Badge Visibility --}}
                @if($repo->visibility == 'public')
                    <div class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-wide flex items-center gap-1">
                        Publik
                    </div>
                @else
                    <div class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wide flex items-center gap-1">
                        Privat
                    </div>
                @endif
            </div>

            {{-- Judul Repo --}}
            <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-2 line-clamp-2 flex items-start gap-2">
                <svg class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                <span>{{ $repo->name }}</span>
            </h3>
            
            {{-- Deskripsi --}}
            <p class="text-[13px] text-gray-500 font-normal leading-relaxed line-clamp-2 mb-5 flex-grow">
                {{ $repo->description ?? 'Belum ada deskripsi untuk repositori ini.' }}
            </p>
            
            {{-- Footer Info --}}
            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                @if($repo->major)
                    <span class="px-3 py-1.5 rounded-full bg-red-50 text-[#EE2737] text-[10px] font-bold uppercase tracking-wider">
                        {{ $repo->major }}
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                        Umum
                    </span>
                @endif
                
                <span class="text-[11px] font-medium text-gray-400">
                    {{ $repo->updated_at->diffForHumans(null, true, true) }} lalu
                </span>
            </div>
        </a>

        @empty
        {{-- EMPTY STATE --}}
        <div class="col-span-full py-16 bg-white rounded-3xl border border-gray-200 text-center flex flex-col items-center justify-center shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Pencarian Tidak Ditemukan</h3>
            <p class="text-[13px] text-gray-500 max-w-xs mx-auto mb-6">Belum ada karya di sini. Yuk, jadi yang pertama mengunggah tugasmu!</p>
            
            <a href="/repository/create" class="inline-flex items-center gap-2 bg-[#EE2737] text-white px-6 py-3 rounded-full font-bold text-[13px] shadow-sm hover:bg-[#D41C29] transition-all tap-effect">
                Mulai Buat Karya
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection