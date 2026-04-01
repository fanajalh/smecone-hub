@extends('layouts.admin')
@section('title', '| Edit Prestasi')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="/admin/dashboard" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-gray-900 transition hover:bg-red-50 hover:text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit Prestasi 🏆</h1>
            <p class="text-sm text-gray-500">Perbarui pencapaian siswa ini.</p>
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
        <form action="/admin/prestasi/{{ $prestasi->id }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Judul Prestasi (cth: LKS Web 2026)</label>
                <input type="text" name="judul" value="{{ old('judul', $prestasi->judul) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama Pemenang / Tim</label>
                    <input type="text" name="nama_pemenang" value="{{ old('nama_pemenang', $prestasi->nama_pemenang) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Kategori Juara (cth: Juara 1)</label>
                    <input type="text" name="kategori_juara" value="{{ old('kategori_juara', $prestasi->kategori_juara) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Tingkat (cth: Nasional)</label>
                    <input type="text" name="tingkat" value="{{ old('tingkat', $prestasi->tingkat) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Tanggal Diraih</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($prestasi->tanggal)->format('Y-m-d')) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Upload Sertifikat Baru (Opsional)</label>
                <input type="file" name="gambar[]" accept="image/*" multiple class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                <p class="text-[10px] text-gray-500 mt-1 italic">* Unggah gambar baru jika ingin mengganti gambar lama. Maks 2MB/file.</p>
                
                @if($prestasi->gambar && is_array($prestasi->gambar))
                    <div class="mt-3 flex gap-2 flex-wrap">
                        @foreach($prestasi->gambar as $img)
                            <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $img) }}" class="object-cover w-full h-full">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:bg-white transition outline-none">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-sm py-4 rounded-xl shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
