@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
    <h2 class="text-2xl font-bold mb-6">Konfirmasi Pembayaran</h2>
    
    <div class="flex items-center mb-6 p-4 border rounded-lg">
        <img src="{{ asset('storage/' . $item->image) }}" class="w-20 h-20 object-cover rounded">
        <div class="ml-4">
            <h3 class="font-bold">{{ $item->title }}</h3>
            <p class="text-red-600 font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
        </div>
    </div>

    <form action="{{ route('marketplace.checkout.process', $item->id) }}" method="POST">
        @csrf
        <p class="mb-4 text-gray-600 italic text-sm">*Anda akan diarahkan ke halaman aman Xendit untuk memilih metode pembayaran (QRIS, DANA, Virtual Account, dll).</p>
        
        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 transition">
            Lanjut ke Pembayaran
        </button>
    </form>
</div>
@endsection