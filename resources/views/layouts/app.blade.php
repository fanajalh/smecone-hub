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
        * { -webkit-tap-highlight-color: transparent; }
        .tap-effect:active { transform: scale(0.92); transition: transform 0.1s ease; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .float-delay-1 { animation-delay: 0s; }
        .float-delay-2 { animation-delay: 0.2s; }
        .float-delay-3 { animation-delay: 0.4s; }
        .float-delay-4 { animation-delay: 0.6s; }
        .float-delay-5 { animation-delay: 0.8s; }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-page-in { animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .nav-link::after {
            content: ''; display: block; width: 0; height: 2px;
            background: #dc2626; transition: width 0.3s ease;
            margin-top: 2px; border-radius: 2px;
        }
        .nav-link:hover::after { width: 100%; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-['Plus_Jakarta_Sans',_sans-serif] antialiased pb-24 md:pb-0 selection:bg-red-200 selection:text-red-900 overflow-x-hidden">

    @auth
    <nav class="hidden lg:flex bg-white/80 backdrop-blur-md border-b border-gray-100 py-3 px-8 justify-between items-center sticky top-0 z-50 shadow-[0_4px_20px_rgba(0,0,0,0.02)] transition-all duration-500">
        <div class="flex items-center gap-10">
            <a href="/dashboard" class="flex items-center gap-2 group tap-effect animate-float">
                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-sm group-hover:rotate-12 transition-all">S</div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">Smecone<span class="text-red-600">Hub</span></span>
            </a>
            
            <div class="flex gap-5 font-bold text-[13px]">
                <a href="/dashboard" class="py-2 {{ request()->is('dashboard') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Beranda</a>
                <a href="/marketplace" class="py-2 {{ request()->is('marketplace*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Marketplace</a>
                <a href="/prestasi" class="py-2 {{ request()->is('prestasi*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Prestasi</a>
                <a href="/event" class="py-2 {{ request()->is('event*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Event</a>
                <a href="/repository" class="py-2 {{ request()->is('repository*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Tugas</a>
                <a href="/forum" class="py-2 {{ request()->is('forum*') ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-900 nav-link' }}">Forum</a>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5 bg-red-50 px-3 py-1.5 rounded-full border border-red-100">
                <span class="text-[13px] font-extrabold text-red-700">{{ auth()->user()->reputation_points ?? 0 }} <span class="opacity-70">Pts</span></span>
            </div>
            <a href="/profile" class="w-9 h-9 rounded-full border-2 border-gray-100 overflow-hidden tap-effect">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=dc2626&color=fff&bold=true" class="w-full h-full object-cover">
            </a>
            <form action="/logout" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </nav>
    @endauth

    <main class="w-full min-h-screen animate-page-in">
        @yield('content')
    </main>

    @auth
    <div class="lg:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-200 flex justify-around items-center px-2 py-2 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] z-50 pb-[env(safe-area-inset-bottom)]">
        
        <a href="/marketplace" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="p-1.5 rounded-xl transition-all {{ request()->is('marketplace*') ? 'text-red-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-[10px] font-bold {{ request()->is('marketplace*') ? 'text-red-600' : 'text-gray-500' }}">Jajan</span>
        </a>

        <a href="/prestasi" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="p-1.5 rounded-xl transition-all {{ request()->is('prestasi*') ? 'text-red-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <span class="text-[10px] font-bold {{ request()->is('prestasi*') ? 'text-red-600' : 'text-gray-500' }}">Prestasi</span>
        </a>

        <a href="/dashboard" class="flex flex-col items-center justify-center w-full group relative -top-5 tap-effect">
            <div class="w-16 h-16 bg-gradient-to-tr from-red-600 to-red-500 rounded-full flex items-center justify-center text-white shadow-[0_8px_25px_rgba(220,38,38,0.4)] border-[6px] border-white transition-transform active:scale-90 animate-float">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </a>

        <a href="/event" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="p-1.5 rounded-xl transition-all {{ request()->is('event*') ? 'text-red-600' : 'text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-[10px] font-bold {{ request()->is('event*') ? 'text-red-600' : 'text-gray-500' }}">Event</span>
        </a>

        <a href="/profile" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="w-7 h-7 rounded-full border-2 {{ request()->is('profile*') ? 'border-red-500 scale-110' : 'border-gray-300 opacity-70' }} overflow-hidden transition-all">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=dc2626&color=fff&bold=true" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] mt-1 font-bold {{ request()->is('profile*') ? 'text-red-600' : 'text-gray-500' }}">Profil</span>
        </a>

    </div>
    @endauth

</body>
</html>