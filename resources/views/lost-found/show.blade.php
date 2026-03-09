@extends('layouts.app')
@section('title', '| Detail Laporan')

@section('content')
<div class="max-w-2xl mx-auto md:pt-6 md:px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <a href="/lost-found" class="fixed top-4 left-4 z-50 w-10 h-10 bg-black/30 backdrop-blur-md rounded-full flex items-center justify-center text-white md:hidden">
        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
    </a>

    <div class="bg-white md:rounded-3xl shadow-sm border-gray-100 overflow-hidden relative">
        
        <div class="w-full aspect-square md:aspect-video bg-gray-100 relative">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                    <svg class="w-20 h-20 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
        </div>

        <div class="p-5 md:p-8">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 text-[10px] font-black tracking-wider uppercase rounded-md {{ $item->type == 'lost' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                        {{ $item->type == 'lost' ? 'Info Kehilangan' : 'Barang Ditemukan' }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">{{ $item->created_at->format('d M Y') }}</span>
                </div>
                
                @if($item->user_id == auth()->id() || auth()->user()->is_admin)
                <div class="flex gap-2">
                    <a href="/lost-found/{{ $item->id }}/edit" class="p-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <form action="/lost-found/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>
            
            <h1 class="text-2xl font-extrabold text-gray-800 leading-tight mb-4">{{ $item->item_name }}</h1>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-black text-lg shadow-sm">
                        {{ substr($item->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-0.5">Dilaporkan Oleh</p>
                        <p class="font-bold text-gray-800 text-sm">{{ $item->user->name }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 mb-6">

            <h3 class="text-sm font-bold text-gray-800 mb-2">Detail & Ciri-ciri</h3>
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $item->description }}</p>

            @if(session('success'))
                <div class="mt-6 bg-green-50 text-green-700 px-4 py-3 rounded-xl text-sm font-bold border border-green-200 shadow-sm text-center">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <div class="fixed md:static bottom-[60px] md:bottom-auto left-0 w-full bg-white border-t border-gray-200 p-3 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] md:mt-6 md:rounded-2xl md:border z-40">
        
        @if($item->status == 'active' && $item->user_id !== auth()->id())
            <a href="/chat/item/{{ $item->id }}" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl active:scale-95 transition-all flex justify-center items-center gap-2 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Hubungi Admin Kesiswaan
            </a>
        @elseif($item->status == 'active' && $item->user_id === auth()->id())
            <div class="flex gap-2">
                <div class="w-2/3 py-3.5 bg-gray-50 text-gray-500 font-bold rounded-xl text-center text-sm border border-gray-200">
                    Laporanmu sedang dipublikasi
                </div>
                <a href="/chat/item/{{ $item->id }}" class="w-1/3 py-3.5 bg-white text-gray-700 font-bold rounded-xl border border-gray-300 hover:bg-gray-100 transition-all flex justify-center items-center text-sm">
                    Chat
                </a>
            </div>
        @elseif($item->status == 'resolved')
            <div class="w-full py-3.5 bg-gray-100 text-gray-400 font-bold rounded-xl text-center text-sm flex justify-center items-center gap-2">
                Kasus Telah Diselesaikan Kesiswaan
            </div>
        @elseif($item->status == 'pending')
            <div class="w-full py-3.5 bg-yellow-50 text-yellow-600 font-bold rounded-xl text-center text-sm border border-yellow-200">
                Menunggu Verifikasi Kesiswaan
            </div>
        @endif
    </div>

</div>
@endsection