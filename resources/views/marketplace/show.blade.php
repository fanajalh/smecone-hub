@extends('layouts.app')
@section('title', '| ' . $product->item_name)

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="bg-gray-100 min-h-screen pb-24 md:pb-28 font-sans selection:bg-red-100 selection:text-red-900">
    
    <a href="/marketplace" class="fixed top-4 left-4 z-40 w-10 h-10 bg-white/80 backdrop-blur-md rounded-full flex items-center justify-center text-gray-800 hover:bg-white shadow-[0_4px_10px_rgba(0,0,0,0.1)] transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
    </a>

    <div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-2xl overflow-hidden">
        
        <div class="bg-gray-50 aspect-square w-full relative flex items-center justify-center group">
            @if($product->is_sold)
                <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex items-center justify-center">
                    <div class="bg-gray-900 text-white px-10 py-3.5 rounded-2xl font-black text-3xl tracking-widest uppercase transform -rotate-12 border-4 border-gray-900 shadow-2xl">HABIS</div>
                </div>
            @endif

            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
            @else
                <div class="text-gray-300 flex flex-col items-center">
                    <svg class="w-24 h-24 opacity-30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-bold text-sm">Tidak ada foto</span>
                </div>
            @endif
            
            <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
        </div>

        <div class="px-5 py-6 bg-white rounded-t-3xl -mt-6 relative z-30 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
            <div class="flex items-center gap-2 mb-3 flex-wrap">
                @if($product->is_promoted)
                    <span class="bg-gradient-to-r from-red-600 to-rose-500 text-white text-[10px] uppercase font-black px-2.5 py-1 rounded-sm shadow-sm flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path></svg> Iklan
                    </span>
                @endif
                <span class="border border-red-200 bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-wider">{{ $product->category }}</span>
                <span class="{{ $product->type == 'Ready Stock' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-orange-50 text-orange-600 border border-orange-200' }} text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-wider">{{ $product->type }}</span>
            </div>

            <h1 class="text-xl md:text-2xl font-bold text-gray-800 leading-snug line-clamp-3 mb-4">
                {{ $product->item_name }}
            </h1>

            <div class="flex items-end justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1 mt-2">Harga</p>
                    <div class="text-3xl font-black text-red-600 tracking-tight flex items-start">
                        <span class="text-sm font-bold mt-1.5 mr-1 text-red-400">Rp</span>{{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100 text-[11px] font-medium text-gray-400">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 0 Penilaian</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> {{ $product->views_count }} Dilihat</span>
                </div>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $product->created_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <div class="h-2 w-full bg-gray-100"></div>

        <div class="bg-white p-5 flex items-center justify-between hover:bg-gray-50 transition cursor-pointer" onclick="window.location.href='/marketplace/toko/{{ $product->user_id }}'">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center font-black text-white text-xl shadow-[0_2px_8px_rgba(0,0,0,0.08)]">
                    {{ substr($product->user->store_name ?? $product->user->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-sm font-bold text-gray-900">{{ $product->user->store_name ?? $product->user->name }}</h3>
                        <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
                    </div>
                    <div class="flex items-center text-xs text-gray-500 mt-1 font-medium">
                        <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ $product->location ?? 'Kantin Smecone' }}
                    </div>
                </div>
            </div>
            <div class="border border-red-200 text-red-600 px-4 py-1.5 rounded-full text-[11px] font-bold bg-red-50 flex items-center gap-1">
                Kunjungi <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>

        <div class="h-2 w-full bg-gray-100"></div>

        <div class="bg-white p-5">
            <h2 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Rincian Produk
            </h2>
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line font-medium bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                {{ $product->description }}
            </div>
        </div>

        <div class="h-2 w-full bg-gray-100"></div>

        <div class="bg-white p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Penilaian Produk
                </h2>
                <a href="#" class="text-xs text-red-500 font-bold flex items-center hover:underline">Lihat Semua <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
            </div>
            <div class="text-center py-8 bg-gray-50/70 rounded-xl border border-gray-100">
                <span class="text-4xl block mb-2 opacity-80">💬</span>
                <p class="text-xs font-medium text-gray-400">Belum ada penilaian untuk produk ini.</p>
            </div>
        </div>

        <div class="h-2 w-full bg-gray-100"></div>

        <div class="bg-white p-5 pb-8">
            <div class="flex items-center justify-center gap-2 mb-5">
                <div class="h-[1px] w-12 bg-gray-200"></div>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Kamu Mungkin Suka</h2>
                <div class="h-[1px] w-12 bg-gray-200"></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @forelse($recommendations as $rek)
                <a href="/marketplace/{{ $rek->id }}" class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-[0_8px_20px_rgba(0,0,0,0.06)] transition-all group shadow-sm flex flex-col">
                    <div class="aspect-square bg-gray-50 relative overflow-hidden">
                        @if($rek->image)
                            <img src="{{ asset('storage/' . $rek->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs font-bold">No Foto</div>
                        @endif
                        @if($rek->is_promoted)
                            <span class="absolute top-0 right-0 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[9px] font-black px-2 py-1 rounded-bl-xl shadow-sm">Ad</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="text-xs font-medium text-gray-700 line-clamp-2 leading-tight mb-2 h-8">{{ $rek->item_name }}</h3>
                        <p class="text-sm font-black text-red-600">Rp {{ number_format($rek->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-8 bg-gray-50 rounded-2xl text-xs text-gray-400 font-medium">Tidak ada rekomendasi lain.</div>
                @endforelse
            </div>
        </div>
        
    </div>
</div>

<div class="fixed bottom-0 left-0 w-full bg-white z-50 border-t border-gray-100 shadow-[0_-10px_30px_rgba(0,0,0,0.08)]">
    <div class="max-w-[480px] mx-auto h-[72px] flex items-center px-1">
        
        @if(auth()->id() == $product->user_id)
            <div class="flex-1 flex gap-2 p-3 w-full">
                <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full h-[46px] rounded-xl font-bold text-[13px] {{ $product->is_sold ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-gray-900 text-white hover:bg-gray-800' }} shadow-sm">
                        {{ $product->is_sold ? 'Tandai Tersedia' : 'Tandai Habis' }}
                    </button>
                </form>
                <form action="/marketplace/{{ $product->id }}/delete" method="POST" class="flex-1" onsubmit="return confirm('Yakin hapus lapak ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full h-[46px] rounded-xl border border-red-200 font-bold text-[13px] bg-red-50 text-red-600 hover:bg-red-100 transition shadow-sm">
                        Hapus Lapak
                    </button>
                </form>
            </div>
        @else
            @php
                $waNumber = $product->user->whatsapp_number ?? '';
                if(str_starts_with($waNumber, '0')) $waNumber = '62' . substr($waNumber, 1);
                $pesanDefault = urlencode("Halo kak, saya tertarik dengan *{$product->item_name}* di Smecone Mart. Masih ada?");
            @endphp

            <div class="flex items-center w-full h-full p-2.5 gap-2">
                <a href="{{ $product->is_sold ? '#' : 'https://wa.me/'.$waNumber.'?text='.$pesanDefault }}" target="{{ $product->is_sold ? '_self' : '_blank' }}" 
                   class="w-[60px] h-[50px] flex flex-col items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 hover:bg-emerald-100 transition {{ $product->is_sold ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <svg class="w-[22px] h-[22px] fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                </a>

                <button onclick="alert('Fitur Keranjang sedang pengembangan! Nantikan update Smecone Hub selanjutnya 🚀')" 
                        class="w-[60px] h-[50px] flex flex-col items-center justify-center rounded-xl bg-gray-50 border border-gray-200 text-gray-600 hover:bg-gray-100 transition {{ $product->is_sold ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->is_sold ? 'disabled' : '' }}>
                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </button>

                <form action="{{ route('marketplace.checkout.confirm', $product->id) }}" method="GET" class="flex-1 m-0 h-full pl-1">
                    <button type="submit" 
                            class="w-full h-[50px] rounded-xl font-black text-[13px] uppercase tracking-wide flex items-center justify-center transition shadow-xl {{ $product->is_sold ? 'bg-gray-400 text-white cursor-not-allowed shadow-none' : 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white active:scale-[0.98] shadow-red-500/25' }}" 
                            {{ $product->is_sold ? 'disabled' : '' }}>
                        {{ $product->is_sold ? 'BARANG HABIS' : 'Beli Sekarang' }}
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection