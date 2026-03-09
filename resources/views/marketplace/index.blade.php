@extends('layouts.app')
@section('title', '| Jajan & Jasa')

@section('content')
<div class="max-w-7xl mx-auto pt-4 px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <div class="bg-white px-6 py-5 md:mt-4 shadow-sm flex flex-col md:flex-row md:justify-between items-start md:items-center gap-4 rounded-2xl border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-red-600 tracking-tight">Marketplace</h1>
            <p class="text-sm text-gray-500 mt-1">Jual beli jajan & jasa antar siswa Smecone.</p>
        </div>
        <a href="/marketplace/create" class="w-full md:w-auto bg-red-600 text-white px-5 py-3 rounded-xl shadow-md hover:bg-red-700 active:scale-95 transition-all flex items-center justify-center gap-2 font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Mulai Jualan</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="block sm:inline font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex gap-2 mt-6 overflow-x-auto pb-2 scrollbar-hide">
        <a href="#" class="bg-red-600 text-white px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap shadow-sm">Semua</a>
        <a href="#" class="bg-white text-gray-600 border border-gray-200 px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap hover:bg-gray-50">🍔 Jajan</a>
        <a href="#" class="bg-white text-gray-600 border border-gray-200 px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap hover:bg-gray-50">🎨 Jasa Desain</a>
        <a href="#" class="bg-white text-gray-600 border border-gray-200 px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap hover:bg-gray-50">📦 Barang Bekas</a>
    </div>

    <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        
        @if($items->isEmpty())
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-gray-100">
                <div class="bg-red-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium text-lg">Belum ada dagangan yang diposting.</p>
            </div>
        @endif

        @foreach($items as $item)
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300 group cursor-pointer relative">
            
            <div class="absolute top-3 left-3 z-10 px-2.5 py-1 {{ $item->type == 'jasa' ? 'bg-blue-500' : 'bg-orange-500' }} text-white text-[10px] font-black uppercase tracking-wider rounded-lg shadow-sm">
                {{ $item->type }}
            </div>

            <div class="aspect-square w-full relative overflow-hidden bg-gray-50">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 hidden md:block"></div>
            </div>
            
            <div class="p-4 md:p-5 flex flex-col flex-grow">
                <h2 class="text-sm md:text-base font-bold text-gray-800 leading-tight line-clamp-2">{{ $item->title }}</h2>
                <p class="text-lg md:text-xl font-black text-red-600 mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                
                <div class="mt-auto pt-4 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <span class="text-[10px] font-bold text-red-600">{{ substr($item->user->name, 0, 1) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium truncate">{{ $item->user->name }}</p>
                </div>

                @if($item->user_id !== auth()->id())
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->whatsapp_number) }}?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($item->title) }}%20yang%20dijual%20di%20Smecone%20Hub" 
                   target="_blank"
                   class="mt-4 w-full py-2.5 bg-green-50 text-green-600 font-bold rounded-xl border border-green-200 hover:bg-green-500 hover:text-white hover:border-green-500 active:scale-95 transition-all flex justify-center items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.89-4.443 9.893-9.896.002-5.466-4.415-9.898-9.881-9.898-5.452 0-9.887 4.426-9.889 9.896-.001 1.822.514 3.388 1.632 5.145l-1.125 4.104 4.185-1.129zm8.593-8.127c-.47-.235-2.784-1.375-3.219-1.535-.435-.16-.752-.235-1.069.235-.316.47-1.218 1.535-1.493 1.849-.275.314-.551.353-1.02.118-2.316-1.159-3.418-2.618-4.782-5.011-.122-.213.344-.199 1.144-1.849.079-.157.039-.314-.04-.511-.079-.196-.99-2.39-1.355-3.272-.361-.861-.726-.745-1.01-.758-.275-.013-.592-.014-.909-.014-.316 0-.83.118-1.264.588-.435.47-1.662 1.624-1.662 3.962 0 2.338 1.701 4.598 1.938 4.912.237.314 3.351 5.115 8.121 7.18 3.868 1.674 4.881 1.602 5.761 1.409.879-.193 2.784-1.137 3.18-2.238.396-1.101.396-2.045.275-2.238-.119-.196-.435-.314-.906-.549z"/></svg>
                    Chat WA
                </a>
                @else
                <div class="mt-4 w-full py-2.5 bg-gray-50 text-gray-400 font-semibold rounded-xl text-center text-sm border border-gray-100">
                    Dagangan Anda
                </div>
                @endif
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection