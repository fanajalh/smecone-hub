@extends('layouts.app')

@section('title', '| Lupa Password')

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
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2.2); opacity: 0; }
    }
    .pulse-ring::before {
        content: '';
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 3px solid rgba(220, 38, 38, 0.4);
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes float-gentle {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }
    .animate-float-gentle { animation: float-gentle 4s ease-in-out infinite; }
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
                    <img src="https://illustrations.popsy.co/red/password-lock.svg" alt="Forgot Password" class="w-full h-full object-contain filter drop-shadow-2xl transform transition-transform duration-700 hover:scale-105" onerror="this.style.display='none'; document.getElementById('css-fallback-fp').style.display='flex';">
                    
                    <div id="css-fallback-fp" class="hidden absolute inset-0 flex-col items-center justify-center">
                        <div class="relative pulse-ring">
                            <div class="w-32 h-32 bg-white/80 rounded-full shadow-xl flex items-center justify-center backdrop-blur-sm border border-white">
                                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 w-full mt-8 glass-card p-6 rounded-2xl text-left border border-white shadow-lg">
                <h3 class="font-bold text-gray-800 text-lg mb-2">Lupa Password?</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Jangan khawatir! Masukkan email yang terdaftar dan kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
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

            <div class="text-left mb-10">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight mb-2">Reset Password</h1>
                <p class="text-gray-500 font-medium">Masukkan email Anda yang terdaftar untuk menerima link reset password.</p>
            </div>

            @if (session('status'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-4 text-sm border border-emerald-200 font-semibold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if (session('reset_link'))
                <div class="bg-blue-50 border border-blue-200 p-5 rounded-2xl mb-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-bold text-blue-700">Cek Email Anda</span>
                    </div>
                    <p class="text-sm text-blue-600 leading-relaxed">Kami telah mengirim link reset password ke email Anda. Buka inbox atau cek folder <strong>Spam</strong> jika tidak menemukannya.</p>
                    <p class="text-xs text-blue-400 mt-3 text-center font-medium">⏰ Link berlaku selama 60 menit</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm border border-red-200 font-semibold flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label for="email" class="text-sm font-semibold text-gray-700 ml-1">Email address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email terdaftar"
                               class="w-full pl-11 pr-5 py-4 bg-[#f8f9fc] border border-gray-200 text-gray-900 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full bg-red-500 text-white font-bold py-4 px-4 rounded-2xl hover:bg-red-600 hover:shadow-[0_8px_20px_rgba(239,68,68,0.3)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    KIRIM LINK RESET
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
