<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Smecone Hub @yield('title')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Scripts --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    {{-- Ionicons --}}
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { -webkit-tap-highlight-color: transparent; }
        
        /* Efek klik empuk khas aplikasi mobile */
        .tap-effect:active { transform: scale(0.92); transition: transform 0.1s ease; }

        /* Animasi Melayang untuk tombol tengah Mobile Nav */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }

        /* Animasi masuk halaman yang lebih smooth */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-page-in { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Mencegah Alpine.js flicker */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-['Plus_Jakarta_Sans',_sans-serif] antialiased pb-28 md:pb-0 selection:bg-red-200 selection:text-red-900 overflow-x-hidden relative">

    @auth
    {{-- ================= UNIQUE DESKTOP DYNAMIC ISLAND NAVIGATION ================= --}}
    <nav class="hidden lg:flex fixed top-5 left-1/2 -translate-x-1/2 w-max max-w-[95%] bg-white/80 backdrop-blur-2xl border border-white/80 shadow-[0_8px_30px_rgba(226,31,38,0.1)] rounded-[3rem] p-3 justify-between items-center z-[100] transition-all duration-500 hover:shadow-[0_15px_40px_rgba(226,31,38,0.15)] hover:bg-white/95 ring-1 ring-black/5">
        
        {{-- Brand / Logo (Left) --}}
        <div class="flex items-center pr-8 pl-3 border-r border-gray-200/80">
            <a href="/dashboard" class="flex items-center gap-4 group tap-effect">
                <div class="w-12 h-12 rounded-full overflow-hidden shadow-sm group-hover:scale-105 group-hover:rotate-3 transition-transform duration-300 ring-2 ring-white">
                    <img src="https://graph.facebook.com/smkn1purwokerto/picture?type=large" alt="SMKN 1 Purwokerto Logo" class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col">
                    <span class="text-[18px] font-black text-gray-900 tracking-tight leading-none group-hover:text-[#E21F26] transition-colors">Smecone<span class="text-[#E21F26]">Hub</span></span>
                    <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mt-1">Student Portal</span>
                </div>
            </a>
        </div>
        
        {{-- Navigation Links (Center) --}}
        <div class="flex items-center gap-2 px-8 font-bold text-[15px]">
            <a href="/dashboard" class="relative px-6 py-2.5 rounded-full transition-all duration-300 group {{ request()->is('dashboard') ? 'bg-[#E21F26] text-white shadow-md shadow-red-500/20' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100/80' }}">
                Beranda
            </a>
            <a href="/marketplace" class="relative px-6 py-2.5 rounded-full transition-all duration-300 group {{ request()->is('marketplace*') ? 'bg-[#E21F26] text-white shadow-md shadow-red-500/20' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100/80' }}">
                Marketplace
            </a>
            <a href="/kabar" class="relative px-6 py-2.5 rounded-full transition-all duration-300 group {{ request()->is('kabar*') ? 'bg-[#E21F26] text-white shadow-md shadow-red-500/20' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100/80' }}">
                Kabar
            </a>
            <a href="/repository" class="relative px-6 py-2.5 rounded-full transition-all duration-300 group {{ request()->is('repository*') ? 'bg-[#E21F26] text-white shadow-md shadow-red-500/20' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100/80' }}">
                Tugas
            </a>
            <a href="/forum" class="relative px-6 py-2.5 rounded-full transition-all duration-300 group {{ request()->is('forum*') ? 'bg-[#E21F26] text-white shadow-md shadow-red-500/20' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100/80' }}">
                Forum
            </a>
        </div>
        
        {{-- User Actions (Right) --}}
        <div class="flex items-center gap-4 pl-8 pr-2 border-l border-gray-200/80">

            {{-- Notifikasi --}}
            <a href="/notifikasi" class="relative flex items-center justify-center w-11 h-11 rounded-full bg-gray-50 border border-gray-200/60 hover:bg-red-50 hover:border-red-100 text-gray-500 hover:text-[#E21F26] transition-all tap-effect group">
                <svg class="w-5 h-5 group-hover:animate-[wiggle_1s_ease-in-out_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(auth()->user()->unread_notifications_count > 0)
                <span class="absolute top-0 right-0 flex h-3.5 w-3.5 items-center justify-center">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#E21F26] border-[2px] border-white"></span>
                </span>
                @endif
            </a>

            {{-- User Profil --}}
            <a href="/profile" class="w-11 h-11 rounded-full border-2 border-white shadow-sm overflow-hidden tap-effect ring-1 ring-gray-200 hover:ring-[#E21F26] hover:ring-2 transition-all group">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E21F26&color=fff&bold=true" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                @endif
            </a>
            
            {{-- Logout --}}
            <form action="/logout" method="POST" class="inline m-0 p-0">
                @csrf
                <button type="submit" class="w-11 h-11 flex items-center justify-center rounded-full bg-gray-50 border border-gray-200/60 text-gray-400 hover:bg-red-50 hover:text-[#E21F26] hover:border-red-100 transition-all tap-effect" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </nav>
    @endauth

    {{-- ================= MAIN CONTENT ================= --}}
    <main class="w-full min-h-screen animate-page-in relative z-10">
        @yield('content')
    </main>

        @auth
    {{-- ================= UNIQUE MOBILE BOTTOM DOCK ================= --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-2xl border-t border-gray-200/50 flex justify-around items-end px-2 pb-5 pt-3 shadow-[0_-5px_20px_rgba(0,0,0,0.05)] z-[90] transition-all">
        
        <a href="/marketplace" class="flex flex-col items-center justify-center w-full group tap-effect">
            <div class="relative p-1.5 rounded-2xl transition-all duration-300 {{ request()->is('marketplace*') ? 'text-[#E21F26]' : 'text-gray-400 hover:text-gray-900' }}">
                <svg class="w-6 h-6 relative z-10" fill="{{ request()->is('marketplace*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-[10px] mt-0.5 font-bold {{ request()->is('marketplace*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Jajan</span>
        </a>

        <a href="/kabar" class="flex flex-col items-center justify-center w-full group tap-effect">
            <div class="relative p-1.5 rounded-2xl transition-all duration-300 {{ request()->is('kabar*') ? 'text-[#E21F26]' : 'text-gray-400 hover:text-gray-900' }}">
                <svg class="w-6 h-6 relative z-10" fill="{{ request()->is('kabar*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
            <span class="text-[10px] mt-0.5 font-bold {{ request()->is('kabar*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Kabar</span>
        </a>

        <a href="/dashboard" class="flex flex-col items-center justify-center w-full group tap-effect relative -top-1">
            <div class="relative p-3 rounded-full transition-all duration-300 {{ request()->is('dashboard*') ? 'bg-[#E21F26] text-white shadow-md' : 'bg-gray-100 text-gray-500' }}">
                <svg class="w-6 h-6 relative z-10" fill="{{ request()->is('dashboard*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </a>

        <a href="/notifikasi" class="flex flex-col items-center justify-center w-full group tap-effect relative">
            <div class="relative p-1.5 rounded-2xl transition-all duration-300 {{ request()->is('notifikasi*') ? 'text-[#E21F26]' : 'text-gray-400 hover:text-gray-900' }}">
                <svg class="w-6 h-6 relative z-10" fill="{{ request()->is('notifikasi*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(auth()->user()->unread_notifications_count > 0)
                <span class="absolute top-1 right-1 flex h-3 w-3 items-center justify-center z-20">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#E21F26] border-[2px] border-white"></span>
                </span>
                @endif
            </div>
            <span class="text-[10px] mt-0.5 font-bold {{ request()->is('notifikasi*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Notif</span>
        </a>

        <a href="/profile" class="flex flex-col items-center justify-center w-full group tap-effect">
            <div class="relative p-1.5 rounded-2xl transition-all duration-300 {{ request()->is('profile*') ? 'text-[#E21F26]' : 'text-gray-400 hover:text-gray-900' }}">
                <svg class="w-6 h-6 relative z-10" fill="{{ request()->is('profile*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <span class="text-[10px] mt-0.5 font-bold {{ request()->is('profile*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Profil</span>
        </a>

    </div>
    @endauth

    {{-- ================= GLOBAL SCRIPTS & SWEETALERT ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Konfigurasi Standar untuk Popup (Modern Dribbble Style)
            const swalCustomClass = {
                popup: 'rounded-[2rem] shadow-[0_15px_50px_rgba(0,0,0,0.1)] border border-gray-100 p-6 md:p-8',
                title: 'font-extrabold text-[22px] tracking-tight text-gray-900 mt-2',
                htmlContainer: 'text-[14px] font-medium text-gray-500 mt-2 leading-relaxed',
                confirmButton: 'bg-[#E21F26] text-white font-bold px-8 py-3.5 rounded-[1rem] shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:bg-red-700 active:scale-95 transition-all w-full mt-4',
            };

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{!! session('success') !!}",
                    background: '#ffffff',
                    color: '#111827',
                    iconColor: '#10B981', // Hijau Emerald
                    confirmButtonText: 'Tutup',
                    buttonsStyling: false,
                    customClass: swalCustomClass,
                    showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                    hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: "{!! session('error') !!}",
                    background: '#ffffff',
                    color: '#111827',
                    iconColor: '#E21F26', // Merah Smecone
                    confirmButtonText: 'Mengerti',
                    buttonsStyling: false,
                    customClass: { ...swalCustomClass, confirmButton: 'bg-gray-900 text-white font-bold px-8 py-3.5 rounded-[1rem] active:scale-95 transition-all w-full mt-4' },
                    showClass: { popup: 'animate__animated animate__headShake animate__faster' },
                    hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
                });
            @endif
        });

        // Delete Confirmation
        window.confirmDelete = function(button) {
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data yang dihapus tidak dapat dikembalikan lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true, // Tombol aksi utama di kanan (standar UI modern)
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2rem] shadow-[0_15px_50px_rgba(0,0,0,0.1)] border border-gray-100 p-6 md:p-8',
                    title: 'font-extrabold text-[22px] tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-[14px] font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-[#E21F26] text-white font-bold px-6 py-3.5 rounded-[1rem] shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:bg-red-700 active:scale-95 transition-all mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3.5 rounded-[1rem] hover:bg-gray-200 active:scale-95 transition-all mx-2'
                },
                showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }

        // Generic Form Submit Confirmation
        window.confirmSubmit = function(event, title, text, btnText) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: title || 'Apakah Anda yakin?',
                text: text || "Tindakan ini tidak bisa dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: btnText || 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2rem] shadow-[0_15px_50px_rgba(0,0,0,0.1)] border border-gray-100 p-6 md:p-8',
                    title: 'font-extrabold text-[22px] tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-[14px] font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-[#E21F26] text-white font-bold px-6 py-3.5 rounded-[1rem] shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:bg-red-700 active:scale-95 transition-all mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3.5 rounded-[1rem] hover:bg-gray-200 active:scale-95 transition-all mx-2'
                },
                showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    <!-- LOGOUT DOOR ANIMATION -->
    <style>
        .door-overlay {
            position: fixed; inset: 0; z-index: 9999999;
            display: flex; pointer-events: none;
            perspective: 1000px;
        }
        .door-panel {
            width: 50%; height: 100%;
            background: linear-gradient(135deg, #111 0%, #000 100%);
            transition: transform 0.8s cubic-bezier(0.85, 0, 0.15, 1);
            position: relative;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .door-left { 
            border-right: 1px solid rgba(226, 31, 38, 0.3); 
            box-shadow: inset -10px 0 30px rgba(0,0,0,0.6);
        }
        .door-right { 
            border-left: 1px solid rgba(226, 31, 38, 0.3); 
            box-shadow: inset 10px 0 30px rgba(0,0,0,0.6);
        }
        
        .door-overlay.closed .door-left::after {
            content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 1px;
            background: #E21F26;
            box-shadow: 0 0 20px 2px #E21F26;
            opacity: 0;
            animation: seamGlow 1.5s ease-in-out forwards;
        }
        
        @keyframes seamGlow {
            0% { opacity: 0; height: 0%; top: 50%; }
            50% { opacity: 1; height: 100%; top: 0; }
            100% { opacity: 0.6; height: 100%; top: 0; }
        }

        .door-content-wrapper {
            position: absolute;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            display: flex; flex-direction: column; align-items: center;
            opacity: 0; transition: opacity 0.5s ease;
            z-index: 10;
        }
        .door-overlay.closed .door-content-wrapper {
            opacity: 1;
            transition-delay: 0.6s;
        }
        
        .door-brand-logo {
            width: 90px; height: 90px;
            background: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 30px rgba(226, 31, 38, 0.6), 0 0 0 4px rgba(255, 255, 255, 0.1);
            animation: floatLogo 3s ease-in-out infinite;
            overflow: hidden;
            position: relative;
            z-index: 20;
        }
        .door-brand-logo img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .door-text {
            margin-top: 1.5rem;
            color: white; font-weight: 800; font-size: 1.1rem;
            letter-spacing: 0.2em; text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(0,0,0,1); /* Stronger shadow to hide the line behind */
            display: flex; gap: 6px; align-items: flex-end;
            background: rgba(0,0,0,0.4); /* Slight background to mask the glowing line */
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }
        .dot-anim span {
            animation: bounceDot 1.4s infinite ease-in-out both;
            display: inline-block;
        }
        .dot-anim span:nth-child(1) { animation-delay: -0.32s; }
        .dot-anim span:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes floatLogo {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.05); box-shadow: 0 15px 40px rgba(226, 31, 38, 0.7), inset 0 2px 4px rgba(255,255,255,0.4); }
        }
        @keyframes bounceDot {
            0%, 80%, 100% { transform: scale(0); opacity: 0; }
            40% { transform: scale(1); opacity: 1; }
        }
        
        .door-left::before {
            content: ''; position: absolute; right: -50%; top: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at right center, rgba(226,31,38,0.1) 0%, transparent 60%);
        }
        .door-right::before {
            content: ''; position: absolute; left: -50%; top: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at left center, rgba(226,31,38,0.1) 0%, transparent 60%);
        }
    </style>
    <div class="door-overlay" id="doorOverlay" style="display: none;">
        <div class="door-panel door-left" id="doorLeft" style="transform: translateX(-100%);"></div>
        <div class="door-panel door-right" id="doorRight" style="transform: translateX(100%);"></div>
        <div class="door-content-wrapper">
            <div class="door-brand-logo">
                <img src="https://graph.facebook.com/smkn1purwokerto/picture?type=large" alt="Logo">
            </div>
            <div class="door-text">
                LOGGING OUT 
                <div class="dot-anim flex mb-[3px]">
                    <span class="w-1.5 h-1.5 bg-white rounded-full mx-0.5"></span>
                    <span class="w-1.5 h-1.5 bg-white rounded-full mx-0.5"></span>
                    <span class="w-1.5 h-1.5 bg-white rounded-full mx-0.5"></span>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.playLogoutAnimation = function(callback) {
            const overlay = document.getElementById('doorOverlay');
            const doorLeft = document.getElementById('doorLeft');
            const doorRight = document.getElementById('doorRight');
            
            if (overlay) {
                overlay.style.display = 'flex';
                overlay.classList.remove('closed');
                void overlay.offsetWidth;
                doorLeft.style.transform = 'translateX(0)';
                doorRight.style.transform = 'translateX(0)';
                
                sessionStorage.setItem('playDoorOpen', 'true');
                
                setTimeout(() => {
                    overlay.classList.add('closed');
                }, 750); // Just as doors meet

                setTimeout(() => {
                    if (callback) callback();
                }, 2000); // 2 second showcase
            } else {
                if (callback) callback();
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('form[action$="/logout"], form[action*="logout"]');
            
            // Handle Logout Click
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.action.includes('logout')) {
                        e.preventDefault();
                        if (this.dataset.submitting) return;
                        this.dataset.submitting = 'true';
                        
                        const currentForm = this;
                        
                        window.playLogoutAnimation(() => {
                            // Use fetch to bypass browser form submission blocks
                            fetch(currentForm.action, {
                                method: 'POST',
                                body: new FormData(currentForm),
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }).then(response => {
                                window.location.href = response.url || '/login';
                            }).catch(err => {
                                window.location.reload();
                            });
                        });
                    }
                });
            });

            // Handle Page Load (Open doors if flag is set)
            if (sessionStorage.getItem('playDoorOpen') === 'true') {
                sessionStorage.removeItem('playDoorOpen');
                const overlay = document.getElementById('doorOverlay');
                const doorLeft = document.getElementById('doorLeft');
                const doorRight = document.getElementById('doorRight');
                
                if (overlay) {
                    overlay.style.display = 'flex';
                    overlay.classList.remove('closed');
                    doorLeft.style.transform = 'translateX(0)';
                    doorRight.style.transform = 'translateX(0)';
                    
                    const companyOverlay = document.getElementById('company-welcome');
                    if (companyOverlay) companyOverlay.style.display = 'none';

                    void overlay.offsetWidth;
                    
                    setTimeout(() => {
                        doorLeft.style.transform = 'translateX(-100%)';
                        doorRight.style.transform = 'translateX(100%)';
                        setTimeout(() => { overlay.style.display = 'none'; }, 850);
                    }, 200);
                }
            }
        });

        // Fix for browser back button (bfcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                const overlay = document.getElementById('doorOverlay');
                const doorLeft = document.getElementById('doorLeft');
                const doorRight = document.getElementById('doorRight');
                
                if (overlay) {
                    overlay.style.display = 'none';
                    overlay.classList.remove('closed');
                }
                if (doorLeft) doorLeft.style.transform = 'translateX(-100%)';
                if (doorRight) doorRight.style.transform = 'translateX(100%)';
            }
        });
    </script>
</body>
</html>

