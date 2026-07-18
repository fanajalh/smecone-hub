@extends('layouts.app')
@section('title', '| Lapak Saya')

@section('content')
<div class="max-w-7xl mx-auto pt-24 md:pt-32 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in font-sans text-gray-800">
    
    {{-- HEADER PROFILE LAPAK --}}
    <div class="bg-white rounded-[2rem] overflow-hidden shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 mb-8 relative group">
        
        <div class="h-36 md:h-64 w-full bg-gray-100 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none z-10"></div>
            @if(auth()->user()->store_banner)
                <img src="{{ asset('storage/' . auth()->user()->store_banner) }}" class="w-full h-full object-cover relative z-0">
            @else
                <div class="w-full h-full bg-gradient-to-br from-red-600 via-[#E21F26] to-orange-500 relative z-0"></div>
            @endif
            
            <button onclick="openProfileModal()" class="absolute top-4 right-4 md:top-6 md:right-6 bg-black/40 hover:bg-black/60 backdrop-blur-md text-white p-2 md:px-5 md:py-2.5 rounded-full transition-all active:scale-95 flex items-center gap-2 text-[13px] font-bold z-20 shadow-sm border border-white/20 tap-effect">
                <ion-icon name="camera" class="text-xl md:text-lg"></ion-icon>
                <span class="hidden md:inline">Edit Tampilan Lapak</span>
            </button>
        </div>

        <div class="px-6 md:px-10 pb-8 relative">
            <div class="flex flex-col md:flex-row md:items-end gap-4 md:gap-6 -mt-16 md:-mt-20 relative z-20 mb-6">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-[6px] border-white shadow-md shrink-0 overflow-hidden bg-white flex items-center justify-center text-gray-400 text-4xl font-black uppercase relative group-hover:shadow-lg transition-shadow">
                    @if(auth()->user()->store_photo)
                        <img src="{{ asset('storage/' . auth()->user()->store_photo) }}" class="w-full h-full object-cover">
                    @elseif(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500">
                            {{ substr(auth()->user()->store_name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <div class="pb-2 md:pb-4 flex-1">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight tracking-tight">{{ auth()->user()->store_name }}</h1>
                    <div class="flex items-center gap-1.5 text-[13px] md:text-[14px] text-green-600 font-bold mt-1 bg-green-50 w-max px-3 py-1 rounded-full border border-green-100">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        Lapak Terverifikasi
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 md:gap-3 pb-2 md:pb-4">
                    <a href="/marketplace/toko/{{ auth()->id() }}" class="flex-1 md:flex-none flex items-center justify-center gap-1.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 px-5 py-3 rounded-2xl text-[14px] font-bold transition-all active:scale-95 tap-effect">
                        <ion-icon name="storefront-outline" class="text-lg"></ion-icon> Lihat Etalase
                    </a>
                    <a href="{{ route('marketplace.sales') }}" class="w-12 h-12 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 rounded-2xl transition-all active:scale-95 tap-effect" title="Riwayat Penjualan">
                        <ion-icon name="receipt-outline" class="text-xl"></ion-icon>
                    </a>
                    <a href="{{ route('marketplace.recap') }}" class="w-12 h-12 flex items-center justify-center bg-blue-50 border border-blue-100 hover:bg-blue-100 text-blue-700 rounded-2xl transition-all active:scale-95 tap-effect" title="Rekap Penjualan">
                        <ion-icon name="analytics-outline" class="text-xl"></ion-icon>
                    </a>
                    <a href="/marketplace/create" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-[#E21F26] text-white px-6 py-3 rounded-2xl transition-all active:scale-95 font-black text-[14px] shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:shadow-[0_12px_25px_rgba(226,31,38,0.35)] hover:-translate-y-0.5 tap-effect">
                        <ion-icon name="add-circle" class="text-xl"></ion-icon>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
        <div class="bg-white p-5 md:p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-[1rem] bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                <ion-icon name="eye" class="text-2xl"></ion-icon>
            </div>
            <div>
                <div class="text-gray-400 text-[11px] md:text-[12px] font-bold uppercase tracking-widest mb-1">Total Dilihat</div>
                <div class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ number_format($totalViews) }} <span class="text-[12px] font-bold text-gray-400">kali</span></div>
            </div>
        </div>
        
        <div class="bg-white p-5 md:p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-[1rem] bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <ion-icon name="cube" class="text-2xl"></ion-icon>
            </div>
            <div>
                <div class="text-gray-400 text-[11px] md:text-[12px] font-bold uppercase tracking-widest mb-1">Produk Aktif</div>
                <div class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ $activeProducts }}</div>
            </div>
        </div>
        
        <div class="bg-white p-5 md:p-6 rounded-[1.5rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-[1rem] bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                <ion-icon name="bag-check" class="text-2xl"></ion-icon>
            </div>
            <div>
                <div class="text-gray-400 text-[11px] md:text-[12px] font-bold uppercase tracking-widest mb-1">Terjual</div>
                <div class="text-xl md:text-2xl font-black text-gray-900 leading-none">{{ $soldProducts }}</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-5 md:p-6 rounded-[1.5rem] shadow-[0_8px_25px_rgba(16,185,129,0.3)] relative overflow-hidden flex flex-col justify-center border border-emerald-400">
            <div class="absolute -right-6 -bottom-6 text-[80px] opacity-20 rotate-12">
                <ion-icon name="wallet"></ion-icon>
            </div>
            <div class="relative z-10 w-full">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-green-100 text-[11px] md:text-[12px] font-bold uppercase tracking-widest">Saldo Lapak</div>
                    <button type="button" onclick="{{ auth()->user()->store_balance >= 10000 ? "document.getElementById('wdModal').classList.remove('hidden');" : "Swal.fire({ icon: 'warning', title: 'Belum Bisa Tarik', text: 'Minimal penarikan saldo adalah Rp 10.000', confirmButtonColor: '#10B981' });" }}" class="bg-white text-emerald-600 text-[11px] font-black px-3 py-1.5 rounded-lg shadow-sm transition active:scale-95 tap-effect flex items-center gap-1">
                        <ion-icon name="cash-outline"></ion-icon> Tarik
                    </button>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white leading-none tracking-tight">Rp {{ number_format(auth()->user()->store_balance, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- WA CONFIG --}}
    <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-5 md:p-7 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-green-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-[1rem] bg-green-50 flex items-center justify-center text-green-500 shrink-0 border border-green-100">
                <ion-icon name="logo-whatsapp" class="text-[26px]"></ion-icon>
            </div>
            <div>
                <h3 class="text-[15px] md:text-[17px] font-black text-gray-900 leading-tight">Nomor WhatsApp Toko</h3>
                <p class="text-[12px] md:text-[13px] text-gray-500 font-medium mt-0.5">Digunakan untuk menerima notifikasi orderan / pesan dari pembeli.</p>
            </div>
        </div>
        
        <form action="{{ route('marketplace.updateWa') }}" method="POST" class="flex flex-col md:flex-row gap-3 w-full md:w-auto relative z-10">
            @csrf
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-[14px] font-black text-gray-400">+62</span>
                </div>
                <input type="text" name="whatsapp_number" value="{{ substr(auth()->user()->whatsapp_number, 2) }}" placeholder="8123456789" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 text-[14px] font-bold text-gray-900 bg-gray-50 focus:bg-white transition-all">
            </div>
            <button type="submit" class="bg-gray-900 text-white px-6 py-3.5 rounded-xl text-[14px] font-bold active:scale-95 tap-effect transition shadow-md md:w-auto w-full flex items-center justify-center gap-2">
                <ion-icon name="save-outline" class="text-lg"></ion-icon> Simpan
            </button>
        </form>
    </div>

    {{-- ETALASE PRODUK --}}
    <div class="flex justify-between items-end mb-5 px-1 md:px-0">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Etalase Produk</h2>
            <p class="text-[13px] font-bold text-gray-500 mt-0.5">Kelola barang jualan dan jasa kamu.</p>
        </div>
        <div class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-[12px] font-bold border border-gray-200">
            {{ $products->count() }} Produk
        </div>
    </div>

    {{-- MOBILE LIST VIEW --}}
    <div class="space-y-4 md:hidden">
        @forelse($products as $product)
            @php
                $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya:* \n" . url('/marketplace/' . $product->id);
            @endphp
            <div class="bg-white p-4 rounded-[1.5rem] border border-gray-100 shadow-[0_4px_15px_rgba(0,0,0,0.03)] flex gap-4">
                
                <div class="w-24 h-24 rounded-[1rem] bg-gray-50 shrink-0 border border-gray-100 overflow-hidden relative">
                    @if($product->image)
                        @php 
                            $decoded = json_decode($product->image, true);
                            $firstImage = is_array($decoded) ? $decoded[0] : $product->image;
                        @endphp
                        <img src="{{ asset('storage/' . $firstImage) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <ion-icon name="image-outline" class="text-3xl"></ion-icon>
                        </div>
                    @endif
                    @if($product->is_sold)
                        <div class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center">
                            <span class="bg-gray-900 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md border border-gray-700">Habis</span>
                        </div>
                    @endif
                </div>

                <div class="flex-1 flex flex-col justify-between py-1">
                    <div>
                        <a href="/marketplace/{{ $product->id }}" class="text-[14px] font-bold text-gray-900 leading-snug line-clamp-2 hover:text-[#E21F26] transition-colors">{{ $product->item_name }}</a>
                        <div class="text-[15px] font-black text-[#E21F26] mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 flex items-center gap-1">
                                <ion-icon name="eye-outline"></ion-icon> {{ $product->views_count }}
                            </span>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="/marketplace/{{ $product->id }}/edit" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 rounded-xl tap-effect" title="Edit Produk">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                            <a href="{{ route('marketplace.broadcast', $product->id) }}" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 border border-green-100 rounded-xl tap-effect" title="Kirim ke WA">
                                <ion-icon name="paper-plane-outline"></ion-icon>
                            </a>
                            <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                @csrf
                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-600 border border-gray-200 rounded-xl tap-effect" title="Ubah Status">
                                    <ion-icon name="swap-horizontal-outline"></ion-icon>
                                </button>
                            </form>
                            <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 border border-red-100 rounded-xl tap-effect">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-[2rem] border border-dashed border-gray-300 shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <ion-icon name="storefront-outline" class="text-4xl text-gray-400"></ion-icon>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-1">Belum ada produk</h3>
                <p class="text-gray-500 text-[13px] font-medium mb-5">Yuk tambah barang jualan pertamamu!</p>
                <a href="/marketplace/create" class="inline-flex items-center gap-2 bg-[#E21F26] text-white px-6 py-3 rounded-xl font-bold text-[14px] shadow-md hover:bg-red-700 transition">
                    <ion-icon name="add"></ion-icon> Tambah Produk
                </a>
            </div>
        @endforelse
    </div>

    {{-- DESKTOP TABLE VIEW --}}
    <div class="hidden md:block bg-white rounded-[2rem] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-gray-400 text-[11px] uppercase tracking-widest font-black border-b border-gray-100">
                    <th class="px-6 py-5">Produk</th>
                    <th class="px-6 py-5">Harga</th>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[14px] divide-y divide-gray-50">
                @forelse($products as $product)
                @php
                    $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya di web:* \n" . url('/marketplace/' . $product->id);
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4 flex items-center gap-5">
                        @if($product->image)
                            @php 
                                $decoded = json_decode($product->image, true);
                                $firstImage = is_array($decoded) ? $decoded[0] : $product->image;
                            @endphp
                            <img src="{{ asset('storage/' . $firstImage) }}" class="w-16 h-16 rounded-[1rem] object-cover border border-gray-100 shadow-sm group-hover:scale-105 transition-transform">
                        @else
                            <div class="w-16 h-16 rounded-[1rem] bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                <ion-icon name="image-outline" class="text-2xl"></ion-icon>
                            </div>
                        @endif
                        <div>
                            <a href="/marketplace/{{ $product->id }}" class="font-black text-[15px] text-gray-900 hover:text-[#E21F26] transition-colors leading-tight">{{ $product->item_name }}</a>
                            <div class="text-[13px] text-gray-500 font-bold mt-1 flex items-center gap-1.5">
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[11px] uppercase tracking-wider">{{ $product->category }}</span>
                                <span>&bull;</span>
                                <ion-icon name="eye-outline" class="text-gray-400"></ion-icon> {{ $product->views_count }} Views
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-black text-gray-900 text-[15px]">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($product->is_sold)
                            <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-widest shadow-sm">Habis</span>
                        @else
                            <span class="bg-green-50 text-green-600 border border-green-100 px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-widest shadow-sm">Tersedia</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2.5 transition-opacity">
                            <a href="/marketplace/{{ $product->id }}/edit" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 text-gray-400 hover:text-blue-600 rounded-xl transition shadow-sm tap-effect" title="Edit Produk">
                                <ion-icon name="create-outline" class="text-lg"></ion-icon>
                            </a>
                            <a href="{{ route('marketplace.broadcast', $product->id) }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 hover:border-green-300 hover:bg-green-50 text-gray-400 hover:text-green-600 rounded-xl transition shadow-sm tap-effect" title="Broadcast Iklan ke WA">
                                <ion-icon name="paper-plane-outline" class="text-lg"></ion-icon>
                            </a>
                            <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                @csrf
                                <button type="submit" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-100 text-gray-400 hover:text-gray-700 rounded-xl transition shadow-sm tap-effect" title="Ubah Status">
                                    <ion-icon name="swap-horizontal-outline" class="text-lg"></ion-icon>
                                </button>
                            </form>
                            <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-xl transition shadow-sm tap-effect" title="Hapus">
                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-16 px-6 text-center text-gray-500">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <ion-icon name="storefront-outline" class="text-4xl text-gray-300"></ion-icon>
                        </div>
                        <p class="font-bold mb-4">Belum ada produk jualan.</p>
                        <a href="/marketplace/create" class="inline-flex items-center gap-2 bg-[#E21F26] text-white px-5 py-2.5 rounded-xl font-bold text-[13px] shadow-sm hover:bg-red-700 transition">
                            Mulai Buka Lapak
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
        <form action="{{ route('marketplace.updateStoreProfile') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" x-data="{ photoPreview: null, bannerPreview: null }">
            @csrf
            
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5">Foto Profil / Logo Lapak</label>
                <div class="relative group border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-red-400 hover:bg-red-50/50 transition-all cursor-pointer overflow-hidden">
                    <input type="file" name="store_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           @change="const file = $event.target.files[0]; if(file) { photoPreview = URL.createObjectURL(file); } else { photoPreview = null; }">
                    
                    <div x-show="!photoPreview" class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-400 group-hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600 transition-colors">Pilih Foto (Rasio 1:1)</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Maksimal 2MB</p>
                    </div>
                    
                    <div x-show="photoPreview" style="display: none;" class="flex flex-col items-center">
                        <img :src="photoPreview" class="w-20 h-20 object-cover rounded-full border-4 border-white shadow-md mb-2">
                        <p class="text-[12px] font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">Foto Terpilih</p>
                    </div>
                </div>
                @error('store_photo')
                    <div class="mt-2 text-[11px] text-red-500 font-bold bg-red-50 px-2.5 py-1 rounded-lg inline-flex">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5">Banner Latar Belakang</label>
                <div class="relative group border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-red-400 hover:bg-red-50/50 transition-all cursor-pointer overflow-hidden">
                    <input type="file" name="store_banner" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           @change="const file = $event.target.files[0]; if(file) { bannerPreview = URL.createObjectURL(file); } else { bannerPreview = null; }">
                    
                    <div x-show="!bannerPreview" class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-400 group-hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600 transition-colors">Pilih Banner (Rasio 4:1)</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Maksimal 4MB</p>
                    </div>
                    
                    <div x-show="bannerPreview" style="display: none;" class="flex flex-col items-center w-full">
                        <img :src="bannerPreview" class="w-full h-24 object-cover rounded-xl border-2 border-white shadow-sm mb-3">
                        <p class="text-[12px] font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full w-max mx-auto">Banner Terpilih</p>
                    </div>
                </div>
                @error('store_banner')
                    <div class="mt-2 text-[11px] text-red-500 font-bold bg-red-50 px-2.5 py-1 rounded-lg inline-flex">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100/50 mt-4">
                <button type="button" onclick="document.getElementById('profileModal').classList.add('hidden');" class="flex-1 px-5 py-4 rounded-[20px] bg-gray-100 text-gray-700 font-bold text-[14px] hover:bg-gray-200 transition-all active:scale-[0.98] tap-effect">Batal</button>
                <button type="submit" class="flex-[2] px-5 py-4 rounded-[20px] bg-red-600 text-white font-black text-[14px] hover:bg-red-700 transition-all active:scale-[0.98] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:shadow-[0_12px_25px_rgba(220,38,38,0.35)] hover:-translate-y-0.5 flex items-center justify-center gap-2 tap-effect">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
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