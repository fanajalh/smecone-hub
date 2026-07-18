@extends('layouts.app')
@section('title', '| Buat Repositori')

@section('content')
<div class="bg-white min-h-screen pt-24 md:pt-32 pb-32 animate-page-in font-sans">
    
    <div class="mb-10 text-center relative px-4">
        <div class="w-16 h-16 bg-red-50 text-[#E21F26] rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
            <ion-icon name="folder-open" class="text-3xl"></ion-icon>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Simpan <span class="text-[#E21F26]">Karyamu</span></h1>
        <p class="text-[13px] md:text-sm text-gray-500 font-medium max-w-sm mx-auto">Buat repositori digital untuk menyimpan tugas akhir atau proyek kreatif kamu.</p>
    </div>

    @if ($errors->any())
        <div class="max-w-4xl mx-auto px-4 md:px-8 mb-6">
            <div class="bg-red-50 border border-red-100 text-red-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                    <ion-icon name="warning" class="text-xl text-red-600"></ion-icon>
                </div>
                <span>Mohon periksa kembali inputan Anda.</span>
            </div>
        </div>
    @endif

    <form action="/repository" method="POST" class="max-w-4xl mx-auto px-4 md:px-8 space-y-8 relative overflow-hidden">
        @csrf
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="flex flex-col md:flex-row items-start md:items-end gap-5 relative z-10">
            <div class="w-full md:w-1/3">
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Pemilik</label>
                <div class="bg-gray-100 border border-gray-200 rounded-[20px] px-5 py-4 text-[14px] text-gray-600 font-extrabold shadow-inner flex items-center gap-3">
                    <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[10px] text-gray-400 border border-gray-200">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    {{ explode(' ', auth()->user()->name)[0] }}
                </div>
            </div>
            <span class="text-3xl text-gray-200 font-light px-2 mb-3 hidden md:block">/</span>
            <div class="w-full md:flex-1">
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Repositori <span class="text-[#E21F26]">*</span></label>
                <input type="text" name="name" required placeholder="misal: tugas-akhir-web" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-[#E21F26] transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-50">
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Kategori / Jurusan <span class="text-[#E21F26]">*</span></label>
                <div class="relative" x-data="{ 
                    open: false, 
                    selectedLabel: 'Pilih Jurusan...', 
                    selectedValue: '',
                    options: [
                        { value: 'PPLG', label: '💻 PPLG (RPL)' },
                        { value: 'TJKT', label: '📡 TJKT (TKJ)' },
                        { value: 'DKV', label: '🎨 DKV (Multimedia)' },
                        { value: 'AKL', label: '📈 AKL (Akuntansi)' },
                        { value: 'MPLB', label: '🏢 MPLB (Perkantoran)' },
                        { value: 'PM', label: '🛍️ PM (Pemasaran)' },
                        { value: 'TF', label: '💊 TF (Farmasi)' },
                        { value: 'Umum', label: '📚 Umum / Lainnya' }
                    ]
                }">
                    <input type="hidden" name="major" x-model="selectedValue" required>
                    <button type="button" @click="open = !open" @click.outside="open = false" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 px-5 text-[14px] font-bold flex justify-between items-center focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-[#E21F26] transition-all cursor-pointer">
                        <span x-text="selectedLabel" :class="selectedValue === '' ? 'text-gray-400' : 'text-gray-800'"></span>
                        <ion-icon name="chevron-down-outline" class="text-lg text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                    </button>
                    
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-[-10px]"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-[-10px]"
                         class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-[20px] shadow-[0_15px_40px_rgba(0,0,0,0.12)] py-2 overflow-hidden">
                        <template x-for="option in options" :key="option.value">
                            <div @click="selectedValue = option.value; selectedLabel = option.label; open = false;"
                                 :class="selectedValue === option.value ? 'bg-red-50 text-[#E21F26]' : 'text-gray-700 hover:bg-gray-50'"
                                 class="px-5 py-3.5 text-[14px] font-bold cursor-pointer transition-colors flex items-center justify-between group">
                                <span x-text="option.label"></span>
                                <ion-icon name="checkmark-circle" class="text-lg text-[#E21F26]" x-show="selectedValue === option.value"></ion-icon>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Link Demo <span class="text-gray-300">(Opsional)</span></label>
                <div class="relative flex items-center">
                    <div class="absolute left-5 text-gray-400 pointer-events-none flex items-center">
                        <ion-icon name="link-outline" class="text-xl"></ion-icon>
                    </div>
                    <input type="url" name="demo_link" placeholder="https://youtube.com/..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-[20px] py-4 pl-12 pr-5 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-[#E21F26] transition-all">
                </div>
            </div>
        </div>

        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Deskripsi Proyek <span class="text-gray-300">(Opsional)</span></label>
            <textarea name="description" rows="4" placeholder="Jelaskan secara singkat tentang karya ini..." 
                      class="w-full bg-gray-50 border border-gray-200 rounded-[24px] py-5 px-6 text-[14px] font-bold text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-[#E21F26] transition-all resize-none"></textarea>
        </div>

        <div class="space-y-4 relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Visibilitas</label>
            
            <label class="flex items-start gap-4 cursor-pointer p-5 bg-gray-50 border border-gray-200 rounded-[24px] hover:bg-white hover:border-red-300 transition-all group tap-effect">
                <input type="radio" name="visibility" value="public" checked class="mt-1.5 text-[#E21F26] focus:ring-[#E21F26] border-gray-300">
                <div>
                    <div class="flex items-center gap-2">
                        <ion-icon name="globe-outline" class="text-xl text-gray-400 group-hover:text-[#E21F26] transition-colors"></ion-icon>
                        <span class="font-black text-[15px] text-gray-800 uppercase tracking-tight">Publik</span>
                    </div>
                    <p class="text-[12px] text-gray-500 mt-1 font-bold">Semua orang di Smecone bisa melihat dan mengunduh karyamu.</p>
                </div>
            </label>

            <label class="flex items-start gap-4 cursor-pointer p-5 bg-gray-50 border border-gray-200 rounded-[24px] hover:bg-white hover:border-red-300 transition-all group tap-effect">
                <input type="radio" name="visibility" value="private" class="mt-1.5 text-[#E21F26] focus:ring-[#E21F26] border-gray-300">
                <div>
                    <div class="flex items-center gap-2">
                        <ion-icon name="lock-closed-outline" class="text-xl text-gray-400 group-hover:text-[#E21F26] transition-colors"></ion-icon>
                        <span class="font-black text-[15px] text-gray-800 uppercase tracking-tight">Privat</span>
                    </div>
                    <p class="text-[12px] text-gray-500 mt-1 font-bold">Hanya kamu yang dapat mengelola repositori ini.</p>
                </div>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-[#111827] text-white py-5 rounded-[24px] font-black text-[15px] hover:bg-[#E21F26] transition-all shadow-xl hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect relative z-10 flex justify-center items-center gap-2 uppercase tracking-widest">
                <ion-icon name="add-outline" class="text-xl"></ion-icon>
                Buat Repositori
            </button>
        </div>

    </form>
</div>
@endsection