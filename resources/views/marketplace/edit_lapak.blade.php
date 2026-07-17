@extends('layouts.app')
@section('title', '| Edit Lapak')

@section('content')
<div class="max-w-5xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 animate-page-in min-h-[85vh] flex flex-col">

    {{-- HEADER --}}
    <div class="flex items-center gap-3.5 mb-8">
        <a href="/marketplace/lapak-saya" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors tap-effect shrink-0 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-[19px] md:text-[24px] font-black text-gray-900 tracking-tight leading-tight uppercase">Edit Lapak ✨</h2>
            <p class="text-[12px] md:text-[14px] text-gray-500 font-medium mt-0.5 font-mono">Ubah logo dan banner jualanmu</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm relative z-10">
            <p class="font-bold">Gagal menyimpan perubahan:</p>
            <ul class="list-disc ml-5 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form x-data="{ logoPreview: null, bannerPreview: null }" action="{{ route('marketplace.updateStoreProfile') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-7 relative w-full">
        @csrf
        
        {{-- LOGO LAPAK --}}
        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Foto Profil Lapak / Logo</label>
            
            @if(auth()->user()->store_photo)
                <div x-show="!logoPreview" class="mb-3 flex items-center gap-3">
                    <img src="{{ asset('storage/' . auth()->user()->store_photo) }}" class="w-20 h-20 rounded-full object-cover border border-gray-200 shadow-sm">
                    <span class="text-[12px] text-gray-500 font-medium">Logo saat ini</span>
                </div>
            @endif

            <div class="flex items-center justify-center w-full group tap-effect">
                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-[24px] cursor-pointer bg-white hover:bg-red-50 hover:border-red-300 transition-all duration-300 relative overflow-hidden text-center">
                    
                    <div x-show="!logoPreview" class="flex flex-col items-center justify-center pt-5 pb-6 transition-transform group-hover:-translate-y-1">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-400 group-hover:text-red-500 transition-colors">
                            <i class='bx bx-image-add text-2xl'></i>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600">Klik atau Tarik Logo Baru Kesini</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Rasio 1:1, Maks 2MB</p>
                    </div>
                    
                    <img x-show="logoPreview" :src="logoPreview" class="w-40 h-40 object-cover rounded-full p-2" style="display: none;">
                    
                    <input type="file" name="store_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="logoPreview = URL.createObjectURL($event.target.files[0])" />
                </label>
            </div>
        </div>

        {{-- BANNER LAPAK --}}
        <div class="relative z-10">
            <label class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Banner Latar Belakang Lapak</label>
            
            @if(auth()->user()->store_banner)
                <div x-show="!bannerPreview" class="mb-3 flex flex-col gap-2">
                    <span class="text-[12px] text-gray-500 font-medium">Banner saat ini:</span>
                    <img src="{{ asset('storage/' . auth()->user()->store_banner) }}" class="w-full h-32 rounded-xl object-cover border border-gray-200 shadow-sm">
                </div>
            @endif

            <div class="flex items-center justify-center w-full group tap-effect">
                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-[24px] cursor-pointer bg-white hover:bg-red-50 hover:border-red-300 transition-all duration-300 relative overflow-hidden text-center">
                    
                    <div x-show="!bannerPreview" class="flex flex-col items-center justify-center pt-5 pb-6 transition-transform group-hover:-translate-y-1">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-400 group-hover:text-red-500 transition-colors">
                            <i class='bx bx-landscape text-2xl'></i>
                        </div>
                        <p class="text-[13px] font-extrabold text-gray-700 mb-1 group-hover:text-red-600">Klik atau Tarik Banner Baru Kesini</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Rasio 4:1 (Lanskap), Maks 4MB</p>
                    </div>
                    
                    <img x-show="bannerPreview" :src="bannerPreview" class="w-full h-full object-cover p-1 rounded-[24px]" style="display: none;">
                    
                    <input type="file" name="store_banner" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="bannerPreview = URL.createObjectURL($event.target.files[0])" />
                </label>
            </div>
        </div>

        <div class="h-px w-full bg-gray-100 my-2"></div>

        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4.5 rounded-[24px] font-black text-[15px] hover:from-red-700 hover:to-red-800 transition-all shadow-[0_8px_25px_rgba(220,38,38,0.3)] hover:shadow-[0_12px_30px_rgba(220,38,38,0.4)] hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] tap-effect relative z-10 flex justify-center items-center gap-2 h-14 mt-4">
            <i class='bx bx-check-circle text-xl'></i>
            SIMPAN LAPAK KE PUBLIK
        </button>
        
    </form>
</div>
@endsection
