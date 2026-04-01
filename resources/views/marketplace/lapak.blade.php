@extends('layouts.app')
@section('title', '| Lapak Saya')

@section('content')
<div class="max-w-7xl mx-auto pt-4 md:pt-6 px-0 md:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in font-sans text-gray-800">
    
    <div class="bg-white md:rounded-[32px] overflow-hidden shadow-sm md:shadow-[0_2px_15px_rgba(0,0,0,0.02)] md:border border-gray-100 mb-6 relative">
        
        <div class="h-28 md:h-48 w-full bg-gray-100 relative">
            @if(auth()->user()->store_banner)
                <img src="{{ asset('storage/' . auth()->user()->store_banner) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-red-600 to-red-400"></div>
            @endif
            <button onclick="openProfileModal()" class="absolute top-4 right-4 bg-black/30 hover:bg-black/50 backdrop-blur-md text-white p-2 md:px-4 md:py-2 rounded-full transition active:scale-95 flex items-center gap-2 text-[13px] font-medium">
                <svg class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                <span class="hidden md:inline">Edit Profil</span>
            </button>
        </div>

        <div class="px-4 md:px-6 pb-6 relative">
            <div class="flex items-end gap-3 -mt-10 relative z-10 mb-5">
                <div class="w-20 h-20 md:w-28 md:h-28 rounded-full border-4 border-white shadow-sm shrink-0 overflow-hidden bg-white flex items-center justify-center text-gray-400 text-2xl font-bold uppercase">
                    @if(auth()->user()->store_photo)
                        <img src="{{ asset('storage/' . auth()->user()->store_photo) }}" class="w-full h-full object-cover">
                    @elseif(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(auth()->user()->store_name, 0, 1) }}
                    @endif
                </div>
                <div class="pb-1">
                    <h1 class="text-[18px] md:text-2xl font-bold text-gray-900 leading-tight">{{ auth()->user()->store_name }}</h1>
                    <div class="flex items-center gap-1 text-[12px] md:text-[13px] text-gray-500 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Terverifikasi
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 md:flex md:gap-3 gap-2">
                <a href="/marketplace/toko/{{ auth()->id() }}" class="col-span-2 md:flex-none md:px-6 flex items-center justify-center bg-gray-50 border border-gray-100 hover:bg-gray-100 text-gray-700 py-2.5 rounded-xl text-[13px] md:text-[14px] font-medium transition active:scale-95">
                    Lihat Lapak
                </a>
                <a href="{{ route('marketplace.sales') }}" class="col-span-1 md:w-12 flex items-center justify-center bg-gray-50 border border-gray-100 hover:bg-gray-100 text-gray-700 py-2.5 rounded-xl transition active:scale-95" title="Riwayat Penjualan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </a>
                <a href="/marketplace/create" class="col-span-1 md:flex-none md:px-6 flex items-center justify-center gap-2 bg-red-50 text-red-600 md:bg-red-600 md:text-white py-2.5 rounded-xl transition active:scale-95 font-medium text-[14px]" title="Tambah Produk">
                    <svg class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="hidden md:inline">Tambah Produk</span>
                </a>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-0">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
            <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                <div class="text-gray-400 text-[11px] md:text-[12px] font-medium mb-1">Total Dilihat 👀</div>
                <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $totalViews }} <span class="text-[10px] font-normal text-gray-400">kali</span></div>
            </div>
            <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                <div class="text-gray-400 text-[11px] md:text-[12px] font-medium mb-1">Produk Aktif 📦</div>
                <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $activeProducts }}</div>
            </div>
            <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                <div class="text-gray-400 text-[11px] md:text-[12px] font-medium mb-1">Terjual 🔴</div>
                <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $soldProducts }}</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-4 md:p-5 rounded-2xl shadow-sm relative overflow-hidden flex flex-col justify-center">
                <div class="absolute -right-2 -bottom-2 opacity-10 text-5xl md:text-6xl">💰</div>
                <div class="flex justify-between items-start z-10 w-full relative">
                    <div>
                        <div class="text-green-100 text-[11px] md:text-[12px] font-medium mb-0.5 md:mb-1">Saldo Lapak</div>
                        <div class="text-xl md:text-2xl font-bold text-white leading-tight">Rp {{ number_format(auth()->user()->store_balance, 0, ',', '.') }}</div>
                    </div>
                    <button type="button" onclick="{{ auth()->user()->store_balance >= 10000 ? "document.getElementById('wdModal').classList.remove('hidden');" : "alert('Minimal penarikan adalah Rp 10.000');" }}" class="bg-white/20 hover:bg-white/30 text-white text-[10px] md:text-xs font-bold px-2 py-1 md:py-1.5 rounded-lg backdrop-blur-sm transition active:scale-95 shrink-0 mt-1">Tarik</button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] p-4 md:p-6 mb-6 md:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3 md:gap-2">
                <div class="w-10 h-10 md:w-8 md:h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500 shrink-0">
                    <svg class="w-5 h-5 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                </div>
                <div>
                    <h3 class="text-[14px] md:text-[15px] font-semibold text-gray-900 leading-tight">Nomor WhatsApp</h3>
                    <p class="text-[11px] md:text-[13px] text-gray-500 mt-0.5">Untuk menerima notif pesanan</p>
                </div>
            </div>
            <form action="{{ route('marketplace.updateWa') }}" method="POST" class="flex flex-col md:flex-row gap-2">
                @csrf
                <input type="text" name="whatsapp_number" value="{{ auth()->user()->whatsapp_number }}" placeholder="Contoh: 628123456789" class="w-full md:w-64 px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-green-500 text-[13px] md:text-[14px] bg-gray-50 focus:bg-white transition-colors">
                <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl text-[13px] md:text-[14px] font-medium active:scale-95 transition md:w-auto w-full">Simpan</button>
            </form>
        </div>

        <div class="flex justify-between items-center mb-4 md:mb-5 px-1 md:px-0">
            <h2 class="text-[16px] md:text-[18px] font-semibold text-gray-900">Etalase Produk</h2>
            <span class="text-[12px] md:text-[13px] text-gray-500">{{ $products->count() }} Produk</span>
        </div>

        <div class="space-y-3 md:hidden">
            @forelse($products as $product)
                @php
                    $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya:* \n" . url('/marketplace/' . $product->id);
                @endphp
                <div class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] flex gap-3">
                    
                    <div class="w-20 h-20 rounded-xl bg-gray-50 shrink-0 border border-gray-100 overflow-hidden relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        @if($product->is_sold)
                            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center">
                                <span class="bg-gray-900 text-white px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider">Habis</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-0.5">
                        <div>
                            <a href="/marketplace/{{ $product->id }}" class="text-[13px] font-medium text-gray-900 leading-snug line-clamp-2">{{ $product->item_name }}</a>
                            <div class="text-[14px] font-bold text-red-600 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ $product->views_count }}
                                </span>
                            </div>
                            
                            <div class="flex gap-1.5">
                                <a href="/marketplace/{{ $product->id }}/edit" class="w-7 h-7 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg active:scale-90 transition" title="Edit Produk">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" data-id="{{ $product->id }}" data-pesan="{{ $defaultPesan }}" onclick="openBroadcastModal(this)" class="w-7 h-7 flex items-center justify-center bg-green-50 text-green-600 rounded-lg active:scale-90 transition" title="Kirim ke WA">
                                    <svg class="w-3.5 h-3.5 transform -rotate-45 ml-0.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                                <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                    @csrf
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center bg-gray-50 text-gray-600 border border-gray-100 rounded-lg active:scale-90 transition" title="Ubah Status">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </button>
                                </form>
                                <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="confirmDelete(event, this)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center bg-red-50 text-red-500 rounded-lg active:scale-90 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-gray-500 text-[13px]">Belum ada produk. Yuk buka lapak!</p>
                </div>
            @endforelse
        </div>

        <div class="hidden md:block bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-[12px] uppercase tracking-wider font-medium">
                        <th class="p-4 font-medium">Produk</th>
                        <th class="p-4 font-medium">Harga</th>
                        <th class="p-4 font-medium text-center">Status</th>
                        <th class="p-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] divide-y divide-gray-50">
                    @forelse($products as $product)
                    @php
                        $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya di web:* \n" . url('/marketplace/' . $product->id);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="p-4 flex items-center gap-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <a href="/marketplace/{{ $product->id }}" class="font-semibold text-gray-900 hover:text-red-600 transition">{{ $product->item_name }}</a>
                                <div class="text-[12px] text-gray-500 font-normal">{{ $product->category }} • {{ $product->views_count }} Views</div>
                            </div>
                        </td>
                        <td class="p-4 font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-4 text-center">
                            @if($product->is_sold)
                                <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide">Habis</span>
                            @else
                                <span class="bg-green-50 text-green-600 border border-green-100 px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide">Aktif</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="/marketplace/{{ $product->id }}/edit" class="p-2 bg-white border border-gray-200 hover:bg-amber-50 text-amber-600 rounded-lg transition shadow-sm" title="Edit Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" data-id="{{ $product->id }}" data-pesan="{{ $defaultPesan }}" onclick="openBroadcastModal(this)" class="p-2 bg-white border border-gray-200 hover:bg-green-50 text-green-600 rounded-lg transition shadow-sm" title="Broadcast Iklan ke WA">
                                    <svg class="w-4 h-4 transform -rotate-45 ml-0.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                                <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 rounded-lg transition shadow-sm" title="Ubah Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </button>
                                </form>
                                <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="confirmDelete(event, this)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-gray-200 hover:bg-red-50 text-red-500 rounded-lg transition shadow-sm" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-500 font-normal">Belum ada produk jualan. Mulai buka lapak sekarang!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT PROFIL LAPAK -->
<div id="profileModal" class="hidden fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl scale-95 origin-center animate-modal-in">
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
            <div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Edit Lapak ✨</h3>
                <p class="text-xs text-gray-500 font-medium">Ubah logo dan banner jualanmu.</p>
            </div>
            <button type="button" onclick="document.getElementById('profileModal').classList.add('hidden');" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full hover:bg-gray-200 hover:text-gray-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('marketplace.updateStoreProfile') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            
            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-widest mb-2">Foto Profil Lapak Logo Baru</label>
                <input type="file" name="store_photo" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:uppercase file:tracking-wider file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                <p class="text-[10px] text-gray-400 mt-1 font-medium italic">Rekomendasi rasio 1:1 persegi. Ukuran maksimal 2MB.</p>
                @error('store_photo')
                    <div class="mt-2 text-[11px] text-red-500 font-bold bg-red-50 px-2.5 py-1 rounded inline-flex">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-widest mb-2">Banner Lapak Latar Belakang Baru</label>
                <input type="file" name="store_banner" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:uppercase file:tracking-wider file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                <p class="text-[10px] text-gray-400 mt-1 font-medium italic">Rekomendasi lanskap 4:1 memanjang. Ukuran maksimal 4MB.</p>
                @error('store_banner')
                    <div class="mt-2 text-[11px] text-red-500 font-bold bg-red-50 px-2.5 py-1 rounded inline-flex">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-2 justify-end pt-2 border-t border-gray-50 mt-2">
                <button type="button" onclick="document.getElementById('profileModal').classList.add('hidden');" class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold text-[13px] hover:bg-gray-200 transition active:scale-95">Batal</button>
                <button type="submit" class="flex-1 px-5 py-3 rounded-xl bg-red-600 text-white font-bold text-[13px] hover:bg-red-700 transition active:scale-95 shadow-[0_8px_20px_rgba(220,38,38,0.25)] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Lapak Ke Publik
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TARIK SALDO -->
<div id="wdModal" class="hidden fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl scale-95 origin-center animate-modal-in">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Tarik Saldo Lapak</h3>
            <button type="button" onclick="document.getElementById('wdModal').classList.add('hidden');" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('marketplace.withdraw') }}" method="POST" class="p-5">
            @csrf
            
            <div class="mb-4">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nominal Penarikan (Rp)</label>
                <input type="number" name="amount" min="10000" max="{{ auth()->user()->store_balance }}" placeholder="Min: 10000" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition text-sm" required>
                <div class="text-xs text-gray-500 mt-1">Saldo tersedia: Rp {{ number_format(auth()->user()->store_balance, 0, ',', '.') }}</div>
            </div>

            <div class="mb-4">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Bank / E-Wallet</label>
                <select name="bank_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition text-sm" required>
                    <option value="">Pilih Bank / E-Wallet</option>
                    <option value="DANA">DANA</option>
                    <option value="GOPAY">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="BCA">BCA</option>
                    <option value="MANDIRI">Mandiri</option>
                    <option value="BRI">BRI</option>
                    <option value="BNI">BNI</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nomor Rekening / E-Wallet</label>
                <input type="text" name="account_number" placeholder="Contoh: 08123456789" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition text-sm" required>
            </div>

            <div class="mb-5">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Atas Nama (Pemilik Rekening)</label>
                <input type="text" name="account_name" placeholder="Sesuai buku tabungan / aplikasi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition text-sm" required>
            </div>

            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('wdModal').classList.add('hidden');" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium text-sm hover:bg-gray-50 transition active:scale-95">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white font-medium text-sm hover:bg-emerald-600 shadow-sm transition active:scale-95">Ajukan Penarikan</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modal-in {
    0% { transform: scale(0.95); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.animate-modal-in {
    animation: modal-in 0.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
</style>

<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }
    
    // Buka kembali modal jika terdapat error validasi foto lapak
    @if($errors->has('store_photo') || $errors->has('store_banner'))
        openProfileModal();
    @endif
</script>

@endsection