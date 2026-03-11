@extends('layouts.app')
@section('title', '| Buat Channel Obrolan')

@section('content')
<div class="max-w-2xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in">
    
    <div class="mb-8 text-center relative">
        <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Buat <span class="text-red-600">Channel Baru</span></h1>
        <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-sm mx-auto">Mulai topik diskusi seru atau buat grup belajar bersama teman Smecone.</p>
    </div>

    <form action="/forum" method="POST" class="bg-white p-6 md:p-8 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] space-y-6 relative overflow-hidden">
        @csrf
        
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Channel / Topik <span class="text-red-500">*</span></label>
            <div class="relative flex items-center">
                <div class="absolute left-4 font-black text-gray-400 pointer-events-none text-lg">#</div>
                <input type="text" name="title" required placeholder="Misal: Info Mabar, Tugas PPLG, Anak Basket..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-10 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Deskripsi & Aturan Obrolan <span class="text-red-500">*</span></label>
            <textarea name="content" rows="4" required placeholder="Jelaskan apa yang akan dibahas di channel ini..." 
                      class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all resize-none"></textarea>
        </div>

        <div class="h-px w-full bg-gray-100 my-2"></div>

        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4 rounded-[20px] font-black text-[14px] hover:from-red-700 hover:to-red-800 transition-all shadow-[0_8px_25px_rgba(220,38,38,0.3)] hover:shadow-[0_12px_30px_rgba(220,38,38,0.4)] hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect relative z-10 flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            BUAT CHANNEL
        </button>

    </form>
</div>
@endsection