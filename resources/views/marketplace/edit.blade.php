@extends('layouts.app')
@section('title', '| Edit Produk')

@section('content')
<div class="max-w-2xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in">
    
    <div class="mb-10 text-center relative">
        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-amber-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Edit <span class="text-amber-600">Produk</span></h1>
        <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-sm mx-auto">Perbarui detail barang atau jasa kamu.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <form x-data="{ format: '{{ old('format', $product->format ?? 'Fisik') }}' }" action="{{ route('marketplace.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-10 rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] space-y-7 relative overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="absolute -right-20 -top-20 w-40 h-40 bg-amber-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        {{-- FOTO PRODUK --}}
        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Foto Produk <span class="text-gray-300">(Kosongkan jika tidak ingin ganti)</span></label>
            
            @if($product->image)
                <div class="mb-3 flex items-center gap-3">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200 shadow-sm">
                    <span class="text-[12px] text-gray-500 font-medium">Foto saat ini</span>
                </div>
            @endif

            <div class="flex items-center justify-center w-full group tap-effect">
                <label id="dropArea" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-[24px] cursor-pointer bg-gray-50 group-hover:bg-amber-50 group-hover:border-amber-300 transition-all duration-300 relative overflow-hidden text-center px-4">
                    <div class="flex flex-col items-center justify-center py-4 transition-transform group-hover:-translate-y-1">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-2 text-gray-400 group-hover:text-amber-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[12px] font-extrabold text-gray-600 group-hover:text-amber-600">Ganti Foto (Opsional)</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">PNG, JPG, JPEG (Maks. 2MB)</p>
                    </div>
                    <input type="file" name="image" id="fileInput" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </label>
            </div>
        </div>

        {{-- NAMA & HARGA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Barang / Jasa <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <input type="text" name="item_name" value="{{ old('item_name', $product->item_name) }}" required 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Harga Satuan <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 pointer-events-none font-black text-gray-500 text-[13px]">Rp</div>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-black text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                </div>
            </div>
        </div>

        {{-- FORMAT PRODUK & VARIAN/LINK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Format Produk <span class="text-red-500">*</span></label>
                <div class="flex bg-gray-50 p-1.5 rounded-[20px] border border-gray-200 relative">
                    <label class="flex-1 text-center cursor-pointer relative z-10">
                        <input type="radio" name="format" value="Fisik" x-model="format" class="peer sr-only">
                        <div class="py-3.5 rounded-[16px] text-[13px] font-extrabold text-gray-400 transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm">
                            📦 Fisik
                        </div>
                    </label>
                    <label class="flex-1 text-center cursor-pointer relative z-10">
                        <input type="radio" name="format" value="Digital" x-model="format" class="peer sr-only">
                        <div class="py-3.5 rounded-[16px] text-[13px] font-extrabold text-gray-400 transition-all peer-checked:bg-white peer-checked:text-purple-600 peer-checked:shadow-sm">
                            🌐 Digital
                        </div>
                    </label>
                </div>
            </div>

            <div x-show="format === 'Digital'" style="display: none;">
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Link File / G-Drive <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <input type="url" name="digital_link" value="{{ old('digital_link', $product->digital_link) }}" :required="format === 'Digital'" placeholder="https://drive.google.com/..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all">
                </div>
            </div>

            <div x-show="format === 'Fisik'">
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Varian / Topping <span class="text-gray-300">(Opsional)</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 pointer-events-none text-[13px]">🏷️</div>
                    @php $variantsStr = $product->variants_config ? implode(', ', json_decode($product->variants_config, true) ?: []) : ''; @endphp
                    <input type="text" name="variants_config" value="{{ old('variants_config', $variantsStr) }}" placeholder="Koma (,) untuk pisah: Keju, Cokelat, Besar" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                </div>
            </div>
        </div>

        {{-- KATEGORI, STOK, TIPE --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Kategori <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="category" x-on:change="$event.target.value === 'Produk Digital' ? format = 'Digital' : ''" required class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 px-5 text-[14px] font-bold text-gray-800 appearance-none focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all cursor-pointer">
                        @php $cats = ['Makanan' => '🍔 Makanan & Minuman', 'Alat Tulis' => '✏️ Alat Tulis', 'Elektronik' => '📱 Elektronik', 'Jasa' => '🎨 Jasa (Desain, dll)', 'Produk Digital' => '💻 Produk Digital', 'Lainnya' => '📦 Lainnya']; @endphp
                        @foreach($cats as $val => $label)
                            <option value="{{ $val }}" {{ $product->category == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
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
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-black text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Tipe <span class="text-red-500">*</span></label>
                <div class="flex bg-gray-50 p-1.5 rounded-[20px] border border-gray-200 relative">
                    <label class="flex-1 text-center cursor-pointer relative z-10">
                        <input type="radio" name="type" value="Ready Stock" class="peer sr-only" {{ $product->type == 'Ready Stock' ? 'checked' : '' }}>
                        <div class="py-2.5 rounded-[16px] text-[12px] font-extrabold text-gray-500 transition-all peer-checked:bg-white peer-checked:text-amber-600 peer-checked:shadow-sm">
                            Ready
                        </div>
                    </label>
                    <label class="flex-1 text-center cursor-pointer relative z-10">
                        <input type="radio" name="type" value="Pre-Order" class="peer sr-only" {{ $product->type == 'Pre-Order' ? 'checked' : '' }}>
                        <div class="py-2.5 rounded-[16px] text-[12px] font-extrabold text-gray-500 transition-all peer-checked:bg-orange-500 peer-checked:text-white peer-checked:shadow-[0_4px_10px_rgba(249,115,22,0.3)]">
                            PO
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- LOKASI --}}
        <div class="relative z-10" x-show="format === 'Fisik'">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Lokasi COD / Posisi <span class="text-gray-300">(Opsional)</span></label>
            <div class="relative flex items-center">
                <div class="absolute left-4 text-gray-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <input type="text" name="location" value="{{ old('location', $product->location) }}" placeholder="Misal: Kelas XI PPLG 1, Kantin Bawah..."
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-3.5 pl-11 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
            </div>
        </div>

        {{-- DESKRIPSI --}}
        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Deskripsi & Catatan Tambahan <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required placeholder="Jelaskan detail barang..."
                      class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all resize-none">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="h-px w-full bg-gray-100 my-2"></div>

        <div class="flex gap-3 relative z-10">
            <a href="/marketplace/lapak-saya" class="flex-1 text-center bg-gray-100 text-gray-700 py-4 rounded-[24px] font-bold text-[14px] hover:bg-gray-200 transition-all active:scale-[0.98] tap-effect">
                Batal
            </a>
            <button type="submit" class="flex-[2] bg-gradient-to-r from-amber-500 to-amber-600 text-white py-4 rounded-[24px] font-black text-[15px] hover:from-amber-600 hover:to-amber-700 transition-all shadow-[0_8px_25px_rgba(245,158,11,0.3)] hover:shadow-[0_12px_30px_rgba(245,158,11,0.4)] hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                SIMPAN PERUBAHAN
            </button>
        </div>
        
        <p class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-4">Pastikan data sesuai aturan Smecone</p>
    </form>
</div>

<!-- Include Alpine JS -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

<script>
    const fileInput = document.getElementById('fileInput');
    const dropArea = document.getElementById('dropArea');

    fileInput.addEventListener('change', function() {
        if(this.files && this.files.length > 0) {
            const textEl = dropArea.querySelector('.text-gray-600, .font-extrabold');
            if(textEl) textEl.textContent = "Foto terpilih: " + this.files[0].name;
            dropArea.classList.add('border-amber-400', 'bg-amber-50');
        }
    });
</script>
@endsection
