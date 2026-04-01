@extends('layouts.app')
@section('title', '| Riwayat Pembelian')

@section('content')
<div class="max-w-4xl mx-auto pt-6 px-4 md:px-6 pb-24 font-sans text-gray-800 animate-page-in">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">Riwayat Belanja</h2>
        <a href="{{ route('marketplace.index') }}" class="text-emerald-600 font-medium hover:text-emerald-700 text-[13px] transition flex items-center gap-1 tap-effect">
            Smecone Mart
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($purchases as $purchase)
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.02)] border border-gray-100 p-4 flex flex-col hover:shadow-[0_4px_15px_rgba(0,0,0,0.04)] hover:border-emerald-100 transition duration-300">
            
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="font-mono font-semibold text-gray-600 text-[12px] md:text-[13px]">#TRX-{{ $purchase->id }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">{{ $purchase->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide uppercase shadow-sm border {{ $purchase->status == 'PAID' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-yellow-50 border-yellow-100 text-yellow-600' }}">
                    {{ $purchase->status == 'PAID' ? 'Berhasil' : $purchase->status }}
                </span>
            </div>

            <div class="mb-4 flex gap-3">
                @if(isset($purchase->marketplaceItem->image))
                    <div class="w-16 h-16 rounded-xl bg-gray-50 shrink-0 border border-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/' . $purchase->marketplaceItem->image) }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-xl bg-gray-50 shrink-0 border border-gray-100 flex items-center justify-center text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                
                <div class="flex-grow flex flex-col justify-center">
                    <h3 class="font-semibold text-[14px] md:text-[15px] text-gray-900 leading-tight mb-1 line-clamp-2">
                        {{ $purchase->marketplaceItem->item_name ?? 'Produk Dihapus' }}
                    </h3>
                    <p class="text-emerald-600 font-bold text-[14px]">
                        Rp {{ number_format($purchase->amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 p-3 rounded-xl mb-4 flex items-center gap-2 border border-gray-100">
                <div class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500 uppercase shrink-0">
                    {{ substr($purchase->marketplaceItem->user->store_name ?? $purchase->marketplaceItem->user->name ?? '?', 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-medium leading-none">Penjual</p>
                    <p class="text-[12px] font-semibold text-gray-800 leading-tight mt-0.5">{{ $purchase->marketplaceItem->user->store_name ?? $purchase->marketplaceItem->user->name ?? 'Penjual Tidak Diketahui' }}</p>
                </div>
            </div>

            <div class="border border-gray-100 rounded-xl mb-4 p-3 bg-white shadow-sm space-y-2 text-[11px] md:text-[12px]">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Metode Pembayaran</span>
                    <span class="font-bold text-gray-800 uppercase text-emerald-600">{{ $purchase->payment_method ?? 'QRIS / E-WALLET' }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-gray-50 pt-2 mt-1">
                    <span class="text-gray-500 font-medium">Waktu Pesanan dibuat</span>
                    <span class="font-semibold text-gray-700">{{ $purchase->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="flex justify-between items-center border-t border-gray-50 pt-2 mt-1">
                    <span class="text-gray-500 font-medium">Update Pembayaran/Status</span>
                    <span class="font-semibold text-gray-700">{{ $purchase->updated_at->format('d M Y, H:i') }} WIB</span>
                </div>
            </div>

            <div class="mt-auto pt-2 flex flex-col gap-2">
                @php 
                    $sellerWa = $purchase->marketplaceItem->user->whatsapp_number ?? null; 
                @endphp
                
                @if($sellerWa && $purchase->status == 'PAID')
                    @php
                        $cleanWa = preg_replace('/^0/', '62', $sellerWa);
                        $defaultMsg = urlencode("Halo kak, saya " . auth()->user()->name . " yang baru saja order *" . ($purchase->marketplaceItem->title ?? 'Barang') . "* (TRX-{$purchase->id}). Kapan barangnya bisa saya ambil?");
                    @endphp
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ $defaultMsg }}" target="_blank" class="w-full bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-50 text-[13px] font-semibold py-2.5 rounded-xl flex justify-center items-center transition shadow-sm active:scale-[0.98]">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                        Hubungi Penjual
                    </a>
                @elseif($purchase->status != 'PENDING' && $purchase->status != 'DIBATALKAN')
                    <button disabled class="w-full bg-gray-50 text-gray-400 text-[13px] font-semibold py-2.5 rounded-xl cursor-not-allowed border border-gray-100">
                        Penjual Tidak Ada WA
                    </button>
                @endif

                <div class="flex gap-2 w-full mt-1">
                    {{-- UPDATE: Jika PENDING, bisa dibatalkan --}}
                    @if($purchase->status == 'PENDING')
                        <form action="{{ route('marketplace.transaction.status', $purchase->id) }}" method="POST" class="flex-1" onsubmit="confirmSubmit(event, 'Batalkan Pesanan?', 'Yakin ingin membatalkan pesanan ini tidak dapat dikembalikan?', 'Ya, Batalkan')">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="DIBATALKAN">
                            <button type="submit" class="w-full bg-white text-orange-600 border border-orange-200 hover:bg-orange-50 text-[13px] font-semibold py-2.5 rounded-xl flex justify-center items-center transition shadow-sm active:scale-[0.98]">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    {{-- DELETE: Jika DIBATALKAN atau SELESAI atau PENDING, boleh hapus riwayat --}}
                    @if(in_array($purchase->status, ['DIBATALKAN', 'SELESAI', 'PENDING']))
                        <form action="{{ route('marketplace.transaction.destroy', $purchase->id) }}" method="POST" class="{{ $purchase->status == 'PENDING' ? 'w-auto' : 'flex-1' }}" onsubmit="confirmSubmit(event, 'Hapus Riwayat?', 'Yakin ingin menghapus riwayat pesanan ini secara permanen?', 'Ya, Hapus')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-white text-red-600 border border-red-200 hover:bg-red-50 text-[13px] font-semibold py-2.5 {{ $purchase->status == 'PENDING' ? 'px-4' : '' }} rounded-xl flex justify-center items-center transition shadow-sm active:scale-[0.98]" title="Hapus Riwayat">
                                @if($purchase->status == 'PENDING')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                @else
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus Riwayat
                                @endif
                            </button>
                        </form>
                     @endif
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full bg-white py-16 px-4 text-center rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <p class="text-gray-900 font-semibold mb-1">Belum ada pesanan</p>
            <p class="text-[13px] text-gray-500 font-normal">Yuk, mulai jajan dan dukung karya teman-temanmu di Smecone Mart!</p>
            <a href="{{ route('marketplace.index') }}" class="inline-block mt-5 bg-emerald-50 text-emerald-600 font-semibold text-[13px] px-6 py-2.5 rounded-xl border border-emerald-100 hover:bg-emerald-100 transition active:scale-95">Mulai Belanja</a>
        </div>
        @endforelse
    </div>
</div>
@endsection