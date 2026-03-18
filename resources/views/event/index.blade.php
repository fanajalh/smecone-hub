@extends('layouts.app')
@section('title', '| Event Sekolah')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 animate-page-in">
    
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ url()->previous() }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition active:scale-95 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Event Sekolah 🎉</h1>
            <p class="text-sm text-gray-500 font-medium mt-0.5">Jangan sampai kelewatan acara seru!</p>
        </div>
    </div>

    <div class="flex gap-2 overflow-x-auto hide-scrollbar mb-8 pb-2">
        <button class="px-5 py-2 bg-pink-600 text-white font-bold rounded-full text-sm shrink-0 shadow-md">Semua Event</button>
        <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 font-bold rounded-full text-sm shrink-0 hover:bg-gray-50 transition">Bulan Ini</button>
        <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 font-bold rounded-full text-sm shrink-0 hover:bg-gray-50 transition">OSIS</button>
        <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 font-bold rounded-full text-sm shrink-0 hover:bg-gray-50 transition">Ekskul</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <div class="bg-white rounded-[24px] overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition group cursor-pointer">
            <div class="h-40 bg-pink-100 flex items-center justify-center text-pink-300 relative">
                <span class="text-4xl">📸</span>
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl font-bold text-center shadow-sm">
                    <p class="text-[10px] text-gray-500 uppercase leading-none">NOV</p>
                    <p class="text-lg text-pink-600 leading-none mt-0.5">15</p>
                </div>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-blue-100 text-blue-600 text-[10px] font-black uppercase px-2 py-0.5 rounded">LOMBA</span>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg mb-1 group-hover:text-pink-600 transition">Smecone Photography Contest</h3>
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">Lomba fotografi antarkelas dengan tema "Wajah Smecone". Berhadiah uang tunai dan sertifikat!</p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Lapangan Basket Smecone
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[24px] overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition group cursor-pointer">
            <div class="h-40 bg-purple-100 flex items-center justify-center text-purple-300 relative">
                <span class="text-4xl">🎤</span>
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl font-bold text-center shadow-sm">
                    <p class="text-[10px] text-gray-500 uppercase leading-none">DES</p>
                    <p class="text-lg text-purple-600 leading-none mt-0.5">02</p>
                </div>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-red-100 text-red-600 text-[10px] font-black uppercase px-2 py-0.5 rounded">OSIS</span>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg mb-1 group-hover:text-pink-600 transition">Pensi Tahunan Smecone</h3>
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">Pentas seni terbesar tahun ini! Menampilkan band sekolah, tari tradisional, dan banyak lagi.</p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Aula Utama
                </div>
            </div>
        </div>

    </div>
</div>
@endsection