@extends('layouts.app')

@section('title', 'Profil Saya - Smecone Hub')

@section('content')
<div class="bg-[#F8F9FA] min-h-screen pt-6 lg:pt-36 pb-24 md:pb-12 relative overflow-hidden">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-0 w-full h-80 md:h-96 bg-gradient-to-br from-[#E21F26] via-[#B8161D] to-[#8F0E14] -skew-y-3 origin-top-left z-0 shadow-xl"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 z-0 pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            
            {{-- Header Profil --}}
            <div class="mb-6 md:mb-8 text-center lg:text-left text-white">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight drop-shadow-sm">Profil</h1>
            </div>

            {{-- Alert Berhasil --}}
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-xs md:text-sm font-bold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Alert Error --}}
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl flex items-start gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-xs md:text-sm">
                    <p class="font-bold mb-1">Mohon periksa kembali:</p>
                    <ul class="list-disc list-inside space-y-0.5 opacity-90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Card Utama Profil --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-100 p-8 md:p-12 lg:p-14 relative">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                    
                    {{-- Kolom Kiri: Avatar & Info Akun --}}
                    <div class="lg:col-span-4 flex flex-col items-center text-center lg:border-r lg:border-gray-100 lg:pr-12">
                        <div class="relative group cursor-pointer mb-4" onclick="document.getElementById('avatar-input').click()">
                            <div class="w-32 h-32 md:w-36 md:h-36 rounded-full border-4 border-white shadow-xl overflow-hidden bg-gray-50 ring-4 ring-gray-100/70 transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl group-hover:shadow-red-500/10">
                                @if(auth()->user()->avatar)
                                    <img id="avatar-preview" src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E21F26&color=fff&size=256&bold=true" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <svg class="w-6 h-6 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-white text-[11px] font-bold">Ubah Foto</span>
                                </div>
                            </div>
                            
                            {{-- Input File Hidden --}}
                            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>
                        
                        <h3 class="text-lg font-black text-gray-900 leading-snug">{{ auth()->user()->name }}</h3>
                        <p class="text-xs font-semibold text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                        
                        <div class="mt-3 flex flex-wrap items-center justify-center gap-1.5">
                            <span class="px-2.5 py-0.5 bg-red-50 text-[#E21F26] border border-red-100 rounded-full text-[10px] font-black uppercase tracking-wider">
                                {{ auth()->user()->is_teacher ? 'Guru / Staff' : 'Siswa' }}
                            </span>
                            @if(auth()->user()->store_name)
                                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    Penjual
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Kolom Kanan: Form Fields --}}
                    <div class="lg:col-span-8 flex flex-col justify-center space-y-7 md:space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-7">
                            {{-- Nama Lengkap --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full pl-11 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs md:text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition text-left" required>
                                </div>
                            </div>
                            
                            {{-- Alamat Email --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Alamat Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full pl-11 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs md:text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition text-left" required>
                                </div>
                            </div>

                            @if(auth()->user()->store_name || auth()->user()->whatsapp_number)
                                {{-- Nomor WhatsApp --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-700">Nomor WhatsApp</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <input type="text" name="whatsapp_number" value="{{ auth()->user()->whatsapp_number }}" placeholder="08..." class="w-full pl-11 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs md:text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition text-left">
                                    </div>
                                </div>

                                {{-- Nama Toko --}}
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-bold text-gray-700">Nama Toko</label>
                                        <a href="{{ route('marketplace.lapak') }}" class="text-[11px] font-bold text-[#E21F26] hover:underline">
                                            Buka Lapak Saya →
                                        </a>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-orange-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <input type="text" name="store_name" value="{{ auth()->user()->store_name }}" placeholder="Nama Toko Anda" class="w-full pl-11 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs md:text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition text-left">
                                    </div>
                                </div>
                            @else
                                {{-- Jika belum punya lapak --}}
                                <div class="md:col-span-2 p-3.5 bg-gradient-to-r from-red-50/50 via-orange-50/30 to-gray-50 border border-red-100 rounded-xl flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 text-[#E21F26] flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-900">Belum Punya Lapak?</h4>
                                            <p class="text-[11px] text-gray-500">Buka lapak & tautkan nomor WA untuk mulai jualan.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('marketplace.lapak') }}" class="shrink-0 px-3.5 py-1.5 bg-[#E21F26] hover:bg-[#B8161D] text-white text-[11px] font-bold rounded-lg shadow-xs transition">
                                        + Daftar Lapak
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Tombol Aksi Bawah --}}
                        <div class="pt-8 mt-2 border-t border-gray-100 flex items-center justify-between gap-3">
                            {{-- Ganti Sandi --}}
                            <button type="button" onclick="openPasswordModal()" class="px-4 py-2.5 rounded-xl font-bold text-xs text-gray-700 bg-gray-100 hover:bg-gray-200 transition inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>Ganti Sandi</span>
                            </button>

                            {{-- Batal & Simpan --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ url('/') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                                    Batal
                                </a>
                                <button type="submit" class="bg-[#E21F26] hover:bg-[#B8161D] text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-red-500/25 hover:shadow-lg transition flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Simpan Perubahan</span>
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

{{-- MODAL GANTI KATA SANDI --}}
<div id="passwordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300 p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full p-6 md:p-8 transform scale-95 transition-all duration-300" id="passwordModalContent">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-[#E21F26]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Ganti Kata Sandi</h3>
                    <p class="text-[11px] text-gray-500">Masukkan kata sandi lama dan baru.</p>
                </div>
            </div>
            <button type="button" onclick="closePasswordModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition" required placeholder="Kata sandi lama">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700">Kata Sandi Baru</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition" required placeholder="Minimal 6 karakter">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E21F26]/10 focus:border-[#E21F26] focus:bg-white transition" required placeholder="Ulangi kata sandi baru">
            </div>

            <div class="pt-4 flex items-center justify-end gap-2 border-t border-gray-100">
                <button type="button" onclick="closePasswordModal()" class="px-4 py-2 rounded-xl font-bold text-xs text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" class="bg-[#E21F26] hover:bg-[#B8161D] text-white px-5 py-2 rounded-xl font-bold text-xs shadow-md shadow-red-500/20 hover:shadow-lg transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Perbarui Sandi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview gambar avatar
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

    // Modal Ganti Sandi Controls
    function openPasswordModal() {
        const modal = document.getElementById('passwordModal');
        const content = document.getElementById('passwordModalContent');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }

    function closePasswordModal() {
        const modal = document.getElementById('passwordModal');
        const content = document.getElementById('passwordModalContent');
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
    }

    // Tutup modal jika klik di luar box
    document.getElementById('passwordModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePasswordModal();
        }
    });
</script>
@endsection
