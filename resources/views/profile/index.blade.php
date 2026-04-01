@extends('layouts.app')
@section('title', '| Profil')

@section('content')
<div class="max-w-4xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    {{-- HEADER CARD --}}
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-8 flex flex-col md:flex-row md:justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center justify-center bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 md:hidden">Profil Saya</div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan <span class="text-red-600">Profil</span></h1>
            <p class="text-[13px] md:text-sm text-gray-500 mt-1.5 font-medium">Kelola informasi akun dan identitas digital kamu.</p>
        </div>
        
        <a href="/dashboard" class="bg-gray-100 text-gray-700 px-5 py-3 rounded-xl font-extrabold text-[13px] hover:bg-gray-200 transition-all active:scale-95 flex items-center gap-2 relative z-10 tap-effect">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-[20px] text-[13px] font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        {{-- AVATAR SECTION --}}
        <div class="bg-white rounded-[32px] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 text-center flex flex-col items-center">
            <label for="avatar_upload" class="cursor-pointer group relative block w-24 h-24 md:w-32 md:h-32 mb-4">
                <div class="w-full h-full rounded-[32px] bg-red-50 flex items-center justify-center text-4xl font-black text-red-500 shadow-inner border-4 border-white overflow-hidden relative group-hover:scale-105 transition-transform duration-500">
                    @if(auth()->user()->avatar)
                        <img id="avatar_preview" src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <img id="avatar_preview" src="" class="w-full h-full object-cover hidden">
                        <span id="avatar_initial" class="relative z-0 uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="absolute inset-0 bg-black/40 rounded-[32px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </div>
            </label>
            <input type="file" id="avatar_upload" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(event)">
            <p class="text-[10px] text-gray-400 mt-2 font-medium">Format: JPG/PNG/WebP. Maks ukuran 5MB.</p>
            @error('avatar')
                <div class="mt-2 bg-red-50 text-red-600 text-[11px] font-bold px-3 py-1.5 rounded-lg border border-red-100 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $message }}
                </div>
            @enderror

            <h2 class="text-xl font-extrabold text-gray-900 leading-tight">{{ auth()->user()->name }}</h2>
            <p class="text-[13px] text-gray-500 font-medium mt-1">{{ auth()->user()->email }}</p>
            
            <div class="flex items-center gap-2 mt-4">
                <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">
                    {{ auth()->user()->is_admin ? 'Administrator' : 'Siswa' }}
                </span>
                <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">
                    Smecone Member
                </span>
            </div>
        </div>

        {{-- FORM FIELDS --}}
        <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[13px] font-extrabold text-gray-700 ml-1">Nama Lengkap</label>
                    <div class="relative tap-effect">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-[14px] font-bold text-gray-800 transition-all">
                    </div>
                    @error('name') <p class="text-[11px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[13px] font-extrabold text-gray-700 ml-1">Alamat Email</label>
                    <div class="relative tap-effect">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-[14px] font-bold text-gray-800 transition-all">
                    </div>
                    @error('email') <p class="text-[11px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-50">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Keamanan Akun</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[13px] font-extrabold text-gray-700 ml-1">Password Baru</label>
                        <div class="relative tap-effect">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" name="password" placeholder="Kosongkan jika tetap"
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-[14px] font-bold text-gray-800 transition-all placeholder:font-medium placeholder:text-gray-300">
                        </div>
                        @error('password') <p class="text-[11px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[13px] font-extrabold text-gray-700 ml-1">Konfirmasi Password</label>
                        <div class="relative tap-effect">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <input type="password" name="password_confirmation" placeholder="Masukan ulang password"
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 text-[14px] font-bold text-gray-800 transition-all placeholder:font-medium placeholder:text-gray-300">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4 items-center">
                <button type="submit" class="w-full md:w-auto bg-red-600 text-white px-10 py-4 rounded-[20px] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all active:scale-95 font-black text-[14px] tap-effect flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    {{-- LOGOUT CARD --}}
    <div class="mt-8 bg-white rounded-[32px] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 border border-red-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <div>
                <h3 class="text-[15px] font-extrabold text-gray-900">Keluar Sesi</h3>
                <p class="text-[12px] text-gray-500 font-medium">Selesaikan sesi aktif kamu di perangkat ini.</p>
            </div>
        </div>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-extrabold text-[13px] hover:bg-black transition-all active:scale-95 tap-effect">
                Logout
            </button>
        </form>
    </div>

</div>

<script>
    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewElement = document.getElementById('avatar_preview');
                const initialElement = document.getElementById('avatar_initial');
                
                previewElement.src = e.target.result;
                previewElement.classList.remove('hidden');
                
                if (initialElement) {
                    initialElement.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection