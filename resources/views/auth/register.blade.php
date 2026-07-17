@extends('layouts.app')

@section('title', '| Daftar')

@section('content')
<style>
    /* COMPANY LEVEL WELCOME ANIMATION */
    .company-overlay {
        position: fixed; inset: 0; z-index: 99999;
        background-color: #ffffff;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        transition: transform 0.8s cubic-bezier(0.85, 0, 0.15, 1), opacity 0.8s ease;
    }
    .company-overlay.hide-up {
        transform: translateY(-100%);
        opacity: 0;
        pointer-events: none;
    }

    .brand-logo-anim {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeUpReveal 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    .brand-text-anim {
        opacity: 0;
        clip-path: inset(0 100% 0 0);
        animation: textReveal 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) 0.3s forwards;
    }
    .brand-subtitle-anim {
        opacity: 0;
        transform: translateY(10px);
        animation: fadeUpReveal 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) 0.6s forwards;
    }
    
    .loading-line {
        position: absolute;
        bottom: 0; left: 0; height: 4px;
        background: #dc2626;
        width: 0%;
        animation: loadProgress 2.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes fadeUpReveal {
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes textReveal {
        to { opacity: 1; clip-path: inset(0 0 0 0); }
    }
    @keyframes loadProgress {
        0% { width: 0%; transform: scaleX(1); }
        50% { width: 60%; }
        100% { width: 100%; transform: scaleX(1); }
    }

    /* RIGHT SIDE FORM ANIMATION */
    .form-enter {
        opacity: 0;
        transform: translateX(30px);
        transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .form-enter.show {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* LEFT SIDE ILLUSTRATION ANIMATION */
    .illus-enter {
        opacity: 0;
        transform: scale(0.95);
        transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .illus-enter.show {
        opacity: 1;
        transform: scale(1);
    }

    /* GLASSMORPHISM FOR LEFT DECORATION */
    .glass-card {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
</style>

<!-- WELCOME ANIMATION -->
<div id="company-welcome" class="company-overlay flex flex-col items-center justify-center relative">
    <div class="flex items-center gap-3 mb-2 brand-logo-anim">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg shadow-red-600/30 overflow-hidden">
            <img src="https://graph.facebook.com/smkn1purwokerto/picture?type=large" alt="SMKN 1 Purwokerto Logo" class="w-full h-full object-cover">
        </div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight brand-text-anim">SMECONE</h1>
    </div>
    <p class="text-gray-500 font-medium tracking-widest uppercase text-sm brand-subtitle-anim">Hub Enterprise</p>
    <div class="loading-line"></div>
</div>

<!-- MAIN LAYOUT -->
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row overflow-hidden border border-gray-100">
        
        <!-- LEFT PANEL (Illustration - Hidden on Mobile) -->
        <div class="hidden md:flex w-1/2 bg-red-50 relative p-6 flex-col justify-between illus-enter items-center overflow-hidden group">
            <!-- Decorative Blobs -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 group-hover:scale-110 transition-transform duration-1000"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 group-hover:scale-110 transition-transform duration-1000 delay-100"></div>
            </div>

            <!-- Top Left Logo -->
            <div class="relative z-10 w-full text-left">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-md overflow-hidden">
                        <img src="https://graph.facebook.com/smkn1purwokerto/picture?type=large" alt="SMKN 1 Purwokerto Logo" class="w-full h-full object-cover">
                    </div>
                    <span class="font-bold text-gray-800 tracking-wider text-sm">SMECONE HUB</span>
                </div>
            </div>

            <!-- Illustration Area -->
            <div class="relative z-10 w-full max-w-sm flex-grow flex items-center justify-center my-4">
                <div class="w-full h-48 relative flex items-center justify-center">
                    
                    <!-- Placeholder untuk gambar form -->
                    <img src="https://illustrations.popsy.co/red/surreal-hourglass.svg" alt="Register Illustration" class="w-full h-full object-contain filter drop-shadow-2xl transform transition-transform duration-700 hover:scale-105" onerror="this.style.display='none'; document.getElementById('css-fallback').style.display='flex';">
                    
                    <div id="css-fallback" class="hidden absolute inset-0 flex-col items-center justify-center">
                        <div class="w-40 h-40 bg-white/80 rounded-3xl shadow-xl flex items-center justify-center relative rotate-3 hover:rotate-0 transition-transform duration-500 backdrop-blur-sm border border-white">
                            <div class="absolute inset-4 bg-red-50/50 rounded-2xl border-2 border-dashed border-red-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Left Info Card -->
            <div class="relative z-10 w-full glass-card p-4 rounded-2xl text-left border border-white shadow-lg">
                <h3 class="font-bold text-gray-800 text-sm mb-1">Bergabung Sekarang</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Jadilah bagian dari ekosistem digital Smecone dan nikmati berbagai kemudahan akses layanan terpadu kami.</p>
            </div>
        </div>

        <!-- RIGHT PANEL (Form) -->
        <div class="w-full md:w-1/2 p-6 sm:p-8 flex flex-col justify-center bg-white relative form-enter">
            <!-- Mobile Logo -->
            <div class="md:hidden flex items-center gap-2 mb-6 justify-center">
                <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="https://graph.facebook.com/smkn1purwokerto/picture?type=large" alt="SMKN 1 Purwokerto Logo" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-gray-900 text-lg tracking-tight">SMECONE</span>
            </div>

            <div class="text-left mb-6">
                <h1 class="text-2xl lg:text-3xl font-black text-gray-900 tracking-tight mb-1">Daftar Akun</h1>
                <p class="text-gray-500 font-medium text-sm">Buat akun untuk bergabung dengan Smecone Hub.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-xs border border-red-200 font-semibold flex items-start gap-2 shadow-sm">
                    <svg class="w-4 h-4 flex-shrink-0 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/register" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label for="name" class="text-xs font-semibold text-gray-700 ml-1">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap"
                               class="w-full pl-9 pr-4 py-2.5 bg-[#f8f9fc] border border-gray-200 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="nis" class="text-xs font-semibold text-gray-700 ml-1">NIS</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <input type="number" id="nis" name="nis" value="{{ old('nis') }}" required placeholder="NIS"
                                   class="w-full pl-9 pr-3 py-2.5 bg-[#f8f9fc] border border-gray-200 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label for="email" class="text-xs font-semibold text-gray-700 ml-1">Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Email"
                                   class="w-full pl-9 pr-3 py-2.5 bg-[#f8f9fc] border border-gray-200 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password" class="text-xs font-semibold text-gray-700 ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password"
                               class="w-full pl-9 pr-10 py-2.5 bg-[#f8f9fc] border border-gray-200 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                        
                        <button type="button" onclick="togglePassword('password', 'eye-icon-pw')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-icon-pw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="text-xs font-semibold text-gray-700 ml-1">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password"
                               class="w-full pl-9 pr-10 py-2.5 bg-[#f8f9fc] border border-gray-200 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 focus:bg-white transition-all">
                        
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-conf')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-icon-conf" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-red-500 text-white text-sm font-bold py-3 px-4 rounded-xl hover:bg-red-600 hover:shadow-[0_4px_12px_rgba(239,68,68,0.3)] hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all flex justify-center items-center gap-2">
                        DAFTAR AKUN
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <p class="text-center text-xs text-gray-600 mt-5 font-medium">
                Sudah punya akun?  
                <a href="/login" class="text-red-600 font-bold hover:text-red-800 transition-colors ml-1">Masuk di sini</a>
            </p>
        </div>

    </div>
</div>

<script>
    // Password Toggle Function 
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

    // Company Level Welcome Animation Sequence
    document.addEventListener("DOMContentLoaded", () => {
        const overlay = document.getElementById('company-welcome');
        const formEnter = document.querySelector('.form-enter');
        const illusEnter = document.querySelector('.illus-enter');
        
        setTimeout(() => {
            // Slide up the overlay
            if(overlay) overlay.classList.add('hide-up');
            
            // Trigger entry animations for main content
            setTimeout(() => {
                if(formEnter) formEnter.classList.add('show');
                if(illusEnter) illusEnter.classList.add('show');
            }, 400); // Trigger slightly after overlay starts sliding
            
            // Cleanup overlay from DOM
            setTimeout(() => { if(overlay) overlay.remove(); }, 800);
            
        }, 3000); // 3 seconds total loading time
    });
</script>
@endsection