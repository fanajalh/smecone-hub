@extends('layouts.app')
@section('title', '| Edit Laporan')

@section('content')
<div class="max-w-3xl mx-auto pt-4 px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <div class="flex items-center gap-4 mb-6 md:mt-4">
        <a href="/lost-found/{{ $item->id }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-500 hover:text-red-600 hover:bg-red-50 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Edit Laporan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi barang.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="/lost-found/{{ $item->id }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang</label>
                <input type="text" name="item_name" value="{{ $item->item_name }}" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition font-medium">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Detail & Ciri-ciri</label>
                <textarea name="description" rows="4" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition font-medium resize-none">{{ $item->description }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Foto <span class="text-gray-400 font-medium">(Abaikan jika tidak ingin mengubah)</span></label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-red-100 file:text-red-700">
            </div>

            <button type="submit" class="w-full bg-gray-800 text-white font-bold py-4 rounded-xl hover:bg-black active:scale-95 transition-all shadow-md">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection