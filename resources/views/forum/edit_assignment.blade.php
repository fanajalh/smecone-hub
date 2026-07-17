<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smecone Hub | Edit Tugas - {{ $assignment->title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
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
                <h1 class="text-base font-extrabold text-slate-900 leading-tight">Ubah Detail Tugas</h1>
                <p class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-1">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span> Forum: {{ $forumThread->title }}
                </p>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 p-6 md:p-12 flex items-start justify-center">
        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-[0_4px_30px_rgba(0,0,0,0.03)] w-full max-w-xl flex flex-col gap-6">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 leading-tight">Edit Detail Tugas</h2>
                    <p class="text-xs font-semibold text-slate-400 mt-1">Sesuaikan kembali judul, deskripsi, atau batas waktu tugas ini.</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('assignment.update', $assignment->id) }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 pl-1">JUDUL TUGAS</label>
                    <input type="text" name="title" value="{{ $assignment->title }}" required class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3.5 text-sm focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-bold outline-none transition-all placeholder:text-slate-400" placeholder="Judul Tugas">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 pl-1">INSTRUKSI / DESKRIPSI TUGAS</label>
                    <textarea name="description" rows="5" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3.5 text-sm focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-semibold outline-none transition-all resize-none placeholder:text-slate-400" placeholder="Tuliskan instruksi pengerjaan tugas...">{{ $assignment->description }}</textarea>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-400 pl-1">BATAS WAKTU PENGUMPULAN (DEADLINE)</label>
                    <input type="datetime-local" name="deadline" value="{{ $assignment->deadline->format('Y-m-d\TH:i') }}" required class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3.5 text-sm focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-bold outline-none transition-all text-slate-700">
                </div>

                <div class="flex gap-3 border-t border-slate-100 pt-6 mt-2">
                    <a href="/forum/{{ $forumThread->id }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs py-4 rounded-xl transition-all flex items-center justify-center shadow-sm">
                        BATAL
                    </a>
                    <button type="submit" class="flex-1 bg-slate-900 hover:bg-black text-white font-extrabold text-xs py-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
