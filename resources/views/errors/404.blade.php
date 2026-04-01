<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Nyasar Ya?</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .tap-effect { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .tap-effect:active { transform: scale(0.92); }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 overflow-hidden relative font-sans font-semibold selection:bg-blue-200">
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob"></div>
    <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-2000"></div>

    <div class="bg-white/40 backdrop-blur-2xl border border-white/60 p-8 md:p-10 rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] max-w-sm w-full text-center relative z-10 animate-float">
        <div class="text-7xl mb-4 drop-shadow-lg">🛸</div>
        <h1 class="text-6xl font-black bg-gradient-to-br from-blue-600 to-purple-600 bg-clip-text text-transparent tracking-tighter mb-2">404</h1>
        <h2 class="text-xl font-extrabold text-gray-800 mb-2">Halaman Menghilang!</h2>
        <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">Mungkin halamannya diculik alien, atau kamu memang salah ketik alamat.</p>
        
        <a href="/" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold py-4 rounded-2xl shadow-[0_8px_20px_rgba(79,70,229,0.25)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.35)] hover:-translate-y-0.5 tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>