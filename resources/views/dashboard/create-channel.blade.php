@extends('layouts.app')
@section('title', '| Buat Channel Baru')

@section('content')
<div class="max-w-2xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-24">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="/dashboard" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-500 hover:text-red-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Buat Channel Baru</h1>
            <p class="text-sm text-gray-500">Mulai komunitas dan diskusimu sendiri di Smecone Hub.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="/dashboard/channel" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Channel</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-red-500 font-black text-lg">#</span>
                    <input type="text" name="title" required placeholder="mabar-ml-smecone" 
                           class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white text-gray-800 font-bold lowercase transition">
                </div>
                <p class="text-xs text-gray-400 mt-2 font-medium">Gunakan huruf kecil, pisahkan dengan strip (-), tanpa spasi.</p>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Topik Obrolan / Deskripsi</label>
                <textarea name="content" required placeholder="Deskripsikan aturan atau tujuan channel ini dibuat..." rows="4" 
                          class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white text-gray-800 font-medium resize-none transition"></textarea>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-xl hover:bg-red-700 active:scale-95 transition shadow-lg shadow-red-600/30">
                Bentuk Channel Sekarang
            </button>
        </form>
    </div>
</div>
@endsection