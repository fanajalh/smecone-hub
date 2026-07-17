@extends('layouts.app')
@section('title', '| Bukti Transaksi')

@section('content')
<style>
    /* Efek tekan tombol */
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s; }
</style>

<div class="bg-[#F6F8FA] min-h-screen py-6 sm:py-10 font-sans text-gray-800 flex flex-col items-center">
    <div class="w-full max-w-[400px] flex flex-col items-center px-4">
        
        <!-- Back Button & Title -->
        <div class="w-full flex items-center justify-between mb-5">
            @php
                $backUrl = $transaction->user_id === auth()->id() ? route('marketplace.purchases') : route('marketplace.sales');
            @endphp
            <a href="{{ $backUrl }}" class="flex items-center gap-2 text-xs font-black text-gray-500 hover:text-gray-900 transition tap-effect">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
            <span class="text-xs font-mono font-bold text-gray-400">#TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <!-- Celah Mesin Printer -->
        <div class="w-[90%] h-3 bg-gray-800 rounded-t-lg shadow-md border-b-2 border-gray-900 relative z-20 shrink-0">
            <div class="absolute right-3 top-1 w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_5px_rgba(74,222,128,0.8)]"></div>
        </div>

        <!-- Kertas Struk -->
        <div class="relative w-full bg-white filter drop-shadow-[0_8px_20px_rgba(0,0,0,0.06)] z-10 rounded-b-2xl">
            <!-- Zigzag Atas -->
            <div class="w-full h-2 flex overflow-hidden">
                @for ($i = 0; $i < 30; $i++)
                    <div class="w-3 h-3 bg-white transform rotate-45 -mt-1.5 shrink-0"></div>
                @endfor
            </div>

            <!-- Isi Struk -->
            <div class="px-6 py-6 relative">
                <!-- Header -->
                <div class="text-center mb-5 border-b-2 border-dashed border-gray-200 pb-5">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-red-50 text-[#E21F26] rounded-full mb-3 border border-red-100 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                    </div>
                    <h3 class="font-extrabold text-[16px] text-gray-900 tracking-wider uppercase">SMECONE MART</h3>
                    <p class="text-[10px] font-mono text-gray-500 mt-1">
                        @if($transaction->user_id === auth()->id())
                            BUKTI TRANSAKSI PEMBELIAN
                        @else
                            BUKTI TRANSAKSI PENJUALAN
                        @endif
                    </p>
                    
                    <div class="mt-4 text-xs font-mono text-gray-600 flex justify-between items-center">
                        <span>#TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span>{{ $transaction->created_at->format('d/m/y H:i') }}</span>
                    </div>
                </div>

                <!-- Detail Item -->
                <div class="mb-5 pb-5 border-b-2 border-dashed border-gray-200">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Detail Pesanan</p>
                    <div class="flex items-start justify-between gap-4 font-mono text-xs">
                        <div class="flex-grow">
                            <p class="font-bold text-gray-900 leading-tight">{{ $transaction->marketplaceItem->item_name ?? 'Produk Dihapus' }}</p>
                            @if($transaction->variant_selected)
                                <p class="text-[10px] text-blue-600 font-bold mt-1">Varian: {{ $transaction->variant_selected }}</p>
                            @endif
                            <p class="text-[10px] text-gray-500 mt-2">{{ $transaction->qty }} x Rp {{ number_format($transaction->amount / max($transaction->qty, 1), 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Info Penjual/Pembeli & Harga -->
                <div class="mb-5 pb-5 border-b-2 border-dashed border-gray-200 font-mono text-xs space-y-2">
                    @if($transaction->user_id === auth()->id())
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Penjual</span>
                            <span class="font-bold text-gray-900 truncate max-w-[180px] text-right">{{ $transaction->marketplaceItem->user->store_name ?? $transaction->marketplaceItem->user->name ?? 'Toko Anonim' }}</span>
                        </div>
                    @else
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Pembeli</span>
                            <span class="font-bold text-gray-900 truncate max-w-[180px] text-right">{{ $transaction->user->name ?? 'Pembeli Anonim' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">WA Pembeli</span>
                            <span class="font-bold text-gray-900 truncate max-w-[180px] text-right">+{{ $transaction->whatsapp_number }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-bold text-gray-900 uppercase">{{ $transaction->payment_method ?? 'QRIS' }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-3">
                        <span class="text-gray-900 font-bold text-xs uppercase tracking-widest">TOTAL</span>
                        <span class="font-black text-lg text-[#E21F26]">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Stempel Status -->
                <div class="flex justify-center mb-5 mt-3">
                    @php
                        $stampColor = 'border-gray-300 text-gray-400';
                        $statusText = $transaction->status;
                        if($transaction->status == 'PAID' || $transaction->status == 'SELESAI') {
                            $stampColor = 'border-emerald-500 text-emerald-600';
                            $statusText = $transaction->status == 'PAID' ? 'BERHASIL' : 'SELESAI';
                        } elseif($transaction->status == 'DIBATALKAN') {
                            $stampColor = 'border-[#E21F26] text-[#E21F26]';
                        } elseif($transaction->status == 'DIPROSES' || $transaction->status == 'PENDING') {
                            $stampColor = 'border-yellow-500 text-yellow-600';
                        }
                    @endphp
                    <div class="px-6 py-2 border-4 {{ $stampColor }} rounded-xl transform -rotate-12 opacity-80">
                        <p class="font-black text-lg tracking-widest uppercase">{{ $statusText }}</p>
                    </div>
                </div>

                <!-- Barcode -->
                <div class="text-center">
                    <div class="flex justify-center gap-1 h-6 opacity-30 mb-2">
                        <div class="w-1 bg-gray-800"></div><div class="w-2 bg-gray-800"></div><div class="w-1 bg-gray-800"></div>
                        <div class="w-3 bg-gray-800"></div><div class="w-1 bg-gray-800"></div><div class="w-2 bg-gray-800"></div>
                        <div class="w-1 bg-gray-800"></div><div class="w-4 bg-gray-800"></div><div class="w-1 bg-gray-800"></div>
                    </div>
                    <p class="text-[8px] font-mono text-gray-400">SIMPAN STRUK INI SEBAGAI BUKTI</p>
                </div>
            </div>

            <!-- Zigzag Bawah -->
            <div class="w-full h-2 flex overflow-hidden">
                @for ($i = 0; $i < 30; $i++)
                    <div class="w-3 h-3 bg-white transform rotate-45 mt-1 shrink-0"></div>
                @endfor
            </div>
        </div>

        <!-- Action Buttons Below Struk -->
        <div class="mt-6 flex flex-col gap-2.5 w-full">
            
            {{-- Hubungi WhatsApp (Buyer View: Hubungi Penjual) --}}
            @if($transaction->user_id === auth()->id())
                @php 
                    $sellerWa = $transaction->marketplaceItem->user->whatsapp_number ?? null; 
                @endphp
                @if($sellerWa && ($transaction->status == 'PAID' || $transaction->status == 'SELESAI'))
                    @php
                        $cleanWa = preg_replace('/^0/', '62', $sellerWa);
                        $defaultMsg = urlencode("Halo kak, saya " . auth()->user()->name . " yang baru saja order *" . ($transaction->marketplaceItem->item_name ?? 'Barang') . "* (TRX-{$transaction->id}). Kapan barangnya bisa saya ambil?");
                    @endphp
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ $defaultMsg }}" target="_blank" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold py-4 rounded-2xl flex justify-center items-center transition shadow-md shadow-emerald-500/10 tap-effect">
                        <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                        Hubungi Penjual
                    </a>
                @endif
            @else
                {{-- Seller View: Hubungi Pembeli --}}
                @if($transaction->whatsapp_number && ($transaction->status == 'PAID' || $transaction->status == 'DIPROSES' || $transaction->status == 'SELESAI'))
                    @php
                        $cleanWa = preg_replace('/^0/', '62', $transaction->whatsapp_number);
                        $defaultMsg = urlencode("Halo kak *" . ($transaction->user->name ?? '') . "*, terima kasih sudah membeli *" . ($transaction->marketplaceItem->item_name ?? 'barang') . "* di Smecone Mart. Pesanan kakak sedang kami siapkan...");
                    @endphp
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ $defaultMsg }}" target="_blank" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold py-4 rounded-2xl flex justify-center items-center transition shadow-md shadow-emerald-500/10 tap-effect">
                        <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                        Hubungi Pembeli
                    </a>
                @endif
            @endif

            {{-- Form Update Status untuk Penjual --}}
            @if($transaction->marketplaceItem->user_id === auth()->id() && in_array($transaction->status, ['PAID', 'DIPROSES']))
                <form action="{{ route('marketplace.transaction.status', $transaction->id) }}" method="POST" class="w-full flex gap-2"
                      x-data="{ 
                          open: false, 
                          statusVal: '{{ $transaction->status === 'PAID' ? 'DIPROSES' : $transaction->status }}',
                          statusLabel: '{{ $transaction->status === 'PAID' ? 'Diproses' : ($transaction->status === 'DIPROSES' ? 'Diproses' : 'Selesai') }}'
                      }">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" :value="statusVal">
                    
                    <div class="relative flex-grow font-sans">
                        <!-- Custom Select Button -->
                        <button type="button" @click="open = !open" 
                                class="w-full px-4 py-4 rounded-xl border border-gray-300 bg-white text-left focus:border-red-500 focus:ring-red-500 focus:ring-2 outline-none transition text-xs font-bold flex items-center justify-between shadow-sm">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" :class="statusVal === 'DIPROSES' ? 'bg-yellow-500' : 'bg-emerald-500'"></span>
                                <span class="text-gray-900 font-bold" x-text="statusLabel"></span>
                            </span>
                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Options List -->
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             style="display: none;"
                             class="absolute z-50 bottom-full mb-1 w-full rounded-xl bg-white border border-gray-200 shadow-xl max-h-60 overflow-y-auto focus:outline-none">
                            <div class="py-1">
                                <button type="button" @click="statusVal = 'DIPROSES'; statusLabel = 'Diproses'; open = false"
                                        class="w-full text-left px-4 py-3.5 text-xs hover:bg-red-50 hover:text-red-950 transition flex items-center justify-between group">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                                        <span class="font-bold text-gray-700 group-hover:text-red-950">Diproses</span>
                                    </span>
                                    <svg x-show="statusVal === 'DIPROSES'" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                                <button type="button" @click="statusVal = 'SELESAI'; statusLabel = 'Selesai'; open = false"
                                        class="w-full text-left px-4 py-3.5 text-xs hover:bg-red-50 hover:text-red-950 transition flex items-center justify-between group">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                        <span class="font-bold text-gray-700 group-hover:text-red-950">Selesai</span>
                                    </span>
                                    <svg x-show="statusVal === 'SELESAI'" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="bg-gray-900 text-white text-xs font-bold px-5 py-4 rounded-xl hover:bg-black transition shadow-sm tap-effect shrink-0">
                        Update Status
                    </button>
                </form>
            @endif

            {{-- Jika PENDING, tampilkan tombol Batalkan Pesanan & Bayar Sekarang bersisian --}}
            @if($transaction->status === 'PENDING' && $transaction->user_id === auth()->id())
                <div class="flex gap-3 w-full">
                    <form action="{{ route('marketplace.transaction.status', $transaction->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Pesanan yang dibatalkan tidak bisa dikembalikan.')">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="DIBATALKAN">
                        <button type="submit" class="w-full bg-white text-orange-600 border border-orange-200 hover:bg-orange-50 text-[13px] font-bold py-4 rounded-2xl transition shadow-sm tap-effect">
                            Batalkan Pesanan
                        </button>
                    </form>

                    <a href="{{ route('marketplace.payment.status', $transaction->id) }}" class="flex-[1.5] bg-[#E21F26] hover:bg-red-700 text-white text-[13px] font-bold py-4 rounded-2xl flex justify-center items-center transition shadow-md shadow-red-500/10 tap-effect">
                        Bayar Sekarang
                    </a>
                </div>
            @endif

            {{-- Form Hapus Riwayat (Hanya jika status DIBATALKAN atau SELESAI) --}}
            @if(in_array($transaction->status, ['DIBATALKAN', 'SELESAI']))
                <form action="{{ route('marketplace.transaction.destroy', $transaction->id) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin menghapus riwayat pesanan ini secara permanen?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-white text-red-600 border border-red-200 hover:bg-[#E21F26] hover:text-white hover:border-[#E21F26] text-[13px] font-bold py-4 rounded-2xl flex justify-center items-center transition shadow-sm gap-1.5 tap-effect group" title="Hapus Riwayat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Riwayat
                    </button>
                </form>
            @endif

        </div>

    </div>
</div>
@endsection
