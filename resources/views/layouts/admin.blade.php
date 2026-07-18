<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smecone Hub @yield('title')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Flatpickr for Date Inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Efek klik empuk untuk tombol */
        .tap-effect:active { transform: scale(0.96); transition: transform 0.1s ease; }
    </style>
</head>
<body class="bg-[#F8F9FA] text-gray-800 font-sans font-semibold antialiased selection:bg-red-100 selection:text-red-900">

    {{-- NAVBAR: Modern Glassmorphism Style --}}
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100/50 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-5 md:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                
                {{-- BRANDING LOGO --}}
                <a href="/admin" class="flex items-center gap-3.5 group tap-effect">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-[#E21F26] to-[#B9151D] rounded-[1rem] flex items-center justify-center font-black text-xl md:text-2xl text-white shadow-[0_8px_16px_rgba(226,31,38,0.25)] group-hover:shadow-[0_8px_20px_rgba(226,31,38,0.4)] group-hover:-translate-y-0.5 transition-all duration-300 shrink-0">
                        S
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-extrabold text-[17px] md:text-[20px] text-gray-900 tracking-tight leading-none mb-1">Hub Admin</span>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-none">Sistem Smecone</span>
                    </div>
                </a>
                
                {{-- RIGHT ACTIONS --}}
                <div class="flex items-center gap-3 md:gap-5">
                    
                    {{-- Status Indicator (Modern Ping) --}}
                    <div class="hidden md:flex items-center gap-2.5 bg-white px-4 py-2 rounded-[1rem] border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <div class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </div>
                        <span class="text-[12px] font-bold text-gray-700 tracking-wide">Kesiswaan Online</span>
                    </div>

                    {{-- Logout Button --}}
                    <form action="/logout" method="POST" class="inline m-0 p-0">
                        @csrf
                        <button type="submit" title="Keluar Aplikasi" class="flex items-center justify-center gap-2 w-10 h-10 md:w-auto md:px-5 md:h-11 bg-white md:bg-gray-50 text-gray-500 md:text-gray-700 hover:text-[#E21F26] md:hover:bg-red-50 hover:border-red-100 rounded-[1rem] border border-gray-200 transition-all duration-300 active:scale-95 shadow-sm group">
                            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden md:inline text-[13px] font-bold">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT AREA --}}
    <main class="w-full min-h-[calc(100vh-64px)] md:min-h-[calc(100vh-80px)] relative z-10">
        @yield('content')
    </main>

    {{-- SWEETALERT2 CONFIGURATION (Dribbble Modern Style) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{!! session('success') !!}",
                    background: '#ffffff',
                    color: '#111827',
                    iconColor: '#10B981', // Emerald green untuk success
                    confirmButtonText: 'Lanjutkan',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[2rem] shadow-[0_15px_50px_rgba(0,0,0,0.08)] border border-gray-100 p-6 md:p-8',
                        title: 'font-extrabold text-2xl tracking-tight text-gray-900 mt-2',
                        htmlContainer: 'text-[14px] font-medium text-gray-500 mt-2 leading-relaxed',
                        confirmButton: 'bg-gray-900 hover:bg-black text-white font-bold px-8 py-3.5 rounded-2xl w-full mt-6 shadow-[0_4px_15px_rgba(0,0,0,0.1)] active:scale-95 transition-all'
                    },
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
                    customClass: {
                        popup: 'rounded-[2rem] shadow-[0_15px_50px_rgba(226,31,38,0.08)] border border-red-50 p-6 md:p-8',
                        title: 'font-extrabold text-2xl tracking-tight text-[#E21F26] mt-2',
                        htmlContainer: 'text-[14px] font-medium text-gray-500 mt-2 leading-relaxed',
                        confirmButton: 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold px-8 py-3.5 rounded-2xl w-full mt-6 active:scale-95 transition-all'
                    },
                    showClass: { popup: 'animate__animated animate__headShake animate__faster' },
                    hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
                });
            @endif
        });

        window.confirmDelete = function(button) {
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data yang dihapus tidak dapat dikembalikan lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true, // Tombol aksi utama di kanan (standar modern UI)
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2rem] shadow-[0_15px_50px_rgba(0,0,0,0.08)] border border-gray-100 p-6 md:p-8',
                    title: 'font-extrabold text-2xl tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-[14px] font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-[#E21F26] text-white font-bold px-6 py-3.5 rounded-2xl shadow-[0_8px_20px_rgba(226,31,38,0.25)] hover:bg-red-700 active:scale-95 transition-all mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3.5 rounded-2xl hover:bg-gray-200 active:scale-95 transition-all mx-2'
                },
                showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
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

        // Initialize Flatpickr on all date inputs
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('input[type="date"]', {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                disableMobile: "true"
            });
        });
    </script>
</body>
</html>