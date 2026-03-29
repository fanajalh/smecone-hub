@extends('layouts.admin')
@section('title', '| Tambah Prestasi')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin/dashboard" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-gray-900 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Tambah Prestasi 🏆</h1>
            <p class="text-sm text-gray-500">Catat kebanggaan siswa Smecone.</p>
        </div>
    </div>

    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="/admin/prestasi" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Judul Prestasi (cth: LKS Web 2026)</label>
                <input type="text" name="judul" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama Pemenang / Tim</label>
                    <input type="text" name="nama_pemenang" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Kategori Juara (cth: Juara 1)</label>
                    <input type="text" name="kategori_juara" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Tingkat (cth: Nasional)</label>
                    <input type="text" name="tingkat" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Tanggal Diraih</label>
                    <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Upload Sertifikat / Dokumentasi (Bisa lebih dari 1)</label>
                <input type="file" name="gambar[]" accept="image/*" multiple class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                <p class="text-[10px] text-gray-400 mt-1">* Pilih beberapa foto sekaligus dengan menekan tombol Ctrl/Shift</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-sm py-4 rounded-xl shadow-md transition active:scale-[0.98]">
                    Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection