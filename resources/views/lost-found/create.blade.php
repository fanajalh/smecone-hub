@extends('layouts.app')
@section('title', '| Lapor Barang')

@section('content')
<div class="max-w-3xl mx-auto pt-4 px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <div class="flex items-center gap-4 mb-6 md:mt-4">
        <a href="/lost-found" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-500 hover:text-red-600 hover:bg-red-50 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Lapor Barang</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi detail barang yang hilang atau kamu temukan.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm border border-red-100 shadow-sm">
            <ul class="list-disc pl-5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="/lost-found" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Tipe Laporan</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-gray-50 p-4 shadow-sm hover:bg-red-50 transition-colors">
                        <input type="radio" name="type" value="lost" class="sr-only peer" checked>
                        <div class="peer-checked:border-red-500 peer-checked:bg-red-50 absolute inset-0 rounded-2xl border-2 border-transparent transition"></div>
                        <div class="relative flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold shrink-0 shadow-sm">!</div>
                            <span class="font-bold text-gray-800 text-sm md:text-base">Saya Kehilangan</span>
                        </div>
                    </label>
                    
                    <label class="relative flex cursor-pointer rounded-2xl border border-gray-200 bg-gray-50 p-4 shadow-sm hover:bg-green-50 transition-colors">
                        <input type="radio" name="type" value="found" class="sr-only peer">
                        <div class="peer-checked:border-green-500 peer-checked:bg-green-50 absolute inset-0 rounded-2xl border-2 border-transparent transition"></div>
                        <div class="relative flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold shrink-0 shadow-sm">✓</div>
                            <span class="font-bold text-gray-800 text-sm md:text-base">Saya Menemukan</span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang</label>
                <input type="text" name="item_name" value="{{ old('item_name') }}" required placeholder="Contoh: Dompet Hitam, Kunci Motor Honda" 
                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition font-medium text-gray-800 placeholder-gray-400">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Detail & Ciri-ciri</label>
                <textarea name="description" rows="4" required placeholder="Jelaskan ciri-ciri barang (warna, merk, isi) dan lokasi terakhir dilihat/ditemukan..." 
                          class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition font-medium text-gray-800 placeholder-gray-400 resize-none">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Barang <span class="text-gray-400 font-medium">(Opsional)</span></label>
                <div class="relative">
                    <input type="file" name="image" accept="image/*" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition text-sm text-gray-600
                                  file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 hover:file:cursor-pointer cursor-pointer">
                </div>
                <p class="text-xs text-gray-500 mt-2 font-medium">Format: JPG, PNG. Maksimal 2MB.</p>
            </div>

            <button type="submit" class="w-full mt-4 bg-red-600 text-white font-bold py-4 px-4 rounded-xl hover:bg-red-700 active:scale-95 transition-all shadow-lg shadow-red-600/30 flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Posting Laporan
            </button>
        </form>
    </div>
</div>
@endsection