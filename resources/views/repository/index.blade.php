@extends('layouts.app')
@section('title', '| Repositori')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-6 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-purple-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-red-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10 w-full md:w-auto">
            <div class="inline-flex items-center justify-center bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 md:hidden">Tugas & Karya</div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Smecone <span class="text-red-600">Repositori</span></h1>
            <p class="text-[13px] md:text-sm text-gray-500 mt-1.5 font-medium">Gudang karya digital dan tugas untuk seluruh siswa.</p>
        </div>
        
        <a href="/repository/create" class="w-full md:w-auto bg-red-600 text-white px-6 py-3.5 rounded-[20px] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-2 font-extrabold text-[14px] relative z-10 tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Buat Repo Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 flex flex-col md:flex-row gap-4 relative z-10">
        <form action="/repository" method="GET" class="w-full md:w-[300px] shrink-0 relative tap-effect">
            @if(request('major'))
                <input type="hidden" name="major" value="{{ request('major') }}">
            @endif
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari karya atau tugas..." 
                   class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 shadow-[0_2px_10px_rgba(0,0,0,0.02)] text-[13px] md:text-sm font-bold placeholder-gray-400 transition-all">
        </form>

        <div class="flex gap-2.5 overflow-x-auto hide-scrollbar pb-2 md:pb-0 snap-x items-center">
            <a href="/repository?search={{ request('search') }}" class="snap-start px-5 py-3 rounded-[18px] text-[12px] font-extrabold whitespace-nowrap transition-all tap-effect {{ !$major ? 'bg-gray-900 text-white shadow-md shadow-gray-900/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-gray-800 shadow-sm' }}">
                Semua Jurusan
            </a>
            @foreach(['PPLG', 'DKV', 'TJKT', 'AKL', 'MPLB', 'PM', 'TF'] as $m)
                <a href="/repository?major={{ $m }}&search={{ request('search') }}" class="snap-start px-5 py-3 rounded-[18px] text-[12px] font-extrabold whitespace-nowrap transition-all tap-effect {{ $major == $m ? 'bg-red-600 text-white shadow-md shadow-red-600/20' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50 hover:text-red-600 shadow-sm' }}">
                    {{ $m }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
        @forelse($repositories as $repo)
        <a href="/repository/{{ $repo->id }}" class="bg-white rounded-[24px] p-5 md:p-6 shadow-[0_2px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-red-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col tap-effect h-full relative overflow-hidden">
            
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200 shadow-inner">
                        <span class="text-[10px] font-black text-gray-600 uppercase">{{ substr($repo->user->name, 0, 1) }}</span>
                    </div>
                    <span class="text-[11px] font-bold text-gray-500 truncate max-w-[120px]">{{ explode(' ', $repo->user->name)[0] }}</span>
                </div>
                
                <span class="px-2.5 py-1 rounded-lg border text-[9px] font-black uppercase tracking-widest flex items-center gap-1 {{ $repo->visibility == 'public' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                    @if($repo->visibility == 'public')
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    @else
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    @endif
                    {{ $repo->visibility }}
                </span>
            </div>

            <h3 class="text-[16px] md:text-lg font-extrabold text-gray-800 group-hover:text-red-600 transition-colors leading-tight mb-2 line-clamp-2">
                {{ $repo->name }}
            </h3>
            
            <p class="text-[12px] md:text-[13px] text-gray-500 leading-relaxed line-clamp-2 mb-4 flex-grow">
                {{ $repo->description ?? 'Tidak ada deskripsi yang disertakan untuk repositori ini.' }}
            </p>
            
            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                @if($repo->major)
                    <span class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-wider border border-red-100">
                        {{ $repo->major }}
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-lg bg-gray-50 text-gray-500 text-[10px] font-black uppercase tracking-wider border border-gray-100">
                        Umum
                    </span>
                @endif
                
                <div class="flex items-center gap-1.5 text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[10px] font-bold tracking-tighter">{{ $repo->updated_at->diffForHumans(null, true, true) }}</span>
                </div>
            </div>
            
        </a>
        @empty
        <div class="col-span-full py-16 md:py-24 bg-white rounded-[32px] border border-gray-100 text-center flex flex-col items-center justify-center shadow-sm">
            <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-5 relative group">
                <svg class="w-10 h-10 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                <div class="absolute top-2 right-2 text-xl opacity-30 animate-pulse">✨</div>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 mb-1">Belum ada repositori</h3>
            <p class="text-[13px] text-gray-500 max-w-sm mx-auto mb-6">Jadilah yang pertama membuat proyek Smecone atau coba ubah filter pencarianmu.</p>
            
            <a href="/repository/create" class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-xl font-extrabold text-[13px] hover:bg-red-700 hover:shadow-lg transition-all tap-effect active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Mulai Buat Karya
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection