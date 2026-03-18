@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center">Pilih Metode Pembayaran</h2>
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Gagal Memproses!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="flex items-center mb-6 p-4 border rounded-lg bg-gray-50">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" class="w-20 h-20 object-cover rounded">
        @endif
        <div class="ml-4">
            <h3 class="font-bold text-lg">{{ $item->title }}</h3>
            <p class="text-emerald-600 font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
        </div>
    </div>

    <form action="{{ route('marketplace.checkout.direct', $item->id) }}" method="POST">
        @csrf
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp Anda</label>
            <div class="flex">
                <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 font-bold">
                    +62
                </span>
                <input type="number" name="whatsapp_number" required placeholder="81234567890" 
                       class="w-full px-4 py-3 rounded-r-lg border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 outline-none transition">
            </div>
            <p class="text-xs text-gray-500 mt-1">Kami akan mengirimkan notifikasi pembelian ke nomor ini.</p>
        </div>
        
            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="payment_method" value="QRIS" class="w-5 h-5 text-emerald-600" required checked>
                <span class="ml-3 font-bold text-gray-700">QRIS (Semua Bank & E-Wallet)</span>
            </label>

            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="payment_method" value="DANA" class="w-5 h-5 text-emerald-600" required>
                <span class="ml-3 font-bold text-gray-700">DANA</span>
            </label>

            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                <input type="radio" name="payment_method" value="GOPAY" class="w-5 h-5 text-emerald-600" required>
                <span class="ml-3 font-bold text-gray-700">GoPay</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-lg font-bold hover:bg-emerald-700 transition shadow-lg">
            Lanjutkan Pembayaran
        </button>
    </form>
</div>
@endsection