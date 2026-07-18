<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smecone Hub | Buat Tugas Baru - {{ $forumThread->title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr Custom Theme to match Slate/Red -->
    <style>
        .flatpickr-calendar.arrowTop:before, .flatpickr-calendar.arrowTop:after { border-bottom-color: #fff; }
        .flatpickr-day.selected { background: #E21F26 !important; border-color: #E21F26 !important; }
        .flatpickr-day:hover { background: #f1f5f9; }
        /* Bikin kalender lebih besar */
        .flatpickr-calendar {
            transform: scale(1.2);
            transform-origin: top left;
            margin-top: 5px !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media (max-width: 768px) {
            .flatpickr-calendar {
                transform: scale(1.05); /* Agak lebih kecil di mobile */
            }
        }
    </style>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        /* Hide native calendar icon since we use flatpickr */
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            display: none;
            -webkit-appearance: none;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between shrink-0 shadow-sm z-20">
        <div class="flex items-center gap-4">
            <a href="/forum/{{ $forumThread->id }}" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-100 transition-all duration-300 shadow-sm" title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-base font-extrabold text-slate-900 leading-tight">Buat Tugas Baru</h1>
                <p class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-1">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span> Forum: {{ $forumThread->title }}
                </p>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 p-6 md:p-12 flex items-start justify-center">
        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-[0_4px_40px_rgba(0,0,0,0.04)] w-full max-w-3xl flex flex-col gap-8">
            <div class="flex items-center gap-5 border-b border-slate-100 pb-6">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 leading-tight">Buat Penugasan Baru</h2>
                    <p class="text-xs font-semibold text-slate-400 mt-1">Tugas yang Anda posting akan disematkan di bagian atas forum agar mudah dilihat murid.</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('assignment.store', $forumThread->id) }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 pl-1">JUDUL TUGAS</label>
                    <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3.5 text-sm focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-bold outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: Modul 1 Web Dev - CRUD Laravel">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 pl-1">INSTRUKSI / DESKRIPSI TUGAS</label>
                    <textarea name="description" rows="5" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3.5 text-sm focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-semibold outline-none transition-all resize-none placeholder:text-slate-400" placeholder="Tuliskan instruksi pengerjaan tugas secara rinci di sini..."></textarea>
                </div>

                <div class="flex flex-col gap-1.5 relative">
                    <label class="text-xs font-bold text-slate-400 pl-1">BATAS WAKTU PENGUMPULAN (DEADLINE)</label>
                    <input type="text" id="deadline" name="deadline" required class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-4 text-[15px] focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-bold outline-none transition-all text-slate-800 placeholder:text-slate-400" placeholder="Pilih tanggal dan waktu deadline...">
                    <svg class="w-5 h-5 text-slate-400 absolute right-4 top-[38px] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>

                <div class="flex gap-3 border-t border-slate-100 pt-6 mt-2">
                    <a href="/forum/{{ $forumThread->id }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs py-4 rounded-xl transition-all flex items-center justify-center shadow-sm">
                        BATAL
                    </a>
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-black text-white font-extrabold text-xs py-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        POSTING TUGAS
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#deadline", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
        });
    </script>
</body>
</html>
