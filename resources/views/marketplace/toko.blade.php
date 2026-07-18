@extends('layouts.app')
@section('title', '| Toko ' . ($seller->store_name ?? $seller->name))

@section('content')
<div class="max-w-7xl mx-auto pt-24 md:pt-32 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in font-sans text-gray-800">
    
    <a href="/marketplace" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-red-600 font-medium text-[13px] md:text-sm mb-4 transition-colors tap-effect w-fit">
        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Smecone Mart
    </a>

    <div class="bg-white md:rounded-[32px] rounded-3xl overflow-hidden shadow-[0_2px_15px_rgba(0,0,0,0.02)] border border-gray-100 mb-8 relative">
        
        <div class="h-28 md:h-48 relative bg-gray-100">
            @if($seller->store_banner)
                <img src="{{ asset('storage/' . $seller->store_banner) }}" class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-400"></div>
                <div class="absolute inset-0 opacity-10 mix-blend-overlay bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            @endif
        </div>

        <div class="px-5 pb-5 relative">
            <div class="flex flex-col md:flex-row md:items-end gap-3 md:gap-5 -mt-10 md:-mt-12 relative z-10 mb-4 md:mb-0">
                <div class="w-20 h-20 md:w-28 md:h-28 bg-white rounded-full flex items-center justify-center font-bold text-2xl md:text-3xl text-gray-400 shadow-sm border-4 border-white uppercase overflow-hidden shrink-0">
                    @if($seller->store_photo)
                        <img src="{{ asset('storage/' . $seller->store_photo) }}" class="w-full h-full object-cover">
                    @elseif($seller->avatar)
                        <img src="{{ asset('storage/' . $seller->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400">{{ substr($seller->store_name ?? $seller->name, 0, 1) }}</span>
                    @endif
                </div>
                
                <div class="pb-1">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight leading-tight">{{ $seller->store_name ?? $seller->name }}</h1>
                    <p class="text-[12px] md:text-[13px] font-normal text-gray-500 mt-0.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Penjual Terverifikasi
                    </p>
                </div>
            </div>

            <div class="mt-5 pt-4 md:mt-0 md:pt-0 border-t border-gray-50 md:border-none md:absolute md:bottom-5 md:right-6 flex flex-wrap gap-4 md:gap-6 justify-between md:justify-end w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-red-50 rounded-full flex items-center justify-center text-red-500 shrink-0">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-[11px] font-medium text-gray-400 uppercase tracking-wide">Produk</p>
                        <p class="text-[14px] md:text-[16px] font-bold text-gray-900 leading-tight">{{ $totalProducts }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 shrink-0">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-[11px] font-medium text-gray-400 uppercase tracking-wide">Terjual</p>
                        <p class="text-[14px] md:text-[16px] font-bold text-gray-900 leading-tight">{{ $soldProducts }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 shrink-0">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-[11px] font-medium text-gray-400 uppercase tracking-wide">Bergabung</p>
                        <p class="text-[12px] md:text-[14px] font-semibold text-gray-900 leading-tight">{{ str_replace('yang lalu', '', $seller->created_at->diffForHumans()) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4 mt-2 px-1">
        <h2 class="text-[16px] md:text-[18px] font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            Etalase Toko
        </h2>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
        @forelse($products as $product)
        <div class="bg-white rounded-[16px] border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_4px_15px_rgba(220,38,38,0.05)] transition-all duration-300 flex flex-col relative group">
            
            <a href="/marketplace/{{ $product->id }}" class="flex flex-col h-full tap-effect {{ $product->is_sold ? 'opacity-75' : '' }}">
                
                <div class="aspect-square w-full shrink-0 relative overflow-hidden bg-gray-50 border-b border-gray-50">
                    
                    @if($product->is_sold)
                    <div class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-20 flex items-center justify-center">
                        <span class="bg-gray-900 text-white px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest">Habis</span>
                    </div>
                    @endif

                    @if($product->image)
                        @php 
                            $decoded = json_decode($product->image, true);
                            $firstImage = is_array($decoded) ? $decoded[0] : $product->image;
                        @endphp
                        <img src="{{ asset('storage/' . $firstImage) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                            <svg class="w-8 h-8 opacity-50 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-[9px] font-medium uppercase tracking-widest opacity-60">No Foto</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-2 left-2 z-10 flex flex-col gap-1.5">
                        @if($product->is_promoted)
                            <span class="px-1.5 py-0.5 rounded bg-gradient-to-r from-red-600 to-rose-500 text-white text-[8px] font-bold uppercase shadow-sm w-fit flex items-center gap-0.5">
                                ⭐ AD
                            </span>
                        @endif
                    </div>
                    
                    <div class="absolute bottom-2 right-2 z-10">
                        <span class="px-1.5 py-0.5 rounded bg-white/90 backdrop-blur-sm text-gray-600 text-[9px] font-medium border border-gray-100 shadow-sm">
                            {{ $product->category }}
                        </span>
                    </div>
                </div>

                <div class="p-2.5 md:p-3 flex flex-col flex-1">
                    
                    <h3 class="text-[12px] md:text-[13px] font-medium text-gray-800 line-clamp-2 leading-tight mb-2 group-hover:text-red-600 transition-colors h-8">
                        {{ $product->item_name }}
                    </h3>
                    
                    <div class="mt-auto">
                        <div class="text-red-600 font-bold text-[14px] md:text-[15px] mb-1.5 tracking-tight">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span>-</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <span>{{ $product->views_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-span-full py-12 md:py-16 bg-white rounded-2xl border border-gray-100 text-center shadow-[0_2px_8px_rgba(0,0,0,0.02)] flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <p class="text-[13px] text-gray-500 font-normal">Penjual ini belum menambahkan produk apapun.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection