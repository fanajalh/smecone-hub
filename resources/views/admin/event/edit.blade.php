@extends('layouts.admin')
@section('title', '| Edit Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin/dashboard" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-gray-900 transition hover:bg-red-50 hover:text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit Event 🎉</h1>
            <p class="text-sm text-gray-500">Perbarui agenda atau acara sekolah.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6">
            <ul class="list-disc ml-5 text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 md:p-8">
        <form action="/admin/event/{{ $event->id }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama Event / Acara</label>
                <input type="text" name="judul" value="{{ old('judul', $event->judul) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_event" value="{{ old('tanggal_event', \Carbon\Carbon::parse($event->tanggal_event)->format('Y-m-d')) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Kategori (cth: Seminar)</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $event->kategori) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Lokasi Acara</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Upload Poster Baru (Opsional)</label>
                <input type="file" name="gambar[]" accept="image/*" multiple class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                <p class="text-[10px] text-gray-500 mt-1 italic">* Unggah gambar baru jika ingin mengganti poster lama. Maks 2MB/file.</p>
                
                @if($event->gambar && is_array($event->gambar))
                    <div class="mt-3 flex gap-2 flex-wrap">
                        @foreach($event->gambar as $img)
                            <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $img) }}" class="object-cover w-full h-full">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Deskripsi Detail Kegiatan</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">{{ old('deskripsi', $event->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-sm py-4 rounded-xl shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-[0.98]">
                    Simpan Perbarui Acara
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
