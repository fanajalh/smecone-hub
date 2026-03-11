@extends('layouts.app')
@section('title', '| ' . $product->item_name)

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="bg-gray-100 min-h-screen pb-20 md:pb-24 font-sans">
    
    <a href="/marketplace" class="fixed top-4 left-4 z-40 w-10 h-10 bg-black/30 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-black/50 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>

    <div class="max-w-3xl mx-auto md:shadow-lg bg-gray-100 md:mt-6">
        
        <div class="bg-white aspect-square md:aspect-video w-full relative overflow-hidden flex items-center justify-center">
            @if($product->is_sold)
                <div class="absolute inset-0 bg-white/50 backdrop-blur-sm z-20 flex items-center justify-center">
                    <span class="bg-gray-900 text-white px-8 py-3 rounded-full font-black text-2xl tracking-widest uppercase transform -rotate-12 border-4 border-white shadow-2xl">HABIS</span>
                </div>
            @endif

            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
            @else
                <div class="text-gray-300 flex flex-col items-center">
                    <svg class="w-24 h-24 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-bold text-sm">Tidak ada foto</span>
                </div>
            @endif
        </div>

        <div class="bg-white p-4 md:p-6 mb-2">
            <div class="text-3xl font-bold text-red-600 tracking-tight mb-2">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
            
            <div class="flex items-center gap-2 mb-2 flex-wrap">
                @if($product->is_promoted)
                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">Iklan</span>
                @endif
                <span class="border border-red-500 text-red-500 text-[10px] font-bold px-2 py-0.5 rounded">{{ $product->category }}</span>
                <span class="{{ $product->type == 'Ready Stock' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} text-[10px] font-bold px-2 py-0.5 rounded">{{ $product->type }}</span>
            </div>

            <h1 class="text-base md:text-xl font-medium text-gray-800 leading-snug line-clamp-3">
                {{ $product->item_name }}
            </h1>

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                <div class="flex items-center gap-4">
                    <span>⭐ 0 Penilaian</span> <span>{{ $product->views_count }} Dilihat</span>
                </div>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $product->created_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 mb-2 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center font-black text-gray-500 text-lg border border-gray-300">
                    {{ substr($product->user->store_name ?? $product->user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $product->user->store_name ?? $product->user->name }}</h3>
                    <div class="flex items-center text-xs text-gray-500 mt-0.5">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $product->location ?? 'Kantin Smecone' }}
                    </div>
                </div>
            </div>
            <a href="#" class="border border-red-500 text-red-500 px-4 py-1.5 rounded-full text-xs font-bold hover:bg-red-50 transition">
                Kunjungi Toko
            </a>
        </div>

        <div class="bg-white p-4 md:p-6 mb-2">
            <h2 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Rincian Produk</h2>
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line font-medium">
                {{ $product->description }}
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 mb-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-gray-800">Penilaian Produk</h2>
                <a href="#" class="text-xs text-red-500 font-medium flex items-center">Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
            </div>
            <div class="text-center py-6 text-gray-400">
                <span class="text-4xl block mb-2">💬</span>
                <p class="text-xs font-medium">Belum ada penilaian untuk produk ini.</p>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 md:rounded-b-2xl">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-red-500 rounded-full"></div>
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Kamu Mungkin Suka</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @forelse($recommendations as $rek)
                <a href="/marketplace/{{ $rek->id }}" class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
                    <div class="aspect-square bg-gray-100 relative">
                        @if($rek->image)
                            <img src="{{ asset('storage/' . $rek->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs font-bold">No Foto</div>
                        @endif
                        @if($rek->is_promoted)
                            <span class="absolute top-0 right-0 bg-yellow-400 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-bl-lg">Ad</span>
                        @endif
                    </div>
                    <div class="p-2.5">
                        <h3 class="text-[11px] font-medium text-gray-800 line-clamp-2 leading-tight mb-1">{{ $rek->item_name }}</h3>
                        <p class="text-sm font-bold text-red-600">Rp {{ number_format($rek->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-4 text-xs text-gray-400">Tidak ada rekomendasi lain di kategori ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
    <div class="max-w-3xl mx-auto h-14 md:h-16 flex">
        
        @if(auth()->id() == $product->user_id)
            <div class="flex-1 flex gap-2 p-2">
                <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full h-full rounded-lg font-bold text-xs md:text-sm {{ $product->is_sold ? 'bg-green-100 text-green-700' : 'bg-gray-800 text-white' }}">
                        {{ $product->is_sold ? 'Tandai Tersedia' : 'Tandai Habis' }}
                    </button>
                </form>
                <form action="/marketplace/{{ $product->id }}/delete" method="POST" class="flex-1" onsubmit="return confirm('Yakin hapus lapak ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full h-full rounded-lg font-bold text-xs md:text-sm bg-red-100 text-red-600 hover:bg-red-200 transition">
                        Hapus Lapak
                    </button>
                </form>
            </div>
        @else
            @php
                // Logic Link Chat WA
                $waNumber = $product->user->whatsapp_number ?? '';
                if(str_starts_with($waNumber, '0')) $waNumber = '62' . substr($waNumber, 1);
                $pesanDefault = urlencode("Halo kak, saya tertarik dengan *{$product->item_name}* di Smecone Mart. Masih ada?");
            @endphp

            <a href="{{ $product->is_sold ? '#' : 'https://wa.me/'.$waNumber.'?text='.$pesanDefault }}" target="{{ $product->is_sold ? '_self' : '_blank' }}" 
               class="w-[20%] flex flex-col items-center justify-center border-r border-gray-200 text-emerald-600 hover:bg-emerald-50 transition {{ $product->is_sold ? 'opacity-50 cursor-not-allowed' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <span class="text-[9px] font-bold">Chat</span>
            </a>

            <button onclick="alert('Fitur Keranjang sedang dalam pengembangan! Nantikan update Smecone Hub selanjutnya 🚀')" 
                    class="w-[20%] flex flex-col items-center justify-center text-gray-600 hover:bg-gray-50 transition {{ $product->is_sold ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->is_sold ? 'disabled' : '' }}>
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="text-[9px] font-bold">Keranjang</span>
            </button>

            <button onclick="alert('Fitur Checkout & Payment Gateway sedang dibuat oleh Developer! Untuk sementara transaksi via Chat WA ya 😊')" 
                    class="w-[60%] bg-red-600 hover:bg-red-700 text-white font-bold text-sm flex items-center justify-center transition {{ $product->is_sold ? 'bg-gray-400 cursor-not-allowed' : '' }}" {{ $product->is_sold ? 'disabled' : '' }}>
                {{ $product->is_sold ? 'BARANG HABIS' : 'Beli Sekarang' }}
            </button>
        @endif
    </div>
</div>
@endsection