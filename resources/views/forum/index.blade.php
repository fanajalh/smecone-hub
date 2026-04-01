@extends('layouts.app')
@section('title', '| Eksplorasi Channel')

@section('content')
<div class="max-w-4xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-8 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-orange-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center justify-center bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 md:hidden">Forum</div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Eksplorasi <span class="text-red-600">Channel</span></h1>
            <p class="text-[13px] md:text-sm text-gray-500 mt-1.5 font-medium">Cari dan gabung ke ruang obrolan yang kamu minati.</p>
        </div>
        
        <form action="/forum" method="GET" class="w-full md:w-[340px] relative z-10 tap-effect">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama channel atau topik..." 
                   class="w-full pl-11 pr-14 py-3.5 bg-gray-50/80 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-[13px] md:text-sm font-bold placeholder-gray-400 transition-all shadow-inner">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-9 h-9 bg-red-600 text-white rounded-2xl flex items-center justify-center hover:bg-red-700 hover:shadow-md transition-all active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-100 text-red-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-6 px-1 flex items-center justify-between">
        @if($search)
            <h2 class="text-base md:text-lg font-extrabold text-gray-800 tracking-tight">Hasil Pencarian: <span class="text-red-600">"{{ $search }}"</span></h2>
        @else
            <h2 class="text-base md:text-lg font-extrabold text-gray-800 tracking-tight">Rekomendasi Channel 🔥</h2>
        @endif
        <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg">{{ $channels->count() }} Channel</span>
    </div>
    
    <div class="space-y-4">
        @forelse($channels as $channel)
        <div class="bg-white rounded-[24px] p-5 shadow-[0_2px_15px_rgba(0,0,0,0.02)] border border-gray-100 hover:border-red-100 hover:shadow-[0_8px_30px_rgba(220,38,38,0.06)] transition-all duration-300 group flex flex-col md:flex-row md:items-center justify-between gap-5 tap-effect">
            
            <div class="flex items-start gap-4 w-full">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-[18px] bg-red-50 text-red-500 flex items-center justify-center font-black text-2xl shrink-0 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300 shadow-inner">#</div>
                
                <div class="flex-1 min-w-0">
                    <h3 class="font-extrabold text-gray-800 text-[15px] md:text-lg truncate group-hover:text-red-600 transition-colors flex items-center gap-2">
                        @if($channel->is_private)
                            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        @endif
                        {{ $channel->title }}
                    </h3>
                    <p class="text-[12px] md:text-[13px] text-gray-500 mt-1 leading-snug line-clamp-2">{{ $channel->content }}</p>
                    
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-lg">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            <span class="text-[10px] font-bold text-gray-500">{{ explode(' ', $channel->user->name)[0] }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg">
                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
                            <span class="text-[10px] font-bold text-blue-600">{{ $channel->replies_count }} Chat</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-green-50 border border-green-100 px-2.5 py-1 rounded-lg">
                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                            <span class="text-[10px] font-bold text-green-600">{{ $channel->members->count() }} Anggota</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-auto shrink-0 border-t border-gray-50 md:border-none pt-4 md:pt-0 mt-2 md:mt-0 flex justify-end">
                @if($channel->members->contains(auth()->id()))
                    <a href="/forum/{{ $channel->id }}" class="w-full md:w-auto text-center bg-gray-50 border border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-extrabold text-[13px] hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        Masuk Obrolan
                    </a>
                @else
                    @php
                        $pending = $channel->joinRequests ? $channel->joinRequests->where('status', 'pending')->first() : null;
                    @endphp
                    @if($pending)
                        <button disabled class="w-full md:w-auto bg-yellow-100 text-yellow-700 px-6 py-3 rounded-xl font-extrabold text-[13px] border border-yellow-200 cursor-not-allowed text-center">
                            Menunggu Persetujuan
                        </button>
                    @else
                        <form action="/forum/{{ $channel->id }}/join" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit" class="w-full md:w-auto {{ $channel->is_private && !auth()->user()->is_admin ? 'bg-orange-100 text-orange-700 hover:bg-orange-600 hover:text-white border-orange-200' : 'bg-red-600 text-white hover:bg-red-700 hover:shadow-red-500/30' }} px-6 py-3 rounded-xl font-extrabold text-[13px] hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2 border border-transparent">
                                @if($channel->is_private && !auth()->user()->is_admin)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <span>Minta Izin Gabung</span>
                                @else
                                    <span>Gabung</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                @endif
                            </button>
                        </form>
                    @endif
                @endif
            </div>

        </div>
        @empty
        <div class="bg-white rounded-[32px] p-10 shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 3l-6 18"></path></svg>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900 mb-1">Yah, channel tidak ditemukan</h3>
            <p class="text-[13px] text-gray-500 max-w-sm mx-auto">Coba gunakan kata kunci lain untuk mencari topik obrolan yang kamu suka.</p>
            @if($search)
                <a href="/forum" class="mt-6 inline-flex items-center gap-2 bg-red-50 text-red-600 px-5 py-2.5 rounded-xl font-extrabold text-[13px] hover:bg-red-100 transition tap-effect">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Semua Channel
                </a>
            @endif
        </div>
        @endforelse
    </div>
</div>
@endsection 