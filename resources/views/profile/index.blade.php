@extends('layouts.app')

@section('content')
<style>
    .tap-effect:active { transform: scale(0.95); transition: transform 0.1s ease; }
</style>

<div class="max-w-xl mx-auto px-4 sm:px-6 py-6 pb-32 md:pb-12">

    {{-- Profile Header Card --}}
    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-[28px] p-6 md:p-8 text-white text-center relative overflow-hidden shadow-[0_8px_30px_rgba(220,38,38,0.25)] mb-6">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
        <div class="absolute -left-8 -bottom-8 w-32 h-32 bg-red-400 opacity-20 rounded-full blur-xl"></div>
        
        <div class="relative z-10">
            <label for="avatar_upload" class="cursor-pointer group relative block w-20 h-20 md:w-24 md:h-24 mx-auto mb-3">
                <div class="w-full h-full rounded-full bg-white/20 border-[3px] border-white/50 flex items-center justify-center text-3xl md:text-4xl font-black backdrop-blur-sm shadow-lg uppercase overflow-hidden relative">
                    {{-- Tampilkan avatar jika ada, jika tidak inisial huruf --}}
                    @if(auth()->user()->avatar)
                        <img id="avatar_preview" src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover relative z-10">
                    @else
                        <img id="avatar_preview" src="" class="w-full h-full object-cover relative z-10 hidden">
                        <span id="avatar_initial" class="relative z-0">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    @endif
                </div>
                {{-- Overlay Camera Icon --}}
                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </label>
            <h1 class="text-lg md:text-xl font-extrabold tracking-tight">{{ auth()->user()->name }}</h1>
            <p class="text-[12px] md:text-sm text-white/70 font-medium mt-0.5">{{ auth()->user()->email }}</p>
            <div class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-md px-3 py-1 rounded-full mt-3 border border-white/20 relative z-30">
                <svg class="w-3.5 h-3.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.441A3 3 0 018.53 2h2.94a3 3 0 012.263 1.441l1.166 1.602a1 1 0 00.793.407h1.808A3 3 0 0120 8.45v5.1a3 3 0 01-2.5 2.96h-1.808a1 1 0 00-.793.407l-1.166 1.602A3 3 0 0111.47 20H8.53a3 3 0 01-2.263-1.441l-1.166-1.602a1 1 0 00-.793-.407H2.5A3 3 0 010 13.55v-5.1a3 3 0 012.5-2.96h1.808a1 1 0 00.793-.407L6.267 3.441zm9.444 4.853a1 1 0 00-1.422-1.414L9 11.586 7.711 10.297a1 1 0 00-1.422 1.414l2 2a1 1 0 001.422 0l6-6z" clip-rule="evenodd"></path></svg>
                <span class="text-[10px] md:text-[11px] font-bold text-white/90 uppercase tracking-wider">Smecone Member</span>
            </div>
            <p class="text-[10px] text-white/50 mt-2">Ketuk foto untuk mengubah</p>
        </div>
    </div>

    {{-- Success Toast --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-700 px-4 py-3.5 rounded-2xl mb-5 text-[13px] font-bold border border-green-100 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ url('/profile/update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <input type="file" id="avatar_upload" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(event)">

        {{-- Section: Profil Publik --}}
        <div class="bg-white rounded-[24px] shadow-[0_2px_15px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            <div class="px-5 pt-5 pb-3 border-b border-gray-50">
                <h2 class="text-[15px] md:text-base font-extrabold text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    Profil Publik
                </h2>
                <p class="text-[11px] md:text-xs text-gray-500 mt-1 ml-10">Info yang terlihat oleh pengguna lain.</p>
            </div>
            
            <div class="p-5 space-y-4">
                <div>
                    <label for="name" class="block text-[12px] md:text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                        class="block w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 px-4 text-[14px] font-medium text-gray-900 focus:ring-2 focus:ring-red-500/30 focus:bg-white focus:border-red-500 placeholder:text-gray-400 transition-all" required>
                    @error('name') <p class="mt-1 text-[11px] text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-[12px] md:text-sm font-bold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                        class="block w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 px-4 text-[14px] font-medium text-gray-900 focus:ring-2 focus:ring-red-500/30 focus:bg-white focus:border-red-500 placeholder:text-gray-400 transition-all" required>
                    @error('email') <p class="mt-1 text-[11px] text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section: Keamanan --}}
        <div class="bg-white rounded-[24px] shadow-[0_2px_15px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            <div class="px-5 pt-5 pb-3 border-b border-gray-50">
                <h2 class="text-[15px] md:text-base font-extrabold text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-50 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    Keamanan Akun
                </h2>
                <p class="text-[11px] md:text-xs text-gray-500 mt-1 ml-10">Biarkan kosong jika tidak ingin mengubah.</p>
            </div>
            
            <div class="p-5 space-y-4">
                <div>
                    <label for="password" class="block text-[12px] md:text-sm font-bold text-gray-700 mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="block w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 px-4 text-[14px] font-medium text-gray-900 focus:ring-2 focus:ring-red-500/30 focus:bg-white focus:border-red-500 placeholder:text-gray-400 transition-all">
                    @error('password') <p class="mt-1 text-[11px] text-red-500 font-medium ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[12px] md:text-sm font-bold text-gray-700 mb-1.5">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                        class="block w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 px-4 text-[14px] font-medium text-gray-900 focus:ring-2 focus:ring-red-500/30 focus:bg-white focus:border-red-500 placeholder:text-gray-400 transition-all">
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3 pt-2">
            <button type="reset" class="flex-1 bg-white border border-gray-200 text-gray-700 font-extrabold py-3.5 rounded-2xl text-[14px] hover:bg-gray-50 transition tap-effect shadow-sm">
                Batalkan
            </button>
            <button type="submit" class="flex-1 bg-red-600 text-white font-extrabold py-3.5 rounded-2xl text-[14px] shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all tap-effect">
                Simpan Perubahan
            </button>
        </div>

    </form>

    {{-- Logout Section --}}
    <div class="mt-8 bg-white rounded-[24px] shadow-[0_2px_15px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
        <div class="p-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <p class="text-[14px] font-extrabold text-gray-900">Keluar Akun</p>
                    <p class="text-[11px] text-gray-500">Logout dari sesi saat ini.</p>
                </div>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-red-50 text-red-600 font-extrabold text-[13px] px-5 py-2.5 rounded-xl border border-red-100 hover:bg-red-100 transition tap-effect">
                    Logout
                </button>
            </form>
        </div>
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