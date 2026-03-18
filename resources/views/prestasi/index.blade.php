@extends('layouts.app')
@section('title', '| Prestasi Smecone')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 animate-page-in">
    
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ url()->previous() }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition active:scale-95 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Prestasi 🏆</h1>
            <p class="text-sm text-gray-500 font-medium mt-0.5">Hall of Fame Siswa Smecone.</p>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-[24px] p-6 md:p-8 text-white relative overflow-hidden shadow-lg mb-8">
        <div class="absolute right-[-10%] top-[-20%] text-8xl opacity-20 transform rotate-12">🌟</div>
        <h2 class="text-2xl md:text-3xl font-black mb-2 relative z-10">Punya Prestasi Keren?</h2>
        <p class="text-yellow-50 font-medium text-sm md:text-base relative z-10 w-3/4">Pamerkan medali atau piagammu di sini agar seluruh sekolah tahu!</p>
        <button onclick="Swal.fire('Fitur Input Segera Hadir!', 'Nanti kamu bisa upload sertifikatmu di sini.', 'info')" class="mt-4 px-6 py-2.5 bg-white text-yellow-600 font-bold rounded-xl shadow-md active:scale-95 transition relative z-10">
            Klaim Prestasi
        </button>
    </div>

    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-10 flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-4xl mb-4 border border-gray-100">🏅</div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Catatan Prestasi</h3>
        <p class="text-sm text-gray-500 max-w-sm">Jadilah yang pertama mengukir namamu di dinding prestasi Smecone bulan ini!</p>
    </div>

</div>
@endsection