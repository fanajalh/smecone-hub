@extends('layouts.app')
@section('title', '| Riwayat Penjualan')

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

<div class="bg-[#F6F8FA] min-h-screen pb-20 font-sans text-gray-800 selection:bg-red-100 selection:text-red-900 animate-page-in">
    
    {{-- Header Section (Padding Diperkecil agar proporsional) --}}
    <div class="bg-white px-5 pt-4 pb-4 rounded-b-[2rem] shadow-[0_2px_15px_rgba(0,0,0,0.02)] mb-5 md:max-w-4xl md:mx-auto md:rounded-[2rem] md:mt-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="/marketplace" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 hover:text-[#E21F26] hover:bg-red-50 transition tap-effect shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-[18px] md:text-[22px] font-extrabold text-gray-900 tracking-tight leading-tight">Riwayat Penjualan</h2>
                    <p class="text-[11px] md:text-[13px] text-gray-500 font-medium">Kelola pesanan lapakmu</p>
                </div>
            </div>
            <a href="/marketplace/lapak-saya" class="bg-red-50 text-[#E21F26] font-bold px-3 py-2 rounded-xl text-[11px] md:text-[12px] hover:bg-red-100 transition flex items-center gap-1 tap-effect shadow-sm shrink-0">
                Lapak Saya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 md:px-6">
        @forelse($sales as $date => $dailySales)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-1.5 h-4 bg-red-500 rounded-full mr-2"></div>
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                </h3>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5">
                @foreach($dailySales as $sale)
        
        {{-- BUNGKUS ALPINE JS (Isolasi Modal Per Item) --}}
        <div x-data="{ showModal: false }">
            
            {{-- ================= 1. SIMPLE CARD (List Awal) ================= --}}
            <div class="bg-white rounded-[1.5rem] p-4 shadow-[0_4px_15px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-red-100 transition-colors">
                
                {{-- ID & Status --}}
                <div class="flex justify-between items-center mb-3">
                    <div class="flex flex-col gap-1">
                        <span class="font-mono font-bold text-gray-500 text-[12px]">#TRX-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span>
                        @if($sale->marketplaceItem->format === 'Digital' && in_array($sale->status, ['PAID', 'SELESAI']))
                            <span class="inline-flex w-max px-2 py-0.5 rounded shadow-sm text-[9px] font-extrabold uppercase border bg-indigo-50 text-indigo-600 border-indigo-100 items-center justify-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Sent via Email
                            </span>
                        @endif
                    </div>
                    @php
                        $statusColor = 'bg-gray-50 text-gray-600 border-gray-200';
                        $statusText = $sale->status;
                        if($sale->status == 'PAID' || $sale->status == 'SELESAI') {
                            $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                            $statusText = $sale->status == 'PAID' ? 'Lunas' : 'Selesai';
                        } elseif($sale->status == 'DIBATALKAN') {
                            $statusColor = 'bg-red-50 text-[#E21F26] border-red-100';
                        } elseif($sale->status == 'DIPROSES' || $sale->status == 'PENDING') {
                            $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-100';
                        }
                    @endphp
                    <span class="px-2.5 py-1 rounded-[0.5rem] text-[10px] font-extrabold uppercase border {{ $statusColor }}">
                        {{ $statusText }}
                    </span>
                </div>

                {{-- Detail Item Pendek --}}
                <div class="flex gap-3 items-center mb-4">
                    @if(isset($sale->marketplaceItem->image))
                        <div class="w-14 h-14 rounded-xl bg-gray-50 shrink-0 overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $sale->marketplaceItem->image) }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-14 h-14 rounded-xl bg-gray-50 shrink-0 flex items-center justify-center text-gray-300 border border-gray-100">
                            <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-[14px] text-gray-900 leading-tight line-clamp-1 mb-0.5">{{ $sale->marketplaceItem->item_name ?? 'Item Dihapus' }}</h3>
                        @if($sale->variant_selected)
                            <span class="text-[10px] font-black text-white bg-blue-500 rounded px-1.5 py-0.5 mb-1 inline-block">{{ $sale->variant_selected }}</span>
                        @endif
                        <p class="text-[#E21F26] font-black text-[15px]">Rp {{ number_format($sale->amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-50 pt-3 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500 overflow-hidden">
                            @if(isset($sale->user->avatar))
                                <img src="{{ asset('storage/' . $sale->user->avatar) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($sale->user->name ?? '?', 0, 1) }}
                            @endif
                        </div>
                        <span class="text-[12px] font-medium text-gray-600">{{ explode(' ', trim($sale->user->name ?? 'Anonim'))[0] }}</span>
                    </div>
                    
                    {{-- TOMBOL TRIGGER MODAL --}}
                    <button @click="showModal = true" class="bg-gray-900 text-white hover:bg-[#E21F26] px-4 py-2 rounded-xl text-[12px] font-bold transition-colors tap-effect flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
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
                    
                    {{-- Celah Mesin Printer (Hanya muncul bersamaan overlay) --}}
                    <div x-show="showModal" x-transition.opacity.duration.300ms class="w-[90%] h-3 bg-gray-800 rounded-t-lg shadow-md border-b-2 border-gray-900 relative z-20 shrink-0">
                        <div class="absolute right-3 top-1 w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_5px_rgba(74,222,128,0.8)]"></div>
                    </div>
                    
                    {{-- Area Scrollable Struk (Membatasi overflow agar kertas seperti keluar dari lubang) --}}
                    <div class="w-full relative z-10 overflow-hidden flex flex-col items-center max-h-[85vh] rounded-b-[2rem]">
                        
                        {{-- KERTAS STRUK & TOMBOL YANG BERGERAK (Animasi Cetak) --}}
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
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                                        </div>
                                        <h3 class="font-extrabold text-[15px] text-gray-900 tracking-wider uppercase">SMECONE MART</h3>
                                        <p class="text-[10px] font-mono text-gray-500 mt-0.5">BUKTI TRANSAKSI PENJUALAN</p>
                                        
                                        <div class="mt-3 text-[11px] font-mono text-gray-600 flex justify-between items-center">
                                            <span>#TRX-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            <span>{{ $sale->created_at->format('d/m/y H:i') }}</span>
                                        </div>
                                    </div>

                                    {{-- Detail Item --}}
                                    <div class="mb-4 pb-4 border-b-2 border-dashed border-gray-200">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Detail Item</p>
                                        <div class="flex items-start justify-between gap-3 font-mono text-[12px]">
                                            <div class="flex-grow">
                                                <p class="font-bold text-gray-900 leading-tight">{{ $sale->marketplaceItem->item_name ?? 'Produk Dihapus' }}</p>
                                                @if($sale->variant_selected)
                                                    <p class="text-[10px] text-blue-600 font-bold mt-0.5">Varian: {{ $sale->variant_selected }}</p>
                                                @endif
                                                <p class="text-[10px] text-gray-500 mt-1">{{ $sale->qty }} x Rp {{ number_format($sale->amount / max($sale->qty, 1), 0, ',', '.') }}</p>
                                            </div>
                                            <p class="font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($sale->amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    {{-- Info Pembeli & Harga --}}
                                    <div class="mb-4 pb-4 border-b-2 border-dashed border-gray-200 font-mono text-[11px] space-y-1.5">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Pembeli</span>
                                            <span class="font-bold text-gray-900 truncate max-w-[150px] text-right">{{ $sale->user->name ?? 'Anonim' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Metode</span>
                                            <span class="font-bold text-gray-900 uppercase">{{ $sale->payment_method ?? 'QRIS' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2 pt-2">
                                            <span class="text-gray-900 font-bold text-[13px]">TOTAL</span>
                                            <span class="font-black text-[16px] text-[#E21F26]">Rp {{ number_format($sale->amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    {{-- Stempel Status --}}
                                    <div class="flex justify-center mb-4 mt-2">
                                        @php
                                            $stampColor = 'border-gray-300 text-gray-400';
                                            $statusText = $sale->status;
                                            if($sale->status == 'PAID' || $sale->status == 'SELESAI') {
                                                $stampColor = 'border-emerald-500 text-emerald-600';
                                                $statusText = $sale->status == 'PAID' ? 'LUNAS' : 'SELESAI';
                                            } elseif($sale->status == 'DIBATALKAN') {
                                                $stampColor = 'border-[#E21F26] text-[#E21F26]';
                                            } elseif($sale->status == 'DIPROSES' || $sale->status == 'PENDING') {
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
                                        <p class="text-[8px] font-mono text-gray-400">TERIMA KASIH TELAH BERBELANJA</p>
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
                                
                                @if($sale->whatsapp_number)
                                    @php
                                        $cleanWa = preg_replace('/^0/', '62', $sale->whatsapp_number);
                                        $defaultMsg = urlencode("Halo kak *" . ($sale->user->name ?? '') . "*, terima kasih sudah membeli *" . ($sale->marketplaceItem->item_name ?? 'barang') . "* di Smecone Mart. Pesanan kakak sudah saya siapkan, mari COD di...");
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanWa }}?text={{ $defaultMsg }}" target="_blank" class="w-full bg-emerald-500 text-white hover:bg-emerald-600 text-[13px] font-bold py-3.5 rounded-[1rem] flex justify-center items-center transition-all shadow-md tap-effect">
                                        <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                                        Hubungi via WhatsApp
                                    </a>
                                @endif

                                <div class="flex gap-2 w-full">
                                    @if(in_array($sale->status, ['PAID', 'DIPROSES']))
                                        <form action="{{ route('marketplace.transaction.status', $sale->id) }}" method="POST" class="flex-grow flex gap-2">
                                            @csrf @method('PUT')
                                            <div class="relative flex-grow">
                                                <select name="status" class="w-full appearance-none bg-white border-2 border-transparent text-gray-800 text-[13px] font-bold py-0 px-4 rounded-[1rem] focus:ring-2 focus:ring-[#E21F26] outline-none h-[44px] shadow-sm cursor-pointer">
                                                    <option value="DIPROSES" {{ $sale->status == 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="SELESAI">Selesai</option>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                </div>
                                            </div>
                                            <button type="submit" class="bg-gray-800 text-white text-[12px] font-bold px-4 rounded-[1rem] hover:bg-black transition-colors shadow-sm h-[44px] shrink-0 tap-effect">
                                                Update
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($sale->status, ['DIBATALKAN', 'SELESAI', 'PENDING']))
                                        <form action="{{ route('marketplace.transaction.destroy', $sale->id) }}" method="POST" class="{{ in_array($sale->status, ['PAID', 'DIPROSES']) ? 'shrink-0' : 'w-full' }}" onsubmit="confirmSubmit(event, 'Buang Struk?', 'Yakin ingin menghapus riwayat penjualan ini?', 'Ya, Buang')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full bg-white text-red-600 hover:bg-[#E21F26] hover:text-white text-[12px] font-bold px-3 rounded-[1rem] flex justify-center items-center transition-colors shadow-sm h-[44px] tap-effect group" title="Hapus Riwayat">
                                                @if(in_array($sale->status, ['DIBATALKAN', 'SELESAI', 'PENDING']) && !in_array($sale->status, ['PAID', 'DIPROSES']))
                                                    <svg class="w-4 h-4 mr-1.5 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Buang Struk
                                                @else
                                                    <svg class="w-5 h-5 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
        <div class="col-span-full bg-white py-20 px-6 text-center rounded-[2.5rem] border border-dashed border-gray-200 shadow-sm md:mt-10">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-gray-900 font-extrabold text-xl mb-2">Belum ada struk penjualan</h3>
            <p class="text-[14px] text-gray-500 font-medium max-w-sm mx-auto">Ayo, promosikan lapakmu ke teman-teman Smecone agar jualanmu cepat laku!</p>
            <a href="/marketplace/lapak-saya" class="inline-block mt-8 bg-[#E21F26] text-white font-bold text-[14px] px-8 py-3.5 rounded-2xl shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:bg-red-700 hover:-translate-y-0.5 transition-all tap-effect">Kelola Lapak Saya</a>
        </div>
        @endforelse
    </div>
</div>

{{-- Alpine.js & SweetAlert2 sudah di-load di layouts/app.blade.php --}}
@endsection