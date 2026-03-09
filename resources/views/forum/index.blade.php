@extends('layouts.app')
@section('title', '| Eksplorasi Channel')

@section('content')
<div class="max-w-4xl mx-auto pt-4 px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <div class="bg-white px-6 py-6 md:mt-4 shadow-sm rounded-2xl border border-gray-100 mb-6 text-center md:text-left flex flex-col md:flex-row md:justify-between items-center gap-6">
        <div>
            <h1 class="text-2xl font-extrabold text-red-600 tracking-tight">Eksplorasi Channel</h1>
            <p class="text-sm text-gray-500 mt-1">Cari dan gabung ke ruang obrolan yang kamu minati.</p>
        </div>
        
        <form action="/forum" method="GET" class="w-full md:w-80 relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama channel atau topik..." 
                   class="w-full pl-4 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-sm font-medium">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center hover:bg-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-bold shadow-sm">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-2 md:p-4">
        @if($search)
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 px-2">Hasil Pencarian: "{{ $search }}"</h2>
        @else
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 px-2">Rekomendasi Channel Tersedia</h2>
        @endif
        
        <div class="space-y-2">
            @forelse($channels as $channel)
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 rounded-2xl border border-gray-100 hover:border-red-100 hover:bg-red-50/30 transition group gap-4">
                
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center font-black text-2xl shrink-0">#</div>
                    <div>
                        <h3 class="font-extrabold text-gray-800 text-base md:text-lg group-hover:text-red-600 transition">{{ $channel->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed line-clamp-2 md:w-96">{{ $channel->content }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-[10px] font-bold text-gray-400">Oleh: {{ $channel->user->name }}</span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md text-[10px] font-black tracking-widest">{{ $channel->replies_count }} Chat</span>
                            <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded-md text-[10px] font-black tracking-widest">{{ $channel->members->count() }} Anggota</span>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-auto shrink-0 mt-2 md:mt-0">
                    @if($channel->members->contains(auth()->id()))
                        <a href="/forum/{{ $channel->id }}" class="block w-full text-center bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Masuk Obrolan</a>
                    @else
                        <form action="/forum/{{ $channel->id }}/join" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-red-700 transition shadow-sm">Gabung Channel</button>
                        </form>
                    @endif
                </div>

            </div>
            @empty
            <div class="text-center py-12">
                <p class="text-gray-500 font-medium">Channel tidak ditemukan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection