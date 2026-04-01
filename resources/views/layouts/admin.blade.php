<!DOCTYPE html>
<html lang="id">
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
</head>
<body class="bg-gray-50 text-gray-800 font-['Plus_Jakarta_Sans',_sans-serif] antialiased">

    <nav class="bg-white sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                
                <a href="/admin" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 md:w-11 md:h-11 bg-red-600 rounded-xl flex items-center justify-center font-black text-xl md:text-2xl text-white shadow-md shadow-red-200 group-hover:bg-red-700 transition">
                        S
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-lg md:text-xl text-gray-900 tracking-tight leading-tight">Hub<span class="text-red-600">.</span>Admin</span>
                        <span class="text-[10px] md:text-xs font-medium text-gray-500 -mt-0.5">Sistem Informasi Smecone</span>
                    </div>
                </a>
                
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="hidden md:flex items-center gap-2.5 bg-green-50 px-4 py-2 rounded-full border border-green-100">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-green-700">Kesiswaan Online</span>
                    </div>

                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm font-bold text-gray-700 hover:text-red-600 transition bg-gray-100 hover:bg-red-50 px-4 py-2.5 md:px-5 md:py-3 rounded-xl border border-gray-200 hover:border-red-100 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden md:inline">Keluar Aplikasi</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="w-full min-h-[calc(100vh-64px)] md:min-h-[calc(100vh-80px)] relative z-10">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses! ✅',
                    text: "{!! session('success') !!}",
                    background: '#ffffff',
                    color: '#111827',
                    iconColor: '#dc2626',
                    confirmButtonText: 'Oke Lanjut',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[28px] shadow-[0_10px_40px_rgba(220,38,38,0.15)] border border-red-50 p-6',
                        title: 'font-black text-2xl tracking-tight text-gray-900 mt-2',
                        htmlContainer: 'text-sm font-medium text-gray-500 mt-2',
                        confirmButton: 'bg-red-600 text-white font-bold px-8 py-3 rounded-xl w-full mt-6 shadow-md shadow-red-200 active:scale-95 transition-transform'
                    },
                    showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                    hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{!! session('error') !!}",
                    background: '#ffffff',
                    color: '#111827',
                    iconColor: '#dc2626',
                    confirmButtonText: 'Mengerti',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[28px] shadow-2xl border border-gray-100 p-6',
                        title: 'font-black text-2xl tracking-tight text-red-600 mt-2',
                        htmlContainer: 'text-sm font-medium text-gray-500 mt-2',
                        confirmButton: 'bg-gray-900 text-white font-bold px-8 py-3 rounded-xl w-full mt-6 active:scale-95 transition-transform'
                    },
                    showClass: { popup: 'animate__animated animate__rubberBand animate__faster' },
                    hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
                });
            @endif
        });

        window.confirmDelete = function(button) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak bisa mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[28px] shadow-2xl border border-gray-100 p-6',
                    title: 'font-black text-2xl tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-sm font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-red-200 active:scale-95 transition-transform mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-200 active:scale-95 transition-transform mx-2'
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