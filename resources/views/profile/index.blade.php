@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Profil Saya</h2>
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full border rounded p-2" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nomor WhatsApp</label>
                <input type="text" name="whatsapp_number" value="{{ auth()->user()->whatsapp_number }}" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nama Toko (Jika ada)</label>
                <input type="text" name="store_name" value="{{ auth()->user()->store_name }}" class="w-full border rounded p-2">
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
