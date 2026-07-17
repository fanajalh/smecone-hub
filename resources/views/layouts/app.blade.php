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
<body class="bg-[#F6F8FA] text-gray-800 font-sans font-semibold antialiased pb-24 md:pb-0 selection:bg-red-100 selection:text-[#E21F26] overflow-x-hidden">

    @auth
    {{-- ================= DESKTOP NAVIGATION ================= --}}
    <nav class="hidden lg:flex bg-white/80 backdrop-blur-xl border-b border-gray-100/50 py-3.5 px-8 justify-between items-center sticky top-0 z-50 shadow-[0_4px_30px_rgba(0,0,0,0.02)] transition-all duration-500">
        
        <div class="flex items-center gap-10">
            {{-- Logo Brand --}}
            <a href="/dashboard" class="flex items-center gap-3 group tap-effect">
                <div class="w-10 h-10 rounded-[1rem] flex items-center justify-center bg-white shadow-[0_4px_12px_rgba(226,31,38,0.25)] group-hover:rotate-6 group-hover:scale-105 transition-all duration-300 overflow-hidden">
                    <img src="https://graph.facebook.com/smkn1purwokerto/picture?type=large" alt="SMKN 1 Purwokerto Logo" class="w-full h-full object-cover">
                </div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">Smecone<span class="text-[#E21F26]">Hub</span></span>
            </a>
            
            {{-- Menu Links --}}
            <div class="flex gap-2 font-bold text-[13px]">
                <a href="/dashboard" class="px-4 py-2.5 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Beranda</a>
                <a href="/marketplace" class="px-4 py-2.5 rounded-xl transition-all {{ request()->is('marketplace*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Marketplace</a>
                <a href="/prestasi" class="px-4 py-2.5 rounded-xl transition-all {{ request()->is('prestasi*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Prestasi</a>
                <a href="/event" class="px-4 py-2.5 rounded-xl transition-all {{ request()->is('event*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Event</a>
                <a href="/repository" class="px-4 py-2.5 rounded-xl transition-all {{ request()->is('repository*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Tugas</a>
                <a href="/forum" class="px-4 py-2.5 rounded-xl transition-all {{ request()->is('forum*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">Forum</a>
            </div>
        </div>
        
        {{-- User Actions --}}
        <div class="flex items-center gap-4">
            {{-- Reputasi Points --}}
            <div class="flex items-center gap-1.5 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span class="text-[13px] font-extrabold text-gray-800">{{ auth()->user()->reputation_points ?? 0 }} <span class="opacity-60 text-[11px]">Pts</span></span>
            </div>

            {{-- User Profil --}}
            <a href="/profile" class="w-10 h-10 rounded-[1rem] border-2 border-gray-100 overflow-hidden tap-effect shadow-sm hover:border-[#E21F26] transition-colors">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E21F26&color=fff&bold=true" class="w-full h-full object-cover">
                @endif
            </a>

            {{-- Logout --}}
            <form action="/logout" method="POST" class="inline m-0 p-0">
                @csrf
                <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-[1rem] bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-[#E21F26] transition-colors tap-effect" title="Keluar">
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
    {{-- ================= MOBILE BOTTOM NAVIGATION ================= --}}
    <div class="lg:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-100 flex justify-around items-center px-2 py-2 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] z-[90] pb-safe rounded-t-[1.5rem]">
        
        <a href="/marketplace" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="p-1.5 rounded-[1rem] transition-all duration-300 {{ request()->is('marketplace*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-400 group-hover:text-gray-700' }}">
                <svg class="w-6 h-6" fill="{{ request()->is('marketplace*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-[10px] mt-1 font-bold {{ request()->is('marketplace*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Jajan</span>
        </a>

        <a href="/prestasi" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="p-1.5 rounded-[1rem] transition-all duration-300 {{ request()->is('prestasi*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-400 group-hover:text-gray-700' }}">
                <svg class="w-6 h-6" fill="{{ request()->is('prestasi*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <span class="text-[10px] mt-1 font-bold {{ request()->is('prestasi*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Prestasi</span>
        </a>

        {{-- Floating Center Button --}}
        <a href="/dashboard" class="flex flex-col items-center justify-center w-full group relative -top-6 tap-effect">
            <div class="w-[60px] h-[60px] bg-[#E21F26] rounded-full flex items-center justify-center text-white shadow-[0_8px_20px_rgba(226,31,38,0.4)] border-[6px] border-white transition-transform active:scale-90 animate-float group-hover:bg-[#B9151D]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </a>

        <a href="/event" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="p-1.5 rounded-[1rem] transition-all duration-300 {{ request()->is('event*') ? 'bg-red-50 text-[#E21F26]' : 'text-gray-400 group-hover:text-gray-700' }}">
                <svg class="w-6 h-6" fill="{{ request()->is('event*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="text-[10px] mt-1 font-bold {{ request()->is('event*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Event</span>
        </a>

        <a href="/profile" class="flex flex-col items-center justify-center w-full py-1 group tap-effect">
            <div class="w-8 h-8 rounded-[0.8rem] border-2 {{ request()->is('profile*') ? 'border-[#E21F26] shadow-[0_0_10px_rgba(226,31,38,0.3)]' : 'border-gray-200' }} overflow-hidden transition-all duration-300">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="Profile">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E21F26&color=fff&bold=true" class="w-full h-full object-cover" alt="Profile">
                @endif
            </div>
            <span class="text-[10px] mt-1 font-bold {{ request()->is('profile*') ? 'text-[#E21F26]' : 'text-gray-500' }}">Profil</span>
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
</body>
</html>