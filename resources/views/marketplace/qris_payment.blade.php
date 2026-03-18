@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-10 text-center">
    
    @if($transaction->status === 'PAID')
        <div class="mb-6">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-emerald-600 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-500">Terima kasih, pesanan Anda sudah dibayar dan sedang diproses oleh penjual.</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border mb-6 text-left flex items-center">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="w-16 h-16 object-cover rounded">
            @endif
            <div class="ml-4">
                <h3 class="font-bold text-gray-800">{{ $item->title }}</h3>
                <p class="text-emerald-600 font-bold text-lg">LUNAS</p>
                <p class="text-xs text-gray-500 mt-1">ID: TRX-{{ $transaction->id }}</p>
            </div>
        </div>

        <a href="{{ route('marketplace.index') }}" class="block w-full bg-emerald-600 text-white py-3 rounded-lg font-bold hover:bg-emerald-700 transition">
            Kembali ke Marketplace
        </a>

    @else
        <h2 class="text-2xl font-bold mb-2">Scan QRIS Untuk Membayar</h2>
        <p class="text-gray-500 mb-4">Bayar menggunakan DANA, GoPay, OVO, ShopeePay, atau M-Banking</p>

        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-2 rounded-lg mb-6 inline-block shadow-sm">
            <span class="text-xs block text-blue-500 font-bold uppercase tracking-wider">ID Transaksi Anda:</span>
            <span class="font-mono font-black text-xl">TRX-{{ $transaction->id }}</span>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border mb-6 text-left flex items-center">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="w-16 h-16 object-cover rounded">
            @endif
            <div class="ml-4">
                <h3 class="font-bold text-gray-800">{{ $item->title }}</h3>
                <p class="text-emerald-600 font-bold text-lg">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="border-4 border-emerald-900 rounded-xl p-4 inline-block bg-white shadow-sm mb-4">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($transaction->invoice_url) }}" 
                 alt="QRIS Barcode" class="w-64 h-64 mx-auto">
        </div>
        
        <p class="text-sm font-bold text-emerald-900 tracking-widest mt-2 mb-6">NMID: Smecone Hub</p>
        <p class="text-sm text-gray-500 mb-4 animate-pulse">Menunggu pembayaran... (Auto-refresh 5 menit)</p>

        <div class="flex gap-3 mb-2">
            <a href="{{ route('marketplace.index') }}" class="w-1/2 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold hover:bg-gray-300 transition block">
                Batalkan
            </a>
            <button onclick="window.location.reload()" class="w-1/2 bg-blue-100 text-blue-700 py-3 rounded-lg font-bold hover:bg-blue-200 transition">
                Cek Status
            </button>
        </div>

        <script>
            setTimeout(function() { 
                window.location.reload(); 
            }, 300000); 
        </script>
    @endif
</div>
@endsection