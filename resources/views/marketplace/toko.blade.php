@extends('layouts.app')
@section('title', '| Toko ' . ($seller->store_name ?? $seller->name))

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <a href="/marketplace" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 font-bold text-sm mb-4 transition-colors tap-effect w-fit">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Smecone Mart
    </a>

    <div class="bg-white rounded-[24px] overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 mb-6">
        <div class="h-32 md:h-48 relative bg-gray-900">
            @if($seller->store_banner)
                <img src="{{ asset('storage/' . $seller->store_banner) }}" class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-gray-900"></div>
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            @endif
            
            <div class="absolute bottom-0 left-0 w-full p-4 md:p-6 bg-gradient-to-t from-black/80 to-transparent flex items-end gap-4">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-full flex items-center justify-center font-black text-2xl md:text-3xl text-gray-400 shadow-lg border-4 border-white/20 uppercase overflow-hidden shrink-0">
                    @if($seller->store_photo)
                        <img src="{{ asset('storage/' . $seller->store_photo) }}" class="w-full h-full object-cover">
                    @elseif($seller->avatar)
                        <img src="{{ asset('storage/' . $seller->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-red-600">{{ substr($seller->store_name ?? $seller->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="text-white mb-1">
                    <h1 class="text-lg md:text-2xl font-extrabold tracking-tight">{{ $seller->store_name ?? $seller->name }}</h1>
                    <p class="text-xs md:text-sm font-medium text-gray-300 opacity-90 flex items-center gap-1">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        Penjual Terverifikasi
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6 bg-white flex flex-wrap gap-4 md:gap-10 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Produk</p>
                    <p class="text-sm md:text-base font-black text-gray-900">{{ $totalProducts }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Terjual</p>
                    <p class="text-sm md:text-base font-black text-gray-900">{{ $soldProducts }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Bergabung</p>
                    <p class="text-sm md:text-base font-black text-gray-900">{{ $seller->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-lg md:text-xl font-extrabold text-gray-900 mb-4 border-l-4 border-red-600 pl-3">Etalase Toko</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-5">
        @forelse($products as $product)
        <div class="relative group bg-white rounded-[24px] border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_8px_30px_rgba(220,38,38,0.08)] hover:border-red-100 transition-all duration-300 flex flex-col hover:-translate-y-1">
            <a href="/marketplace/{{ $product->id }}" class="flex flex-col h-full tap-effect {{ $product->is_sold ? 'opacity-75 grayscale-[40%]' : '' }}">
                <div class="aspect-square relative overflow-hidden bg-gray-50">
                    
                    @if($product->is_sold)
                    <div class="absolute inset-0 bg-white/40 backdrop-blur-[1px] z-20 flex items-center justify-center">
                        <span class="bg-gray-900 text-white px-4 py-1.5 rounded-xl font-black text-xs tracking-widest uppercase transform -rotate-12 border-2 border-white shadow-xl">Habis</span>
                    </div>
                    @endif

                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-100/50">
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-40">No Foto</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-2 left-2 z-10 flex flex-col gap-1.5">
                        @if($product->is_promoted)
                            <span class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-[8px] font-black uppercase shadow-sm border border-yellow-300 w-fit">⭐ AD</span>
                        @endif
                        <span class="px-2.5 py-1 rounded-lg bg-gray-900/60 backdrop-blur-md text-white text-[8px] font-black uppercase border border-white/20 shadow-sm w-fit">{{ $product->category }}</span>
                    </div>
                </div>

                <div class="p-3 md:p-4 flex flex-col flex-1 bg-white">
                    <div class="text-red-600 font-black text-base md:text-[18px] mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <h3 class="text-[13px] md:text-[14px] font-bold text-gray-800 line-clamp-2 mb-3">{{ $product->item_name }}</h3>
                    <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-1 shrink-0 text-gray-400 bg-gray-50 px-2 py-0.5 rounded-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-[9px] font-bold">{{ $product->views_count }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-span-full py-16 bg-white rounded-[24px] border border-gray-100 text-center shadow-sm">
            <p class="text-[13px] text-gray-500 font-medium">Penjual ini belum menambahkan produk apapun.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection