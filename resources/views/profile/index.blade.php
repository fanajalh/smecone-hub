@extends('layouts.app')

@section('title', 'Profil Saya - Smecone Hub')

@section('content')
<div class="bg-[#F8F9FA] min-h-screen pt-4 lg:pt-36 pb-24 md:pb-12 relative overflow-hidden">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-br from-[#E21F26] via-[#B8161D] to-[#8F0E14] -skew-y-3 origin-top-left z-0 shadow-2xl"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 z-0 pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        
        <div class="max-w-4xl mx-auto">
            {{-- Header Profil --}}
            <div class="mb-8 text-center lg:text-left text-white">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Pengaturan Profil ⚙️</h1>
                <p class="text-red-100 font-medium text-lg drop-shadow-sm">Kelola informasi pribadi dan data toko Anda di Smecone Hub.</p>
            </div>

            @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-4 animate-[fadeIn_0.5s_ease-out] shadow-sm backdrop-blur-md">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-800">Berhasil!</h4>
                    <p class="text-sm opacity-90">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <form action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data" class="bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-red-900/10 border border-white/50 p-6 md:p-10 overflow-hidden relative">
                @csrf
                
                <div class="flex flex-col lg:flex-row gap-10">
                    
                    {{-- Kolom Kiri: Foto Profil --}}
                    <div class="w-full lg:w-1/3 flex flex-col items-center">
                        <div class="relative group cursor-pointer mb-6">
                            <div class="w-40 h-40 rounded-full border-4 border-white shadow-xl overflow-hidden bg-gray-100 ring-4 ring-gray-50/50 transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl group-hover:shadow-[#E21F26]/20">
                                @if(auth()->user()->avatar)
                                    <img id="avatar-preview" src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E21F26&color=fff&size=256&bold=true" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <svg class="w-8 h-8 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-white text-xs font-semibold">Ubah Foto</span>
                                </div>
                            </div>
                            
                            {{-- Input File Hidden --}}
                            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>
                        
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h3>
                            <p class="text-gray-500 text-sm font-medium">{{ auth()->user()->email }}</p>
                            
                            <div class="mt-4 flex items-center justify-center gap-2">
                                <span class="px-3 py-1 bg-red-100 text-[#E21F26] rounded-full text-xs font-bold uppercase tracking-wider">
                                    {{ auth()->user()->is_teacher ? 'Guru / Staff' : 'Siswa' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent my-8 lg:hidden"></div>
                    </div>
                    
                    {{-- Kolom Kanan: Form Data --}}
                    <div class="w-full lg:w-2/3 space-y-6">
                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#E21F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Data Diri
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#E21F26]/20 focus:border-[#E21F26] focus:bg-white transition-all shadow-sm" required>
                                    </div>
                                </div>
                                
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#E21F26]/20 focus:border-[#E21F26] focus:bg-white transition-all shadow-sm" required>
                                    </div>
                                </div>
                                
                                <div class="space-y-1.5 md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700">Nomor WhatsApp (Aktif)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <input type="text" name="whatsapp_number" value="{{ auth()->user()->whatsapp_number }}" placeholder="Contoh: 081234567890" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#E21F26]/20 focus:border-[#E21F26] focus:bg-white transition-all shadow-sm">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 ml-1">Nomor WA digunakan jika ada pembeli yang menghubungi toko Anda.</p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-px bg-gray-100 my-2"></div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Pengaturan Toko
                            </h3>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Nama Toko / Bisnis</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <input type="text" name="store_name" value="{{ auth()->user()->store_name }}" placeholder="Biarkan kosong jika tidak ingin membuka toko" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#E21F26]/20 focus:border-[#E21F26] focus:bg-white transition-all shadow-sm">
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 flex flex-col-reverse md:flex-row gap-3 justify-end items-center">
                            <button type="reset" class="w-full md:w-auto px-6 py-3 rounded-xl font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="w-full md:w-auto bg-[#E21F26] hover:bg-[#B8161D] text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-[#E21F26]/30 hover:shadow-xl hover:shadow-[#E21F26]/40 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                        
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script>
    // Script untuk membuka file explorer ketika gambar diklik
    document.querySelector('.group').addEventListener('click', function() {
        document.getElementById('avatar-input').click();
    });

    // Script untuk preview gambar secara live sebelum diupload
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('avatar-preview');
            output.src = reader.result;
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection
