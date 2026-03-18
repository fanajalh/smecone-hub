<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Ngambek</title>
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
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 overflow-hidden relative font-sans selection:bg-pink-200">
    <div class="absolute top-1/4 left-1/3 w-72 h-72 bg-rose-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob"></div>
    <div class="absolute bottom-1/4 right-1/3 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-2000"></div>

    <div class="bg-white/40 backdrop-blur-2xl border border-white/60 p-8 md:p-10 rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(31,38,135,0.07)] max-w-sm w-full text-center relative z-10 animate-float">
        <div class="text-7xl mb-4 drop-shadow-lg">🔥</div>
        <h1 class="text-6xl font-black bg-gradient-to-br from-rose-500 to-pink-600 bg-clip-text text-transparent tracking-tighter mb-2">500</h1>
        <h2 class="text-xl font-extrabold text-gray-800 mb-2">Server Sedang Ngambek!</h2>
        <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">Terjadi korsleting di sistem kami. Tenang, teknisi terbaik Smecone sedang memperbaikinya!</p>
        
        <button onclick="window.history.back()" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-rose-500 to-pink-600 text-white text-sm font-bold py-4 rounded-2xl shadow-[0_8px_20px_rgba(225,29,72,0.25)] hover:shadow-[0_10px_25px_rgba(225,29,72,0.35)] hover:-translate-y-0.5 tap-effect">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Putar Balik
        </button>
    </div>
</body>
</html>