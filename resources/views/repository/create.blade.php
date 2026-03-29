@extends('layouts.app')
@section('title', '| Buat Repositori')

@section('content')
<div class="max-w-2xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in">
    
    <div class="mb-10 text-center relative">
        <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Simpan <span class="text-red-600">Karyamu</span></h1>
        <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-sm mx-auto">Buat repositori digital untuk menyimpan tugas akhir atau proyek kreatif kamu.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-100 text-red-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span>Mohon periksa kembali inputan Anda.</span>
        </div>
    @endif

    <form action="/repository" method="POST" class="bg-white p-6 md:p-10 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] space-y-7 relative overflow-hidden">
        @csrf
        
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="flex flex-col md:flex-row items-start md:items-end gap-4 relative z-10">
            <div class="w-full md:w-1/3">
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Pemilik</label>
                <div class="bg-gray-100 border border-gray-200 rounded-[20px] px-5 py-3.5 text-[14px] text-gray-600 font-extrabold shadow-inner flex items-center gap-2">
                    <div class="w-5 h-5 bg-white rounded-full flex items-center justify-center text-[10px] text-gray-400 border border-gray-200">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    {{ explode(' ', auth()->user()->name)[0] }}
                </div>
            </div>
            <span class="text-3xl text-gray-200 font-light px-2 mb-2 hidden md:block">/</span>
            <div class="w-full md:flex-1">
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Repositori <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="misal: tugas-akhir-web" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Kategori / Jurusan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="major" required class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 px-5 text-[14px] font-bold text-gray-800 appearance-none focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all cursor-pointer">
                        <option value="" disabled selected hidden>Pilih Jurusan...</option>
                        <option value="PPLG">💻 PPLG (RPL)</option>
                        <option value="TJKT">📡 TJKT (TKJ)</option>
                        <option value="DKV">🎨 DKV (Multimedia)</option>
                        <option value="AKL">📈 AKL (Akuntansi)</option>
                        <option value="MPLB">🏢 MPLB (Perkantoran)</option>
                        <option value="PM">🛍️ PM (Pemasaran)</option>
                        <option value="TF">💊 TF (Farmasi)</option>
                        <option value="Umum">📚 Umum / Lainnya</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Link Demo <span class="text-gray-300">(Opsional)</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                    <input type="url" name="demo_link" placeholder="https://youtube.com/..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Deskripsi Proyek <span class="text-gray-300">(Opsional)</span></label>
            <textarea name="description" rows="3" placeholder="Jelaskan secara singkat tentang karya ini..." 
                      class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all resize-none"></textarea>
        </div>

        <div class="space-y-3 relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Visibilitas</label>
            
            <label class="flex items-start gap-4 cursor-pointer p-4 bg-gray-50 border border-gray-200 rounded-[24px] hover:bg-white hover:border-red-300 transition-all group tap-effect">
                <input type="radio" name="visibility" value="public" checked class="mt-1.5 text-red-600 focus:ring-red-500 border-gray-300">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        <span class="font-black text-[14px] text-gray-800 uppercase tracking-tight">Publik</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-0.5 font-bold">Semua orang di Smecone bisa melihat dan mengunduh karyamu.</p>
                </div>
            </label>

            <label class="flex items-start gap-4 cursor-pointer p-4 bg-gray-50 border border-gray-200 rounded-[24px] hover:bg-white hover:border-red-300 transition-all group tap-effect">
                <input type="radio" name="visibility" value="private" class="mt-1.5 text-red-600 focus:ring-red-500 border-gray-300">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span class="font-black text-[14px] text-gray-800 uppercase tracking-tight">Privat</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-0.5 font-bold">Hanya kamu yang dapat mengelola repositori ini.</p>
                </div>
            </label>
        </div>

        <div class="h-px w-full bg-gray-100 my-2"></div>

        <button type="submit" class="w-full bg-gray-900 text-white py-4.5 rounded-[24px] font-black text-[15px] hover:bg-black transition-all shadow-xl hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect relative z-10 flex justify-center items-center gap-2 h-14 uppercase tracking-widest">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Buat Repositori
        </button>

    </form>
</div>
@endsection