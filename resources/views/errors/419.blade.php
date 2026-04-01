<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Habis</title>
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
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 overflow-hidden relative font-sans font-semibold selection:bg-yellow-200">
    <div class="absolute top-1/4 right-1/4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob"></div>
    <div class="absolute bottom-1/4 left-1/4 w-72 h-72 bg-amber-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-2000"></div>

    <div class="bg-white/40 backdrop-blur-2xl border border-white/60 p-8 md:p-10 rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] max-w-sm w-full text-center relative z-10 animate-float">
        <div class="text-7xl mb-4 drop-shadow-lg">⏳</div>
        <h1 class="text-6xl font-black bg-gradient-to-br from-yellow-500 to-amber-600 bg-clip-text text-transparent tracking-tighter mb-2">419</h1>
        <h2 class="text-xl font-extrabold text-gray-800 mb-2">Sesi Kadaluarsa!</h2>
        <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">Karena kamu terlalu lama diam (AFK), sistem otomatis mengakhiri sesimu demi keamanan.</p>
        
        <div class="flex flex-col gap-3">
            <button onclick="window.location.reload()" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-yellow-500 to-amber-500 text-white text-sm font-bold py-3.5 rounded-2xl shadow-[0_8px_20px_rgba(245,158,11,0.25)] hover:shadow-[0_10px_25px_rgba(245,158,11,0.35)] hover:-translate-y-0.5 tap-effect">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Muat Ulang
            </button>
            <a href="/login" class="flex items-center justify-center gap-2 w-full bg-white/50 border border-gray-200 text-gray-600 text-sm font-bold py-3.5 rounded-2xl hover:bg-white tap-effect">
                Login Kembali
            </a>
        </div>
    </div>
</body>
</html>