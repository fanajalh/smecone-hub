@extends('layouts.app')
@section('title', '| Riwayat Pembelian')

@section('content')
<style>
    /* Menyembunyikan scrollbar tapi tetap bisa scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    /* Efek tekan tombol */
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s; }

    /* Mencegah Alpine.js berkedip saat pertama diload */
    [x-cloak] { display: none !important; }
</style>

<div class="bg-[#F6F8FA] min-h-screen pt-24 md:pt-32 pb-20 font-sans text-gray-800 selection:bg-red-100 selection:text-red-900 animate-page-in">
    
    {{-- Header Section --}}
    <div class="bg-white px-5 py-5 rounded-[2rem] shadow-[0_2px_15px_rgba(0,0,0,0.02)] mb-5 md:max-w-4xl md:mx-auto border border-gray-100 mx-4 md:mx-auto">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('marketplace.index') }}" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 hover:text-[#E21F26] hover:bg-red-50 transition tap-effect shrink-0">
                    <ion-icon name="arrow-back" class="text-xl"></ion-icon>
                </a>
                <div>
                    <h2 class="text-[18px] md:text-[22px] font-extrabold text-gray-900 tracking-tight leading-tight">Riwayat Belanja</h2>
                    <p class="text-[11px] md:text-[13px] text-gray-500 font-medium">Pantau status jajan dan pesananmu</p>
                </div>
            </div>
            <a href="{{ route('marketplace.index') }}" class="bg-red-50 text-[#E21F26] font-bold px-4 py-2.5 rounded-xl text-[11px] md:text-[12px] hover:bg-red-100 transition flex items-center gap-2 tap-effect shadow-sm shrink-0">
                Smecone Mart
                <ion-icon name="chevron-forward" class="text-sm"></ion-icon>
            </a>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        @forelse($purchases as $date => $dailyPurchases)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-1.5 h-4 bg-red-500 rounded-full mr-2"></div>
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                </h3>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5">
                @foreach($dailyPurchases as $purchase)
        
        {{-- BUNGKUS ALPINE JS (Isolasi Modal Per Item) --}}
        <div x-data="{ showModal: false }">
            
            {{-- ================= 1. SIMPLE CARD (List Awal) ================= --}}
            <div class="bg-white rounded-[1.5rem] p-4 shadow-[0_4px_15px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-red-100 transition-colors">
                
                {{-- ID & Status --}}
                <div class="flex justify-between items-center mb-3">
                    <span class="font-mono font-bold text-gray-500 text-[12px]">#TRX-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</span>
                    @php
                        $statusColor = 'bg-gray-50 text-gray-600 border-gray-200';
                        $statusText = $purchase->status;
                        if($purchase->status == 'PAID' || $purchase->status == 'SELESAI') {
                            $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                            $statusText = $purchase->status == 'PAID' ? 'Berhasil' : 'Selesai';
                        } elseif($purchase->status == 'DIBATALKAN') {
                            $statusColor = 'bg-red-50 text-[#E21F26] border-red-100';
                        } elseif($purchase->status == 'DIPROSES' || $purchase->status == 'PENDING') {
                            $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-100';
                        }
                    @endphp
                    <span class="px-2.5 py-1 rounded-[0.5rem] text-[10px] font-extrabold uppercase border {{ $statusColor }}">
                        {{ $statusText }}
                    </span>
                </div>

                {{-- Detail Item Pendek --}}
                <div class="flex gap-3 items-center mb-4">
                    @if(isset($purchase->marketplaceItem->image))
                        <div class="w-14 h-14 rounded-xl bg-gray-50 shrink-0 overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $purchase->marketplaceItem->image) }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-14 h-14 rounded-xl bg-gray-50 shrink-0 flex items-center justify-center text-gray-300 border border-gray-100">
                            <ion-icon name="image-outline" class="text-2xl"></ion-icon>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-[14px] text-gray-900 leading-tight line-clamp-1 mb-0.5">{{ $purchase->marketplaceItem->item_name ?? 'Produk Dihapus' }}</h3>
                        @if($purchase->variant_selected)
                            <span class="text-[10px] font-black text-white bg-blue-500 rounded px-1.5 py-0.5 mb-1 inline-block">{{ $purchase->variant_selected }}</span>
                        @endif
                        <p class="text-[#E21F26] font-black text-[15px]">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-50 pt-3 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500 overflow-hidden border border-gray-200">
                            {{ substr($purchase->marketplaceItem->user->store_name ?? $purchase->marketplaceItem->user->name ?? '?', 0, 1) }}
                        </div>
                        <span class="text-[12px] font-medium text-gray-600 truncate max-w-[100px]">{{ $purchase->marketplaceItem->user->store_name ?? explode(' ', trim($purchase->marketplaceItem->user->name ?? 'Penjual'))[0] }}</span>
                    </div>
                    
                    <button @click="showModal = true" class="bg-gray-900 text-white hover:bg-[#E21F26] px-4 py-2 rounded-xl text-[12px] font-bold transition-colors tap-effect flex items-center gap-1.5 shadow-sm">
                        <ion-icon name="eye" class="text-sm"></ion-icon>
                        Lihat Detail
                    </button>
                </div>
            </div>

            {{-- ================= 2. POP-UP MODAL STRUK (TENGAH LAYAR) ================= --}}
            <div x-show="showModal" x-cloak style="display: none;" class="fixed inset-0 z-[100] flex justify-center items-center px-4 pb-safe">
                
                {{-- Latar Gelap Blur --}}
                <div x-show="showModal" 
                     x-transition.opacity.duration.300ms
                     class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" 
                     @click="showModal = false"></div>
                
                {{-- Container Modal Tepat di Tengah Layar --}}
                <div class="relative z-10 w-full max-w-[360px] flex flex-col items-center">
                    
                    {{-- Celah Mesin Printer --}}
                    <div x-show="showModal" x-transition.opacity.duration.300ms class="w-[90%] h-3 bg-gray-800 rounded-t-lg shadow-md border-b-2 border-gray-900 relative z-20 shrink-0">
                        <div class="absolute right-3 top-1 w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_5px_rgba(74,222,128,0.8)]"></div>
                    </div>
                    
                    {{-- Area Scrollable Struk --}}
                    <div class="w-full relative z-10 overflow-hidden flex flex-col items-center max-h-[85vh] rounded-b-[2rem]">
                        
                        {{-- KERTAS STRUK & TOMBOL YANG BERGERAK --}}
                        <div x-show="showModal"
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 transform -translate-y-8"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-8"
                             class="w-full flex flex-col items-center overflow-y-auto hide-scrollbar pb-2">
                            
                            {{-- Kertas Struk --}}
                            <div class="relative w-[94%] bg-white filter drop-shadow-[0_8px_16px_rgba(0,0,0,0.15)]">
                                
                                {{-- Zigzag Atas --}}
                                <div class="w-full h-2 flex overflow-hidden">
                                    @for ($i = 0; $i < 30; $i++)
                                        <div class="w-3 h-3 bg-white transform rotate-45 -mt-1.5 shrink-0"></div>
                                    @endfor
                                </div>

                                {{-- Isi Struk --}}
                                <div class="px-5 py-4 relative">
                                    {{-- Header --}}
                                    <div class="text-center mb-4 border-b-2 border-dashed border-gray-200 pb-4">
                                        <div class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-[#E21F26] rounded-full mb-2 border border-red-100">
                                            <ion-icon name="receipt" class="text-xl"></ion-icon>
                                        </div>
                                        <h3 class="font-extrabold text-[15px] text-gray-900 tracking-wider uppercase">SMECONE MART</h3>
                                        <p class="text-[10px] font-mono text-gray-500 mt-0.5">BUKTI TRANSAKSI PEMBELIAN</p>
                                        
                                        <div class="mt-3 text-[11px] font-mono text-gray-600 flex justify-between items-center">
                                            <span>#TRX-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            <span>{{ $purchase->created_at->format('d/m/y H:i') }}</span>
                                        </div>
                                    </div>

                                    {{-- Detail Item --}}
                                    <div class="mb-4 pb-4 border-b-2 border-dashed border-gray-200">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Detail Pesanan</p>
                                        <div class="flex items-start justify-between gap-3 font-mono text-[12px]">
                                            <div class="flex-grow">
                                                <p class="font-bold text-gray-900 leading-tight">{{ $purchase->marketplaceItem->item_name ?? 'Produk Dihapus' }}</p>
                                                @if($purchase->variant_selected)
                                                    <p class="text-[10px] text-blue-600 font-bold mt-0.5">Varian: {{ $purchase->variant_selected }}</p>
                                                @endif
                                                <p class="text-[10px] text-gray-500 mt-1">{{ $purchase->qty }} x Rp {{ number_format($purchase->amount / max($purchase->qty, 1), 0, ',', '.') }}</p>
                                            </div>
                                            <p class="font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    {{-- Info Penjual & Harga --}}
                                    <div class="mb-4 pb-4 border-b-2 border-dashed border-gray-200 font-mono text-[11px] space-y-1.5">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Penjual</span>
                                            <span class="font-bold text-gray-900 truncate max-w-[150px] text-right">{{ $purchase->marketplaceItem->user->store_name ?? $purchase->marketplaceItem->user->name ?? 'Toko Anonim' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Metode</span>
                                            <span class="font-bold text-gray-900 uppercase">{{ $purchase->payment_method ?? 'QRIS' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2 pt-2">
                                            <span class="text-gray-900 font-bold text-[13px]">TOTAL</span>
                                            <span class="font-black text-[16px] text-[#E21F26]">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    {{-- Stempel Status --}}
                                    <div class="flex justify-center mb-4 mt-2">
                                        @php
                                            $stampColor = 'border-gray-300 text-gray-400';
                                            $statusText = $purchase->status;
                                            if($purchase->status == 'PAID' || $purchase->status == 'SELESAI') {
                                                $stampColor = 'border-emerald-500 text-emerald-600';
                                                $statusText = $purchase->status == 'PAID' ? 'BERHASIL' : 'SELESAI';
                                            } elseif($purchase->status == 'DIBATALKAN') {
                                                $stampColor = 'border-[#E21F26] text-[#E21F26]';
                                            } elseif($purchase->status == 'DIPROSES' || $purchase->status == 'PENDING') {
                                                $stampColor = 'border-yellow-500 text-yellow-600';
                                            }
                                        @endphp
                                        <div class="px-5 py-1.5 border-4 {{ $stampColor }} rounded-md transform -rotate-12 opacity-80">
                                            <p class="font-black text-[16px] tracking-widest uppercase">{{ $statusText }}</p>
                                        </div>
                                    </div>

                                    {{-- Barcode --}}
                                    <div class="text-center">
                                        <div class="flex justify-center gap-1 h-6 opacity-40 mb-1.5">
                                            <div class="w-1 bg-gray-800"></div><div class="w-2 bg-gray-800"></div><div class="w-1 bg-gray-800"></div>
                                            <div class="w-3 bg-gray-800"></div><div class="w-1 bg-gray-800"></div><div class="w-2 bg-gray-800"></div>
                                            <div class="w-1 bg-gray-800"></div><div class="w-4 bg-gray-800"></div><div class="w-1 bg-gray-800"></div>
                                        </div>
                                        <p class="text-[8px] font-mono text-gray-400">SIMPAN STRUK INI SEBAGAI BUKTI</p>
                                    </div>
                                </div>

                                {{-- Zigzag Bawah --}}
                                <div class="w-full h-2 flex overflow-hidden">
                                    @for ($i = 0; $i < 30; $i++)
                                        <div class="w-3 h-3 bg-white transform rotate-45 mt-1 shrink-0"></div>
                                    @endfor
                                </div>
                            </div>
                            
                            {{-- ================= ACTION BUTTONS BAWAH STRUK ================= --}}
                            <div class="mt-4 flex flex-col gap-2 w-[94%] mx-auto">
                                
                                @php 
                                    $sellerWa = $purchase->marketplaceItem->user->whatsapp_number ?? null; 
                                @endphp
                                
                                {{-- Tombol WhatsApp --}}
                                @if($sellerWa && $purchase->status == 'PAID')
                                    @php
                                        $cleanWa = preg_replace('/^0/', '62', $sellerWa);
                                        $defaultMsg = urlencode("Halo kak, saya " . auth()->user()->name . " yang baru saja order *" . ($purchase->marketplaceItem->item_name ?? 'Barang') . "* (TRX-{$purchase->id}). Kapan barangnya bisa saya ambil?");
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanWa }}?text={{ $defaultMsg }}" target="_blank" class="w-full bg-emerald-500 text-white hover:bg-emerald-600 text-[13px] font-bold py-3.5 rounded-[1rem] flex justify-center items-center gap-2 transition-all shadow-md tap-effect">
                                        <ion-icon name="logo-whatsapp" class="text-lg"></ion-icon>
                                        Hubungi Penjual
                                    </a>
                                @elseif($purchase->status != 'PENDING' && $purchase->status != 'DIBATALKAN')
                                    <button disabled class="w-full bg-gray-50 text-gray-400 text-[13px] font-bold py-3.5 rounded-[1rem] cursor-not-allowed border border-gray-100">
                                        Penjual Tidak Ada WA
                                    </button>
                                @endif

                                <div class="flex gap-2 w-full">
                                    {{-- Form Batalkan Pesanan (Hanya jika PENDING) --}}
                                    @if($purchase->status == 'PENDING')
                                        <form action="{{ route('marketplace.transaction.status', $purchase->id) }}" method="POST" class="flex-1" onsubmit="confirmSubmit(event, 'Batalkan Pesanan?', 'Yakin ingin membatalkan pesanan ini? Pesanan yang dibatalkan tidak bisa dikembalikan.', 'Ya, Batalkan')">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="DIBATALKAN">
                                            <button type="submit" class="w-full bg-white text-orange-600 border border-orange-200 hover:bg-orange-50 text-[12px] font-bold py-0 rounded-xl flex justify-center items-center transition-colors shadow-sm h-[44px] tap-effect">
                                                Batalkan Pesanan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Form Hapus Riwayat --}}
                                    @if(in_array($purchase->status, ['DIBATALKAN', 'SELESAI', 'PENDING']))
                                        <form action="{{ route('marketplace.transaction.destroy', $purchase->id) }}" method="POST" class="{{ $purchase->status == 'PENDING' ? 'w-auto' : 'flex-1' }}" onsubmit="confirmSubmit(event, 'Buang Struk?', 'Yakin ingin menghapus riwayat pesanan ini secara permanen?', 'Ya, Buang')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full bg-white text-red-600 border border-red-200 hover:bg-[#E21F26] hover:text-white hover:border-[#E21F26] text-[12px] font-bold px-3 rounded-xl flex justify-center items-center gap-1.5 transition-colors shadow-sm h-[44px] tap-effect group" title="Hapus Riwayat">
                                                @if($purchase->status == 'PENDING')
                                                    <ion-icon name="trash-outline" class="text-lg group-hover:text-white transition-colors"></ion-icon>
                                                @else
                                                    <ion-icon name="trash-outline" class="text-lg group-hover:text-white transition-colors"></ion-icon>
                                                    Buang Struk
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                {{-- Tombol Tutup --}}
                                <button @click="showModal = false" class="w-full bg-gray-200/60 text-gray-700 hover:bg-gray-300 text-[13px] font-bold py-3.5 rounded-[1rem] transition-colors mt-1 tap-effect">
                                    Tutup
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        {{-- AKHIR BUNGKUS ALPINE JS --}}
                @endforeach
            </div>
        </div>
        
        @empty
        <div class="col-span-full bg-gradient-to-br from-white to-gray-50 py-20 px-6 text-center rounded-[2.5rem] border border-gray-100 shadow-sm ticket-gradient">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100 text-gray-300">
                <ion-icon name="cart-outline" class="text-5xl"></ion-icon>
            </div>
            <h3 class="text-gray-900 font-extrabold text-xl mb-2">Belum ada pesanan</h3>
            <p class="text-[14px] text-gray-500 font-medium max-w-sm mx-auto">Yuk, mulai jajan dan dukung karya teman-temanmu di Smecone Mart!</p>
            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 mt-8 bg-[#E21F26] text-white font-bold text-[14px] px-8 py-3.5 rounded-2xl shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:bg-red-700 hover:-translate-y-0.5 transition-all tap-effect">
                Mulai Belanja
                <ion-icon name="arrow-forward"></ion-icon>
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- Alpine.js & SweetAlert2 sudah di-load di layouts/app.blade.php --}}
@endsection