<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Smecone Hub @yield('title')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mencegah highlight biru saat tap di HP */
        * { -webkit-tap-highlight-color: transparent; }
        
        /* Efek klik ala aplikasi native */
        .tap-effect:active { transform: scale(0.92); transition: transform 0.1s ease; }

        /* Animasi Mengambang (Floating) */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-4px); } /* Naik sedikit */
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Delay agar mengambangnya bergelombang (bergantian) */
        .float-delay-1 { animation-delay: 0s; }
        .float-delay-2 { animation-delay: 0.3s; }
        .float-delay-3 { animation-delay: 0.6s; }
        .float-delay-4 { animation-delay: 0.9s; }
        .float-delay-5 { animation-delay: 1.2s; }

        /* Animasi Transisi Halaman (Masuk) */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-page-in {
            animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        /* Hover underline efek untuk Desktop Menu */
        .nav-link::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #dc2626; /* Merah Tailwind */
            transition: width 0.3s ease;
            margin-top: 2px;
            border-radius: 2px;
        }
        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-['Plus_Jakarta_Sans',_sans-serif] antialiased pb-24 md:pb-0 selection:bg-red-200 selection:text-red-900 overflow-x-hidden">

    @auth
    <nav class="hidden lg:flex bg-white/80 backdrop-blur-md border-b border-gray-100 py-3 px-8 justify-between items-center sticky top-0 z-50 shadow-[0_4px_20px_rgba(0,0,0,0.02)] transition-all duration-500 ease-in-out">
        
        <div class="flex items-center gap-10">
            <a href="/dashboard" class="flex items-center gap-2 group tap-effect animate-float float-delay-1">
                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-sm group-hover:rotate-12 group-hover:scale-110 transition-all duration-300">
                    S
                </div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">Smecone<span class="text-red-600">Hub</span></span>
            </a>
            
            <div class="flex gap-4 font-bold text-[13px]">
                <a href="/dashboard" class="py-2 transition-colors animate-float float-delay-1 {{ request()->is('dashboard') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Beranda</a>
                <a href="/lost-found" class="py-2 transition-colors animate-float float-delay-2 {{ request()->is('lost-found*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Barang Hilang</a>
                <a href="/marketplace" class="py-2 transition-colors animate-float float-delay-3 {{ request()->is('marketplace*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Marketplace</a>
                <a href="/repository" class="py-2 transition-colors animate-float float-delay-4 {{ request()->is('repository*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Tugas & Repo</a>
                <a href="/forum" class="py-2 transition-colors animate-float float-delay-5 {{ request()->is('forum*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Forum</a>
            </div>
        </div>
        
        <div class="flex items-center gap-5">
            <div class="flex items-center gap-1.5 bg-red-50 px-3 py-1.5 rounded-full border border-red-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-default animate-float float-delay-3">
                <svg class="w-4 h-4 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span class="text-[13px] font-extrabold text-red-700">{{ auth()->user()->reputation_points ?? 0 }} <span class="text-red-500/80 font-bold">Pts</span></span>
            </div>
            
            <div class="w-px h-6 bg-gray-200"></div>
            
            <a href="/profile" class="flex items-center gap-2.5 group tap-effect animate-float float-delay-4">
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Siswa</p>
                    <p class="text-[13px] font-extrabold text-gray-800 leading-none group-hover:text-red-600 transition-colors duration-300">{{ auth()->user()->name }}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center font-bold shadow-sm border border-gray-200 overflow-hidden group-hover:ring-2 group-hover:ring-red-100 transition-all duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=dc2626&color=fff&bold=true" alt="Avatar" class="w-full h-full object-cover">
                </div>
            </a>

            <form action="/logout" method="POST" class="inline ml-1 animate-float float-delay-5">
                @csrf
                <button type="submit" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-600 tap-effect transition-all duration-300 shadow-sm border border-gray-100" title="Logout">
                    <svg class="w-4 h-4 ml-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </nav>
    @endauth

    <main class="w-full min-h-screen animate-page-in">
        @yield('content')
    </main>

    @auth
    <div class="lg:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-gray-200 flex justify-between items-center px-2 py-2 shadow-[0_-8px_30px_rgba(0,0,0,0.04)] z-50 pb-[env(safe-area-inset-bottom)] transition-transform duration-300">
        
        <a href="/dashboard" class="flex flex-col items-center justify-center w-full py-1 group tap-effect animate-float float-delay-1">
            <div class="px-4 py-1.5 rounded-xl transition-all duration-300 {{ request()->is('dashboard') ? 'bg-red-50 text-red-600 scale-110' : 'text-gray-400 group-hover:text-red-500' }}">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->is('dashboard') ? '2.5' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span class="text-[10px] mt-1 transition-all duration-300 {{ request()->is('dashboard') ? 'font-extrabold text-red-600' : 'font-bold text-gray-500' }}">Beranda</span>
        </a>

        <a href="/marketplace" class="flex flex-col items-center justify-center w-full py-1 group tap-effect animate-float float-delay-2">
            <div class="px-4 py-1.5 rounded-xl transition-all duration-300 {{ request()->is('marketplace*') ? 'bg-red-50 text-red-600 scale-110' : 'text-gray-400 group-hover:text-red-500' }}">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->is('marketplace*') ? '2.5' : '2' }}" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <span class="text-[10px] mt-1 transition-all duration-300 {{ request()->is('marketplace*') ? 'font-extrabold text-red-600' : 'font-bold text-gray-500' }}">Jajan</span>
        </a>

        <a href="/lost-found" class="flex flex-col items-center justify-center w-full group relative -top-4 tap-effect animate-float float-delay-3">
            <div class="w-14 h-14 bg-gradient-to-tr from-red-600 to-red-500 rounded-full flex items-center justify-center text-white shadow-[0_8px_20px_rgba(220,38,38,0.3)] transition-all duration-300 border-[5px] border-white {{ request()->is('lost-found*') ? 'ring-2 ring-red-200' : '' }}">
                <svg class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <span class="text-[10px] mt-1 transition-all duration-300 {{ request()->is('lost-found*') ? 'font-extrabold text-red-600' : 'font-bold text-gray-500' }}">Hilang</span>
        </a>

        <a href="/repository" class="flex flex-col items-center justify-center w-full py-1 group tap-effect animate-float float-delay-4">
            <div class="px-4 py-1.5 rounded-xl transition-all duration-300 {{ request()->is('repository*') ? 'bg-red-50 text-red-600 scale-110' : 'text-gray-400 group-hover:text-red-500' }}">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->is('repository*') ? '2.5' : '2' }}" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
            </div>
            <span class="text-[10px] mt-1 transition-all duration-300 {{ request()->is('repository*') ? 'font-extrabold text-red-600' : 'font-bold text-gray-500' }}">Tugas</span>
        </a>

        <a href="/forum" class="flex flex-col items-center justify-center w-full py-1 group tap-effect animate-float float-delay-5">
            <div class="px-4 py-1.5 rounded-xl transition-all duration-300 {{ request()->is('forum*') ? 'bg-red-50 text-red-600 scale-110' : 'text-gray-400 group-hover:text-red-500' }}">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->is('forum*') ? '2.5' : '2' }}" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
            </div>
            <span class="text-[10px] mt-1 transition-all duration-300 {{ request()->is('forum*') ? 'font-extrabold text-red-600' : 'font-bold text-gray-500' }}">Forum</span>
        </a>

    </div>
    @endauth

</body>
</html>