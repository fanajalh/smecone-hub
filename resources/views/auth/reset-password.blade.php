@extends('layouts.app')

@section('title', '| Reset Password')

@section('content')
<style>
    .form-enter {
        opacity: 0;
        transform: translateX(30px);
        transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .form-enter.show {
        opacity: 1;
        transform: translateX(0);
    }
    .illus-enter {
        opacity: 0;
        transform: scale(0.95);
        transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .illus-enter.show {
        opacity: 1;
        transform: scale(1);
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
    @keyframes checkmark-draw {
        0% { stroke-dashoffset: 50; }
        100% { stroke-dashoffset: 0; }
    }
    @keyframes float-gentle {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }
    .animate-float-gentle { animation: float-gentle 4s ease-in-out infinite; }
    
    /* Password strength meter */
    .strength-bar { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
</style>

<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="max-w-6xl w-full bg-white rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row overflow-hidden min-h-[650px] border border-gray-100">
        
        {{-- LEFT PANEL --}}
        <div class="hidden md:flex w-1/2 bg-red-50 relative p-12 flex-col justify-between illus-enter items-center overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 group-hover:scale-110 transition-transform duration-1000"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 group-hover:scale-110 transition-transform duration-1000 delay-100"></div>
            </div>

            <div class="relative z-10 w-full text-left">
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">S</div>
                    <span class="font-bold text-gray-800 tracking-wider text-sm">SMECONE HUB</span>
                </div>
            </div>

            <div class="relative z-10 w-full max-w-sm flex-grow flex items-center justify-center">
                <div class="w-full h-72 relative flex items-center justify-center animate-float-gentle">
                    <img src="https://illustrations.popsy.co/red/shield.svg" alt="Reset Password" class="w-full h-full object-contain filter drop-shadow-2xl transform transition-transform duration-700 hover:scale-105" onerror="this.style.display='none'; document.getElementById('css-fallback-rp').style.display='flex';">
                    
                    <div id="css-fallback-rp" class="hidden absolute inset-0 flex-col items-center justify-center">
                        <div class="w-32 h-32 bg-white/80 rounded-3xl shadow-xl flex items-center justify-center backdrop-blur-sm border border-white rotate-3 hover:rotate-0 transition-transform duration-500">
                            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 w-full mt-8 glass-card p-6 rounded-2xl text-left border border-white shadow-lg">
                <h3 class="font-bold text-gray-800 text-lg mb-2">Password Baru</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Buat password baru yang kuat dan mudah Anda ingat. Gunakan kombinasi huruf, angka, dan simbol.</p>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white relative form-enter">
            {{-- Mobile Logo --}}
            <div class="md:hidden flex items-center gap-2 mb-8 justify-center">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg">S</div>
                <span class="font-bold text-gray-900 text-xl tracking-tight">SMECONE</span>
            </div>

            {{-- Back Button --}}
            <a href="/login" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-red-500 transition-colors mb-8 group w-fit">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>

            <div class="text-left mb-8">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight mb-2">Password Baru</h1>
                <p class="text-gray-500 font-medium">Buat password baru untuk akun <span class="text-red-500 font-bold">{{ $email }}</span></p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm border border-red-200 font-semibold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="space-y-1.5">
                    <label for="password" class="text-sm font-semibold text-gray-700 ml-1">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" minlength="8"
                               class="w-full pl-11 pr-12 py-4 bg-[#f8f9fc] border border-gray-200 text-gray-900 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all"
                               oninput="checkPasswordStrength(this.value)">
                        <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-icon-1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    {{-- Password Strength Meter --}}
                    <div class="flex gap-1.5 mt-2 px-1">
                        <div id="str-1" class="h-1.5 flex-1 rounded-full bg-gray-200 strength-bar"></div>
                        <div id="str-2" class="h-1.5 flex-1 rounded-full bg-gray-200 strength-bar"></div>
                        <div id="str-3" class="h-1.5 flex-1 rounded-full bg-gray-200 strength-bar"></div>
                        <div id="str-4" class="h-1.5 flex-1 rounded-full bg-gray-200 strength-bar"></div>
                    </div>
                    <p id="str-text" class="text-xs font-semibold text-gray-400 ml-1 mt-1"></p>
                </div>

                <div class="space-y-1.5 pt-1">
                    <label for="password_confirmation" class="text-sm font-semibold text-gray-700 ml-1">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang password baru" minlength="8"
                               class="w-full pl-11 pr-12 py-4 bg-[#f8f9fc] border border-gray-200 text-gray-900 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-icon-2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-red-500 text-white font-bold py-4 px-4 rounded-2xl hover:bg-red-600 hover:shadow-[0_8px_20px_rgba(239,68,68,0.3)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    RESET PASSWORD
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-10 font-medium">
                Sudah ingat password?  
                <a href="/login" class="text-red-600 font-bold hover:text-red-800 transition-colors ml-1">Masuk Sekarang</a>
            </p>
        </div>

    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    }

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;

        const colors = ['', '#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
        const labels = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];

        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById('str-' + i);
            bar.style.backgroundColor = i <= strength ? colors[strength] : '#e5e7eb';
        }

        const text = document.getElementById('str-text');
        text.textContent = password.length > 0 ? labels[strength] || 'Terlalu Pendek' : '';
        text.style.color = colors[strength] || '#9ca3af';
    }

    document.addEventListener("DOMContentLoaded", () => {
        const formEnter = document.querySelector('.form-enter');
        const illusEnter = document.querySelector('.illus-enter');
        setTimeout(() => {
            if (formEnter) formEnter.classList.add('show');
            if (illusEnter) illusEnter.classList.add('show');
        }, 100);
    });
</script>
@endsection
