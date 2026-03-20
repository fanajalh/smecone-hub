@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6 max-w-5xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Pembelian Saya</h2>
        <a href="{{ route('marketplace.index') }}" class="text-emerald-600 font-bold hover:underline text-sm">Kembali Belanja</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($purchases as $purchase)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">ID Transaksi</span>
                    <p class="font-mono font-bold text-gray-700">#TRX-{{ $purchase->id }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $purchase->status == 'PAID' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $purchase->status }}
                </span>
            </div>

            <div class="mb-4 flex-grow">
                <h3 class="font-bold text-lg text-gray-800 leading-tight mb-1">{{ $purchase->marketplaceItem->title }}</h3>
                <p class="text-emerald-600 font-black text-xl">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</p>
            </div>

            <div class="bg-emerald-50 p-3 rounded-lg mb-4 border border-emerald-100">
                <p class="text-xs text-emerald-800 mb-1 font-bold">Penjual:</p>
                <div class="flex items-center">
                    <div class="ml-1">
                        <p class="text-sm font-bold text-gray-800">{{ $purchase->marketplaceItem->user->store_name ?? $purchase->marketplaceItem->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $purchase->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            @php $sellerWa = $purchase->marketplaceItem->user->whatsapp_number; @endphp
            
            @if($sellerWa && $purchase->status == 'PAID')
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $sellerWa) }}?text=Halo kak, saya {{ auth()->user()->name }} yang baru saja membeli pesanan *{{ $purchase->marketplaceItem->title }}* dengan ID TRX-{{ $purchase->id }}. Kapan barangnya bisa saya ambil?" target="_blank" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold py-2.5 rounded-lg flex justify-center items-center transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                Chat Penjual
            </a>
            @else
            <button disabled class="w-full bg-gray-100 text-gray-400 text-sm font-bold py-2.5 rounded-lg cursor-not-allowed border border-gray-200">
                {{ $purchase->status == 'PAID' ? 'Penjual Tidak Ada WA' : 'Selesaikan Pembayaran Dulu' }}
            </button>
            @endif
        </div>
        @empty
        <div class="col-span-full bg-white p-8 text-center rounded-xl border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="text-gray-500 font-bold">Belum ada riwayat pembelian.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection