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
</body>
</html>