@extends('layouts.app')
@section('title', '| Jual Barang')

@section('content')
<div class="max-w-2xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in">
    
    <div class="mb-10 text-center relative">
        <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Mulai <span class="text-red-600">Berjualan</span></h1>
        <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-sm mx-auto">Pasang iklan barang atau jasa kamu ke seluruh warga Smecone.</p>
    </div>

    <form action="/marketplace/store" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-10 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] space-y-7 relative overflow-hidden">
        @csrf
        
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Foto Produk <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-center w-full group tap-effect">
                <label id="dropArea" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-[24px] cursor-pointer bg-gray-50 group-hover:bg-red-50 group-hover:border-red-300 transition-all duration-300 relative overflow-hidden text-center px-4">
                    
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 transition-transform group-hover:-translate-y-1">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-400 group-hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600">Klik atau Tarik Foto Kesini</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">PNG, JPG, JPEG (Maks. 2MB)</p>
                    </div>
                    
                    <input type="file" name="image" id="fileInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required />
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Barang / Jasa <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <input type="text" name="item_name" required placeholder="Misal: Es Krim Goreng, Jasa Gambar" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Harga Satuan <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 pointer-events-none font-black text-gray-500 text-[13px]">Rp</div>
                    <input type="number" name="price" required min="0" placeholder="5000" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-black text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Kategori <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="category" required class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 px-5 text-[14px] font-bold text-gray-800 appearance-none focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all cursor-pointer">
                        <option value="" disabled selected hidden>Pilih Kategori...</option>
                        <option value="Makanan">🍔 Makanan & Minuman</option>
                        <option value="Alat Tulis">✏️ Alat Tulis</option>
                        <option value="Elektronik">📱 Elektronik</option>
                        <option value="Jasa">🎨 Jasa (Desain, dll)</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Stok <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 pointer-events-none font-black text-gray-400 text-[13px]">📦</div>
                    <input type="number" name="stock" required min="1" placeholder="Contoh: 10" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-black text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Tipe <span class="text-red-500">*</span></label>
                <div class="flex bg-gray-50 p-1.5 rounded-[20px] border border-gray-200 relative">
                    <label class="flex-1 text-center cursor-pointer relative z-10">
                        <input type="radio" name="type" value="Ready Stock" class="peer sr-only" checked>
                        <div class="py-2.5 rounded-[16px] text-[12px] font-extrabold text-gray-500 transition-all peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm">
                            Ready
                        </div>
                    </label>
                    <label class="flex-1 text-center cursor-pointer relative z-10">
                        <input type="radio" name="type" value="Pre-Order" class="peer sr-only">
                        <div class="py-2.5 rounded-[16px] text-[12px] font-extrabold text-gray-500 transition-all peer-checked:bg-orange-500 peer-checked:text-white peer-checked:shadow-[0_4px_10px_rgba(249,115,22,0.3)]">
                            PO
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Lokasi COD / Posisi <span class="text-gray-300">(Opsional)</span></label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <input type="text" name="location" placeholder="Misal: Kelas XI PPLG 1, Kantin Bawah..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all">
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Deskripsi & Catatan Tambahan <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required placeholder="Jelaskan detail barang, varian rasa, atau jam buka PO..." 
                      class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all resize-none"></textarea>
        </div>

        <div class="h-px w-full bg-gray-100 my-2"></div>

        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4.5 rounded-[24px] font-black text-[15px] hover:from-red-700 hover:to-red-800 transition-all shadow-[0_8px_25px_rgba(220,38,38,0.3)] hover:shadow-[0_12px_30px_rgba(220,38,38,0.4)] hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect relative z-10 flex justify-center items-center gap-2 h-14">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            PASANG IKLAN SEKARANG
        </button>
        
        <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4">Pastikan data sesuai aturan Smecone</p>

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