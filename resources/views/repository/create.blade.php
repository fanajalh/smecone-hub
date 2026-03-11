@extends('layouts.app')
@section('title', '| Create Repository')

@section('content')
<div class="max-w-3xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    
    <div class="border-b border-gray-200 pb-5 mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Buat Repositori Baru</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Gudang penyimpanan digital dengan Server Git Aktif untuk karyamu.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl text-sm font-bold shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl text-sm font-bold shadow-sm flex items-center gap-3">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    <form action="/repository" method="POST" class="space-y-6 bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
        @csrf

        <div class="flex flex-col md:flex-row items-start md:items-end gap-4">
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Pemilik</label>
                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 font-bold shadow-inner">
                    {{ auth()->user()->name }}
                </div>
            </div>
            <span class="text-3xl text-gray-300 font-light px-2 mb-1 hidden md:block">/</span>
            <div class="w-full md:flex-1">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Nama Repositori <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="misal: tugas-akhir-web" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm font-bold bg-gray-50 focus:bg-white shadow-inner">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Kategori <span class="text-red-500">*</span></label>
                <select name="major" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 transition text-sm font-bold bg-gray-50 focus:bg-white cursor-pointer">
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="PPLG">💻 PPLG (RPL)</option>
                    <option value="TJKT">📡 TJKT (TKJ)</option>
                    <option value="DKV">🎨 DKV (Multimedia)</option>
                    <option value="AKL">📈 AKL (Akuntansi)</option>
                    <option value="MPLB">🏢 MPLB (Perkantoran)</option>
                    <option value="PM">🛍️ PM (Pemasaran)</option>
                    <option value="TF">💊 TF (Farmasi)</option>
                    <option value="Umum">📚 Umum / Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Link Demo <span class="text-gray-400 font-medium lowercase">(Opsional)</span></label>
                <input type="url" name="demo_link" placeholder="https://youtube.com/..." class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 transition text-sm font-bold bg-gray-50 focus:bg-white shadow-inner">
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Deskripsi Proyek <span class="text-gray-400 font-medium lowercase">(Opsional)</span></label>
            <input type="text" name="description" placeholder="Jelaskan secara singkat..." class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 transition text-sm font-bold bg-gray-50 focus:bg-white shadow-inner">
        </div>

        <hr class="border-gray-100">

        <div class="space-y-3">
            <label class="flex items-start gap-4 cursor-pointer p-4 border border-gray-200 rounded-2xl hover:border-red-300 hover:bg-red-50/30 transition group">
                <input type="radio" name="visibility" value="public" checked class="mt-1 text-red-600 focus:ring-red-500 border-gray-300">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-extrabold text-gray-900">Publik</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Semua orang bisa melihat referensi karya ini.</p>
                </div>
            </label>

            <label class="flex items-start gap-4 cursor-pointer p-4 border border-gray-200 rounded-2xl hover:border-red-300 hover:bg-red-50/30 transition group">
                <input type="radio" name="visibility" value="private" class="mt-1 text-red-600 focus:ring-red-500 border-gray-300">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span class="font-extrabold text-gray-900">Privat</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Hanya kamu dan kolaborator yang memiliki akses.</p>
                </div>
            </label>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <button type="submit" class="bg-gray-900 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-red-600 active:scale-95 transition shadow-md w-full md:w-auto uppercase tracking-wider">
                Create Repository
            </button>
        </div>
    </form>
</div>
@endsection