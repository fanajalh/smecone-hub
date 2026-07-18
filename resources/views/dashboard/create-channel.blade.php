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
        <form action="/dashboard/channel" method="POST" class="flex flex-col gap-5">
            @csrf
            
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-400 pl-1">NAMA CHANNEL</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-red-500 font-black text-[15px]">#</span>
                    <input type="text" name="title" required placeholder="mabar-ml-smecone" 
                           class="w-full pl-9 pr-4 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 text-slate-800 font-bold lowercase outline-none transition-all placeholder:text-slate-400 text-sm">
                </div>
                <p class="text-xs text-slate-400 mt-1 font-semibold ml-1">Gunakan huruf kecil, pisahkan dengan strip (-), tanpa spasi.</p>
            </div>
            
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-400 pl-1">TOPIK OBROLAN / DESKRIPSI</label>
                <textarea name="content" required placeholder="Deskripsikan aturan atau tujuan channel ini dibuat..." rows="4" 
                          class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200/80 rounded-xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 text-slate-800 font-semibold resize-none outline-none transition-all placeholder:text-slate-400 text-sm"></textarea>
            </div>

            <div class="flex items-start gap-3 p-4 bg-orange-50 border border-orange-200/60 rounded-xl mt-2 cursor-pointer transition-colors hover:bg-orange-100/50" onclick="document.getElementById('is_private').click()">
                <div class="mt-0.5">
                    <input type="checkbox" name="is_private" id="is_private" onclick="event.stopPropagation()" class="w-5 h-5 text-red-600 bg-white border-slate-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer transition-all">
                </div>
                <div>
                    <label for="is_private" class="text-sm font-bold text-orange-900 cursor-pointer block leading-tight">Buat sebagai Channel Privat</label>
                    <span class="block text-[11px] font-semibold text-orange-700/80 mt-1 leading-snug">Hanya orang yang diizinkan atau memiliki link undangan khusus yang dapat bergabung.</span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6 mt-2">
                <button type="submit" class="w-full bg-slate-900 text-white font-extrabold text-xs py-4 rounded-xl hover:bg-black transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    BENTUK CHANNEL SEKARANG
                </button>
            </div>
        </form>
    </div>
</div>
@endsection