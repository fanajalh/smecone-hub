@extends('layouts.app')
@section('title', '| Jual Barang')

@section('content')
<div class="max-w-5xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in">
    
    <div class="mb-10 text-center relative">
        <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-red-100">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Mulai <span class="text-red-600">Berjualan</span></h1>
        <p class="text-[14px] md:text-[15px] text-gray-500 font-bold max-w-md mx-auto">Pasang iklan barang atau jasa kamu ke seluruh warga Smecone.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm relative z-10">
            <p class="font-bold">Gagal menyimpan produk:</p>
            <ul class="list-disc ml-5 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div x-data="createForm()" class="relative">
        <!-- STEP 1: PILIH JENIS PRODUK -->
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-2xl mx-auto">
            <div class="bg-white p-8 md:p-12 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden text-center">
                <div class="absolute top-6 left-6 md:top-8 md:left-8 z-10">
                    <a href="{{ route('marketplace.lapak') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-gray-400 hover:text-red-600 transition-colors tap-effect">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Batal
                    </a>
                </div>
                <div class="absolute -left-20 -top-20 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2 mt-4 md:mt-0">Pilih Jenis Lapak</h2>
                <p class="text-gray-500 text-[14px] font-medium mb-8">Apa yang ingin kamu jual di Smecone Mart?</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <button type="button" @click="setFormat('Fisik')" class="group flex flex-col items-center justify-center p-8 bg-gray-50 rounded-[24px] border-2 border-gray-200 hover:border-red-400 hover:bg-red-50 hover:shadow-lg transition-all tap-effect relative overflow-hidden">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm text-gray-400 group-hover:text-red-500 group-hover:scale-110 transition-all">
                            <ion-icon name="cube-outline" class="text-4xl"></ion-icon>
                        </div>
                        <h3 class="text-[16px] font-extrabold text-gray-900 mb-1">Produk Fisik</h3>
                        <p class="text-[12px] text-gray-500 font-medium">Makanan, minuman, barang preloved, kerajinan tangan, dsb.</p>
                    </button>
                    
                    <button type="button" @click="setFormat('Digital')" class="group flex flex-col items-center justify-center p-8 bg-gray-50 rounded-[24px] border-2 border-gray-200 hover:border-purple-400 hover:bg-purple-50 hover:shadow-lg transition-all tap-effect relative overflow-hidden">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm text-gray-400 group-hover:text-purple-500 group-hover:scale-110 transition-all">
                            <ion-icon name="globe-outline" class="text-4xl"></ion-icon>
                        </div>
                        <h3 class="text-[16px] font-extrabold text-gray-900 mb-1">Produk Digital & Jasa</h3>
                        <p class="text-[12px] text-gray-500 font-medium">Desain grafis, joki tugas, e-book, source code, akun game.</p>
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 2: FORM INPUT -->
        <form x-show="step === 2" style="display: none;" action="/marketplace/store" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-10 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] space-y-7 relative overflow-hidden">
            <button type="button" @click="step = 1" class="inline-flex items-center gap-2 text-[13px] font-bold text-gray-500 hover:text-red-600 transition-colors mb-2 tap-effect relative z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </button>
            <input type="hidden" name="format" :value="format">
            
            <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6 relative z-10">Isi Detail <span class="text-red-600" x-text="format === 'Fisik' ? 'Produk Fisik' : 'Produk Digital'"></span></h2>
        @csrf
        
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Foto Produk <span class="text-red-500">*</span> <span class="text-gray-400 font-medium normal-case">(Maksimal 5 Foto)</span></label>
            <div class="w-full">
                <label id="dropArea" 
                       @dragover.prevent="dragOver = true" 
                       @dragleave.prevent="dragOver = false" 
                       @drop.prevent="handleDrop($event)"
                       :class="dragOver ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gray-50 hover:bg-red-50 hover:border-red-300'"
                       class="flex flex-col items-center justify-center w-full min-h-[12rem] border-2 border-dashed rounded-[24px] cursor-pointer transition-all duration-300 relative overflow-hidden text-center px-4 py-6 group tap-effect">
                    
                    <div x-show="images.length === 0" class="flex flex-col items-center justify-center pt-2 pb-2 transition-transform group-hover:-translate-y-1">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-400 group-hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600">Klik atau Tarik Foto Kesini</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">PNG, JPG, JPEG (Maks. 2MB/foto)</p>
                    </div>

                    <!-- Previews -->
                    <div x-show="images.length > 0" class="w-full grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" style="display: none;">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative group aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                                <img :src="image.url" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click.stop="removeImage(index)" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                <div x-show="index === 0" class="absolute top-2 left-2 bg-red-600 text-white text-[9px] font-black px-2 py-0.5 rounded shadow-sm">
                                    SAMPUL
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="images.length < 5" class="aspect-square rounded-2xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-300 hover:bg-red-50 transition-colors">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="text-[10px] font-bold">Tambah</span>
                        </div>
                    </div>
                    
                    <input type="file" name="image[]" id="fileInput" accept="image/*" multiple @change="handleFiles($event.target.files)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" :required="images.length === 0" />
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

        <div class="relative z-10" x-show="format === 'Digital'" style="display: none;">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Link File / G-Drive <span class="text-red-500">*</span></label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                </div>
                <input type="url" name="digital_link" :required="format === 'Digital'" placeholder="https://drive.google.com/..." 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all">
            </div>
        </div>

        <div class="relative z-10" x-show="format === 'Fisik'">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Varian / Topping <span class="text-gray-300">(Opsional)</span></label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400 pointer-events-none text-[13px]">🏷️</div>
                <input type="text" name="variants_config" placeholder="Koma (,) untuk pisah: Keju, Cokelat, Besar, Kecil" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 relative z-30" :class="format === 'Fisik' ? 'md:grid-cols-3' : 'md:grid-cols-2'">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Kategori <span class="text-red-500">*</span></label>
                <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                    <!-- Custom Select Button -->
                    <button type="button" @click="dropdownOpen = !dropdownOpen" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 px-5 text-[14px] font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all cursor-pointer flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <template x-if="!category">
                                <span class="text-gray-400 font-normal">Pilih Kategori...</span>
                            </template>
                            <template x-if="category">
                                <div class="flex items-center gap-2">
                                    <ion-icon :name="getCategoryIcon(category)" class="text-xl" :class="format === 'Digital' ? 'text-purple-500' : 'text-red-500'"></ion-icon>
                                    <span x-text="category"></span>
                                </div>
                            </template>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Hidden input for form submission -->
                    <input type="hidden" name="category" x-model="category" required>

                    <!-- Dropdown Options -->
                    <div x-show="dropdownOpen" x-transition.opacity.duration.200ms
                         class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] py-2 overflow-hidden" style="display: none;">
                        
                        <template x-if="format === 'Fisik'">
                            <div>
                                <div class="px-5 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50 mb-1">Pilihan Produk Fisik</div>
                                <template x-for="cat in [
                                    {val: 'Makanan', label: 'Makanan & Minuman', icon: 'fast-food-outline'},
                                    {val: 'Alat Tulis', label: 'Alat Tulis', icon: 'pencil-outline'},
                                    {val: 'Elektronik', label: 'Elektronik', icon: 'hardware-chip-outline'},
                                    {val: 'Lainnya', label: 'Lainnya', icon: 'cube-outline'}
                                ]" :key="cat.val">
                                    <button type="button" @click="category = cat.val; dropdownOpen = false" 
                                            class="w-full text-left px-5 py-2.5 text-[14px] font-bold text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center gap-3">
                                        <ion-icon :name="cat.icon" class="text-xl" :class="category === cat.val ? 'text-red-500' : 'text-gray-400'"></ion-icon>
                                        <span x-text="cat.label"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <template x-if="format === 'Digital'">
                            <div>
                                <div class="px-5 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50 mb-1">Pilihan Produk Digital / Jasa</div>
                                <template x-for="cat in [
                                    {val: 'Jasa', label: 'Jasa (Desain, Tugas, dll)', icon: 'color-palette-outline'},
                                    {val: 'Produk Digital', label: 'Produk Digital (Akun, Ebook)', icon: 'laptop-outline'},
                                    {val: 'Lainnya', label: 'Lainnya', icon: 'cube-outline'}
                                ]" :key="cat.val">
                                    <button type="button" @click="category = cat.val; dropdownOpen = false" 
                                            class="w-full text-left px-5 py-2.5 text-[14px] font-bold text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors flex items-center gap-3">
                                        <ion-icon :name="cat.icon" class="text-xl" :class="category === cat.val ? 'text-purple-500' : 'text-gray-400'"></ion-icon>
                                        <span x-text="cat.label"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

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

            <div x-show="format === 'Fisik'">
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

        <div class="relative z-10" x-show="format === 'Fisik'">
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
</div>

<!-- Include Alpine JS -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('createForm', () => ({
            step: 1,
            format: '{{ old("format", "Fisik") }}',
            category: '{{ old("category", "") }}',
            images: [],
            dragOver: false,
            
            getCategoryIcon(cat) {
                const map = {
                    'Makanan': 'fast-food-outline',
                    'Alat Tulis': 'pencil-outline',
                    'Elektronik': 'hardware-chip-outline',
                    'Jasa': 'color-palette-outline',
                    'Produk Digital': 'laptop-outline',
                    'Lainnya': 'cube-outline'
                };
                return map[cat] || 'cube-outline';
            },
            
            setFormat(type) {
                this.format = type;
                this.step = 2;
                this.category = ''; // Reset kategori ketika format diubah
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            
            handleFiles(files) {
                if (files.length === 0) return;
                
                // Convert FileList to array
                const newFiles = Array.from(files);
                
                // Limit to 5 files max
                const remainingSlots = 5 - this.images.length;
                if (newFiles.length > remainingSlots) {
                    alert('Maksimal 5 foto produk yang diizinkan!');
                    newFiles.splice(remainingSlots);
                }
                
                newFiles.forEach(file => {
                    // Check file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`File ${file.name} terlalu besar! Maksimal 2MB.`);
                        return;
                    }
                    
                    // Generate preview URL
                    const url = URL.createObjectURL(file);
                    this.images.push({
                        file: file,
                        url: url,
                        name: file.name
                    });
                });
                
                this.updateFileInput();
            },
            
            handleDrop(e) {
                this.dragOver = false;
                if (e.dataTransfer.files) {
                    this.handleFiles(e.dataTransfer.files);
                }
            },
            
            removeImage(index) {
                URL.revokeObjectURL(this.images[index].url);
                this.images.splice(index, 1);
                this.updateFileInput();
            },
            
            updateFileInput() {
                // To sync Alpine array with the actual form input, we use a DataTransfer object
                const dt = new DataTransfer();
                this.images.forEach(img => {
                    dt.items.add(img.file);
                });
                document.getElementById('fileInput').files = dt.files;
            }
        }));
    });
</script>
@endsection