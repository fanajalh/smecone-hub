@extends('layouts.app')
@section('title', '| Lapor Barang')

@section('content')
<div class="max-w-2xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in">
    
    <div class="mb-10 text-center relative">
        <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Lapor <span class="text-red-600">Barang</span></h1>
        <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-sm mx-auto">Isi detail barang yang hilang atau kamu temukan di area Smecone.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-100 text-red-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span>Mohon lengkapi semua data dengan benar.</span>
        </div>
    @endif

    <form action="/lost-found" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-10 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] space-y-7 relative overflow-hidden">
        @csrf
        
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div>
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-3 ml-1">Tipe Laporan <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-4">
                <label class="relative flex cursor-pointer tap-effect">
                    <input type="radio" name="type" value="lost" class="sr-only peer" checked>
                    <div class="w-full py-4 px-4 bg-gray-50 border border-gray-200 rounded-[20px] text-center transition-all peer-checked:bg-red-600 peer-checked:border-red-600 peer-checked:shadow-[0_8px_20px_rgba(220,38,38,0.25)]">
                        <span class="text-[13px] font-extrabold text-gray-500 peer-checked:text-white block">Kehilangan</span>
                    </div>
                </label>
                
                <label class="relative flex cursor-pointer tap-effect">
                    <input type="radio" name="type" value="found" class="sr-only peer">
                    <div class="w-full py-4 px-4 bg-gray-50 border border-gray-200 rounded-[20px] text-center transition-all peer-checked:bg-green-600 peer-checked:border-green-600 peer-checked:shadow-[0_8px_20px_rgba(22,163,74,0.25)]">
                        <span class="text-[13px] font-extrabold text-gray-500 peer-checked:text-white block">Menemukan</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Barang <span class="text-red-500">*</span></label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <input type="text" name="item_name" value="{{ old('item_name') }}" required placeholder="Contoh: Dompet Hitam, Kunci Motor Honda" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Ciri-ciri & Lokasi <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required placeholder="Jelaskan detail barang dan lokasi terakhir dilihat/ditemukan..." 
                      class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all resize-none">{{ old('description') }}</textarea>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Foto Barang <span class="text-gray-300">(Opsional)</span></label>
            <div class="flex items-center justify-center w-full group tap-effect">
                <label id="dropArea" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-[24px] cursor-pointer bg-gray-50 group-hover:bg-red-50 group-hover:border-red-300 transition-all duration-300 relative overflow-hidden text-center px-4">
                    
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 transition-transform group-hover:-translate-y-1">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-2 text-gray-400 group-hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600">Klik atau Tarik Foto</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Maksimal 2MB</p>
                    </div>
                    
                    <input type="file" name="image" id="fileInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </label>
            </div>
        </div>

        <div class="h-px w-full bg-gray-100 my-2"></div>

        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4.5 rounded-[24px] font-black text-[15px] hover:from-red-700 hover:to-red-800 transition-all shadow-[0_8px_25px_rgba(220,38,38,0.3)] hover:shadow-[0_12px_30px_rgba(220,38,38,0.4)] hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect relative z-10 flex justify-center items-center gap-2 h-14">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            POSTING LAPORAN
        </button>
        
        <div class="flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Data kamu akan terlihat oleh publik</p>
        </div>

    </form>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const dropArea = document.getElementById('dropArea');
    const uploadText = dropArea.querySelector('p.text-gray-700');

    fileInput.addEventListener('change', function() {
        if(this.files && this.files.length > 0) {
            uploadText.textContent = "Foto terpilih: " + this.files[0].name;
            uploadText.classList.remove('text-gray-700');
            uploadText.classList.add('text-red-600');
            dropArea.classList.add('border-red-400', 'bg-red-50');
        }
    });
</script>
@endsection