@extends('layouts.admin')
@section('title', '| Tambah Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:py-12 pb-32">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin/dashboard" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-gray-900 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Tambah Event 🎉</h1>
            <p class="text-sm text-gray-500">Timeline event dan acara seru sekolah.</p>
        </div>
    </div>

    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="/admin/event" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Judul Event (cth: Smecone Cup 2026)</label>
                <input type="text" name="judul" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-pink-500 focus:bg-white transition outline-none" placeholder="Masukkan judul event...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Tanggal Event</label>
                    <input type="date" name="tanggal_event" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-pink-500 focus:bg-white transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Kategori (cth: Lomba, Seminar, Umum)</label>
                    <input type="text" name="kategori" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-pink-500 focus:bg-white transition outline-none" placeholder="cth: Lomba">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Lokasi (cth: Aula Smecone)</label>
                <input type="text" name="lokasi" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-pink-500 focus:bg-white transition outline-none" placeholder="cth: Aula Smecone">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Poster / Gambar Event (Bisa lebih dari 1)</label>
                <input type="file" name="gambar[]" accept="image/*" multiple class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-pink-500 focus:bg-white transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 cursor-pointer">
                <p class="text-[10px] text-gray-400 mt-1">* Pilih beberapa foto sekaligus dengan menekan tombol Ctrl/Shift</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-pink-500 focus:bg-white transition outline-none" placeholder="Berikan deskripsi acara..."></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold text-sm py-4 rounded-xl shadow-md transition active:scale-[0.98]">
                    Publikasikan Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection