<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smecone Hub | Pengumpulan Tugas - {{ $assignment->title }}</title>
    
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
            overflow: hidden;
        }
        
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 9999px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between shrink-0 shadow-sm z-20">
        <div class="flex items-center gap-4">
            <a href="/forum/{{ $forumThread->id }}" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-100 transition-all duration-300 shadow-sm" title="Kembali ke Kelas">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-base font-extrabold text-slate-900 leading-tight">{{ $assignment->title }}</h1>
                <p class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-1">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span> {{ $forumThread->title }}
                </p>
            </div>
        </div>
        
        <!-- Stats & Actions -->
        <div class="flex items-center gap-6">
            <div class="hidden sm:flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs">
                <div class="text-center border-r border-slate-200 pr-4">
                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Diserahkan</span>
                    <span class="text-sm font-black text-slate-900 mt-0.5 block">{{ $submissions->count() }}</span>
                </div>
                <div class="text-center pl-1">
                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Total Siswa</span>
                    <span class="text-sm font-black text-slate-900 mt-0.5 block">{{ $students->count() }}</span>
                </div>
            </div>
            
            <a href="/assignment/{{ $assignment->id }}/export" class="bg-[#E21F26] hover:bg-red-700 text-white font-extrabold text-xs px-4.5 py-3 rounded-xl shadow-md shadow-red-600/10 hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                EXPORT CSV
            </a>
        </div>
    </header>

    <!-- Main 3-Column Workspace -->
    <div class="flex-1 flex overflow-hidden relative">
        
        <!-- Column 1: Student List (Sidebar) -->
        <aside class="w-80 border-r border-slate-200/80 bg-white flex flex-col shrink-0 overflow-hidden z-10">
            <!-- Search student name -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="studentSearch" onkeyup="filterStudents()" placeholder="Cari nama siswa..." class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-xs outline-none focus:border-red-500 focus:ring-4 focus:ring-red-500/10 font-bold transition-all placeholder:text-slate-400">
                </div>
            </div>

            <!-- Student List Scrollable -->
            <div class="flex-1 overflow-y-auto custom-scroll divide-y divide-slate-100/60" id="studentList">
                @foreach($students as $student)
                    @php
                        $sub = $submissions->where('user_id', $student->id)->first();
                        $statusClass = 'bg-slate-100 text-slate-400 border-slate-200/60';
                        $statusText = 'Belum Kumpul';
                        if ($sub) {
                            if ($sub->grade !== null) {
                                $statusClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                $statusText = 'Dinilai: ' . $sub->grade;
                            } else {
                                $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                $statusText = 'Perlu Nilai';
                            }
                        }
                    @endphp
                    <button onclick="selectStudent({{ $student->id }})" id="student-tab-{{ $student->id }}" class="w-full text-left p-4 hover:bg-slate-50/80 flex items-center gap-3.5 transition-all duration-200 outline-none student-tab-btn border-l-4 border-l-transparent" data-name="{{ strtolower($student->name) }}">
                        <!-- Avatar circle -->
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-600 text-sm shrink-0 border border-slate-200 uppercase">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-extrabold text-slate-800 text-[13px] truncate leading-snug">{{ $student->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </aside>

        <!-- Dynamic Container (Shows details + grading sidebar once student is selected) -->
        <div id="workspace-detail" class="flex-1 flex overflow-hidden hidden w-full">
            
            <!-- Column 2: Submission Details (Center Area) -->
            <main class="flex-1 bg-slate-50 overflow-y-auto p-8 custom-scroll flex flex-col gap-6">
                <!-- Identity Card -->
                <div class="bg-white rounded-2xl p-6 shadow-[0_2px_12px_rgba(0,0,0,0.02)] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center font-black text-slate-700 text-lg border border-slate-200 uppercase" id="workspace-avatar">
                        -
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 leading-tight" id="workspace-student-name">-</h2>
                        <p class="text-xs font-semibold text-slate-400 mt-1 flex items-center gap-2">
                            <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200/50" id="workspace-student-nis">-</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span id="workspace-submit-time">-</span>
                        </p>
                    </div>
                </div>

                <!-- Attachment Details Card -->
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-[0_2px_12px_rgba(0,0,0,0.02)] flex flex-col gap-6 flex-1 min-h-[350px]">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-[#E21F26] block mb-1">Lampiran Siswa</span>
                        <h3 class="text-base font-extrabold text-slate-900">Hasil Tautan Jawaban</h3>
                    </div>

                    <!-- Submission Found View -->
                    <div id="sub-found-view" class="flex flex-col gap-5 flex-1">
                        
                        <!-- Compact Dynamic Link Card -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center justify-between gap-4 shadow-sm shrink-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm" id="repo-icon-box">
                                    <!-- Dynamic Icon -->
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400" id="repo-provider-label">Link Tautan</span>
                                    <h4 class="font-bold text-xs text-slate-800 truncate mt-0.5" id="workspace-link-url">-</h4>
                                </div>
                            </div>
                            <a href="#" target="_blank" id="workspace-link-btn" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-extrabold text-xs px-3.5 py-2 rounded-lg transition-all flex items-center gap-1.5 shrink-0 shadow-sm">
                                Buka
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- GitHub File Explorer -->
                        <div id="github-explorer" class="bg-white rounded-xl border border-slate-200 flex flex-col flex-grow overflow-hidden shadow-sm">
                            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex items-center justify-between shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-extrabold text-slate-700 truncate" id="explorer-path">root</span>
                                </div>
                                <button id="explorer-back-btn" onclick="navigateBack()" class="hidden px-2.5 py-1 bg-white border border-slate-200 hover:bg-slate-50 text-[10px] font-black text-slate-600 rounded-lg transition-colors shadow-sm">
                                    ← Kembali
                                </button>
                            </div>
                            
                            <!-- Explorer Content -->
                            <div class="flex-grow overflow-y-auto p-2 min-h-[250px] custom-scroll" id="explorer-list">
                                <!-- Loaded dynamically -->
                            </div>
                        </div>

                        <!-- Fallback / Error Message for non-explorer repositories -->
                        <div id="explorer-fallback" class="bg-slate-50 border border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center text-center gap-2 hidden">
                            <div class="w-10 h-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-xs text-slate-800">Repositori Tidak Bisa Dijelajahi</h4>
                            <p class="text-[11px] font-semibold text-slate-400 max-w-xs leading-relaxed">Tautan ini tidak didukung untuk dijelajahi langsung (kemungkinan repositori bersifat privat atau bukan layanan GitHub). Silakan klik tombol <strong>"Buka"</strong> di atas untuk memeriksanya.</p>
                        </div>
                    </div>

                    <!-- Submission Empty View -->
                    <div id="sub-empty-view" class="flex flex-col items-center justify-center gap-3 py-16 flex-1 border-2 border-dashed border-slate-200 rounded-xl hidden bg-slate-50/50">
                        <div class="w-12 h-12 bg-white text-slate-400 rounded-xl border border-slate-200 flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-6 3h6m-3-9h.01M9 21h6a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-slate-800 text-sm">Belum Ada Tautan Pengumpulan</h4>
                        <p class="text-xs font-semibold text-slate-400 text-center max-w-xs px-6 leading-relaxed">Siswa ini belum menyerahkan hasil tugas. Nilai dapat tetap diberikan atau menunggu pengumpulan.</p>
                    </div>
                </div>
            </main>

            <!-- Column 3: Evaluation Panel (Right Sidebar) -->
            <aside class="w-80 border-l border-slate-200 bg-white p-6 md:p-8 flex flex-col shrink-0 overflow-y-auto custom-scroll z-10">
                <div class="mb-6">
                    <span class="text-[9px] font-black uppercase tracking-widest text-[#E21F26] block mb-1">Evaluasi Kelas</span>
                    <h3 class="text-base font-extrabold text-slate-900">Penilaian Tugas</h3>
                </div>

                <!-- Grading status card -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col gap-1.5 shadow-sm mb-6">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Tugas</span>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full" id="grade-status-dot"></span>
                        <span class="text-xs font-extrabold" id="grade-status-label">-</span>
                    </div>
                </div>

                <!-- Score Form -->
                <form id="gradingForm" method="POST" class="flex flex-col gap-5">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider pl-1">Skor Tugas (0-100)</label>
                        <input type="number" name="grade" id="gradeInput" required min="0" max="100" placeholder="Skor" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 text-center font-black text-2xl text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all shadow-sm">
                    </div>

                    <!-- Quick scores buttons -->
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" onclick="setQuickGrade(75)" class="py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-xs font-black text-slate-600 transition-colors shadow-sm">75</button>
                        <button type="button" onclick="setQuickGrade(85)" class="py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-xs font-black text-slate-600 transition-colors shadow-sm">85</button>
                        <button type="button" onclick="setQuickGrade(95)" class="py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-xs font-black text-slate-600 transition-colors shadow-sm">95</button>
                        <button type="button" onclick="setQuickGrade(100)" class="py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-xs font-black text-slate-600 transition-colors shadow-sm">100</button>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-extrabold text-xs py-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        SIMPAN NILAI
                    </button>
                </form>
            </aside>
        </div>

        <!-- Default Screen / Empty State (Cover overlay when no student selected) -->
        <div id="empty-state" class="flex-1 bg-slate-50 flex flex-col items-center justify-center p-8 z-10">
            <div class="w-20 h-20 bg-white rounded-2xl border border-slate-150 flex items-center justify-center text-slate-400 shadow-sm mb-4 animate-pulse">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h3 class="text-sm font-extrabold text-slate-800">Pilih Siswa</h3>
            <p class="text-xs font-semibold text-slate-400 text-center max-w-xs mt-1 leading-relaxed">Silakan pilih salah satu siswa pada menu daftar kelas di samping kiri untuk mengkoreksi dan menilai tugas.</p>
        </div>

    </div>

    <!-- Script logic -->
    <script>
        // Load PHP data into JS variables
        const students = @json($students);
        const submissions = @json($submissions);

        let gitOwner = '';
        let gitRepo = '';
        let currentPath = '';
        let pathHistory = [];

        function selectStudent(studentId) {
            // Reset active style on student buttons
            document.querySelectorAll('.student-tab-btn').forEach(btn => {
                btn.classList.remove('bg-red-50/50', 'border-l-red-600', 'pl-5');
                btn.classList.add('border-l-transparent');
            });
            
            // Set active style on selected student button
            const activeTab = document.getElementById(`student-tab-${studentId}`);
            activeTab.classList.remove('border-l-transparent');
            activeTab.classList.add('bg-red-50/50', 'border-l-red-600', 'pl-5');

            // Find current data
            const student = students.find(s => s.id == studentId);
            const sub = submissions.find(s => s.user_id == studentId);

            // Toggle views
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('workspace-detail').classList.remove('hidden');

            // Set identity fields
            document.getElementById('workspace-avatar').innerText = student.name.charAt(0);
            document.getElementById('workspace-student-name').innerText = student.name;
            document.getElementById('workspace-student-nis').innerText = student.nis || 'NIS -';
            
            // Form action & input grade setting
            const form = document.getElementById('gradingForm');
            const gradeInput = document.getElementById('gradeInput');

            // Reset explorer state
            currentPath = '';
            pathHistory = [];
            document.getElementById('github-explorer').classList.add('hidden');
            document.getElementById('explorer-fallback').classList.add('hidden');

            if (sub) {
                // Submit date
                const submitDate = new Date(sub.created_at).toLocaleString('id-ID', {
                    day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                document.getElementById('workspace-submit-time').innerText = `Dikumpul: ${submitDate}`;
                
                // Show found attachment card & fill data
                document.getElementById('sub-found-view').classList.remove('hidden');
                document.getElementById('sub-empty-view').classList.add('hidden');
                document.getElementById('workspace-link-url').innerText = sub.repo_link;
                document.getElementById('workspace-link-btn').href = sub.repo_link;

                // Brand Icon setup
                const iconBox = document.getElementById('repo-icon-box');
                const providerLabel = document.getElementById('repo-provider-label');
                
                if (sub.repo_link.includes('github.com')) {
                    iconBox.innerHTML = `<svg class="w-6 h-6 text-slate-800" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.9-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z" clip-rule="evenodd"></path></svg>`;
                    providerLabel.innerText = 'GitHub Repository';

                    // Parse GitHub Owner & Repo to load files
                    const match = sub.repo_link.match(/github\.com\/([^\/]+)\/([^\/]+)/);
                    if (match) {
                        gitOwner = match[1];
                        gitRepo = match[2].replace(/\.git$/, '');
                        document.getElementById('github-explorer').classList.remove('hidden');
                        loadGithubContents('');
                    } else {
                        document.getElementById('explorer-fallback').classList.remove('hidden');
                    }
                } else if (sub.repo_link.includes('drive.google.com') || sub.repo_link.includes('docs.google.com')) {
                    iconBox.innerHTML = `<svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M15.43 15.35l-3.43-6 3.43-6h6.86l-3.43 6zm-7.79 3.1l-3.43-6 6.86-11.83h3.43zm2.5 1h13.72l-3.43-6H6.86z"/></svg>`;
                    providerLabel.innerText = 'Google Drive Link';
                    document.getElementById('explorer-fallback').classList.remove('hidden');
                } else {
                    iconBox.innerHTML = `<svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>`;
                    providerLabel.innerText = 'Tautan Web / Karya';
                    document.getElementById('explorer-fallback').classList.remove('hidden');
                }

                // Fill form attributes & grade
                form.action = `/submission/${sub.id}/grade`;
                gradeInput.value = sub.grade !== null ? sub.grade : '';

                // Set status indicators
                const statusDot = document.getElementById('grade-status-dot');
                const statusLabel = document.getElementById('grade-status-label');
                if (sub.grade !== null) {
                    statusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500';
                    statusLabel.innerText = `Sudah Dinilai (${sub.grade}/100)`;
                    statusLabel.className = 'text-xs font-black text-emerald-600';
                } else {
                    statusDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping';
                    statusLabel.innerText = 'Perlu Dinilai';
                    statusLabel.className = 'text-xs font-black text-amber-600';
                }
            } else {
                // Student has not submitted
                document.getElementById('workspace-submit-time').innerText = 'Belum Mengumpulkan';
                document.getElementById('sub-found-view').classList.add('hidden');
                document.getElementById('sub-empty-view').classList.remove('hidden');

                form.action = '#';
                gradeInput.value = '';

                const statusDot = document.getElementById('grade-status-dot');
                const statusLabel = document.getElementById('grade-status-label');
                statusDot.className = 'w-2.5 h-2.5 rounded-full bg-slate-350';
                statusLabel.innerText = 'Belum Submit';
                statusLabel.className = 'text-xs font-black text-slate-500';
            }
        }

        async function loadGithubContents(path = '') {
            const listContainer = document.getElementById('explorer-list');
            const backBtn = document.getElementById('explorer-back-btn');
            const pathLabel = document.getElementById('explorer-path');
            
            listContainer.innerHTML = `
                <div class="flex items-center justify-center py-12 text-slate-400 gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-bold">Memuat berkas...</span>
                </div>
            `;

            backBtn.classList.toggle('hidden', path === '');
            pathLabel.innerText = path ? `root / ${path}` : 'root';

            try {
                const response = await fetch(`https://api.github.com/repos/${gitOwner}/${gitRepo}/contents/${path}`);
                if (!response.ok) throw new Error('Failed to load');
                
                const data = await response.json();
                
                // Sort: directories first, then files
                data.sort((a, b) => {
                    if (a.type === 'dir' && b.type !== 'dir') return -1;
                    if (a.type !== 'dir' && b.type === 'dir') return 1;
                    return a.name.localeCompare(b.name);
                });

                listContainer.innerHTML = '';
                
                if (data.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-12 text-slate-400 text-xs font-bold">
                            Folder ini kosong.
                        </div>
                    `;
                    return;
                }

                data.forEach(item => {
                    const isDir = item.type === 'dir';
                    const icon = isDir 
                        ? `<svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>`
                        : `<svg class="w-4 h-4 text-slate-450 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>`;
                    
                    const clickAction = isDir 
                        ? `onClickFolder('${item.path}')` 
                        : `onClickFile('${item.name}', '${item.download_url}')`;

                    listContainer.innerHTML += `
                        <div onclick="${clickAction}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors text-xs font-bold text-slate-700 select-none">
                            ${icon}
                            <span class="truncate">${item.name}</span>
                        </div>
                    `;
                });

            } catch (err) {
                document.getElementById('github-explorer').classList.add('hidden');
                document.getElementById('explorer-fallback').classList.remove('hidden');
            }
        }

        function onClickFolder(path) {
            pathHistory.push(currentPath);
            currentPath = path;
            loadGithubContents(path);
        }

        function navigateBack() {
            if (pathHistory.length > 0) {
                currentPath = pathHistory.pop();
                loadGithubContents(currentPath);
            } else if (currentPath !== '') {
                currentPath = '';
                loadGithubContents('');
            }
        }

        async function onClickFile(name, downloadUrl) {
            const listContainer = document.getElementById('explorer-list');
            const backBtn = document.getElementById('explorer-back-btn');
            const pathLabel = document.getElementById('explorer-path');

            // Save path to history to allow returning to list
            pathHistory.push(currentPath);

            listContainer.innerHTML = `
                <div class="flex items-center justify-center py-12 text-slate-400 gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-bold">Membuka berkas...</span>
                </div>
            `;

            backBtn.classList.remove('hidden');
            pathLabel.innerText = `root / ${currentPath ? currentPath + ' / ' : ''}${name}`;

            try {
                // Check if file is displayable code
                const ext = name.split('.').pop().toLowerCase();
                const textExtensions = ['php', 'js', 'css', 'html', 'json', 'md', 'py', 'txt', 'java', 'cpp', 'c', 'sh', 'yml', 'yaml', 'xml'];
                
                if (!textExtensions.includes(ext)) {
                    listContainer.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-16 text-slate-400 text-center gap-3">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-xs font-bold">Berkas biner tidak didukung pratinjau langsung.</span>
                        </div>
                    `;
                    return;
                }

                const response = await fetch(downloadUrl);
                if (!response.ok) throw new Error('Fail');
                
                const text = await response.text();

                // Escape html tags to prevent browser rendering them
                const escapedText = text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");

                listContainer.innerHTML = `
                    <div class="p-2">
                        <pre class="bg-slate-900 text-slate-100 p-4 rounded-xl overflow-auto text-xs font-mono max-h-[500px] leading-relaxed select-text custom-scroll"><code>${escapedText}</code></pre>
                    </div>
                `;

            } catch (err) {
                listContainer.innerHTML = `
                    <div class="text-center py-12 text-red-500 text-xs font-bold">
                        Gagal memuat isi berkas.
                    </div>
                `;
            }
        }

        function setQuickGrade(val) {
            document.getElementById('gradeInput').value = val;
        }

        function filterStudents() {
            const query = document.getElementById('studentSearch').value.toLowerCase();
            document.querySelectorAll('.student-tab-btn').forEach(btn => {
                const name = btn.getAttribute('data-name');
                if (name.includes(query)) {
                    btn.style.display = 'flex';
                } else {
                    btn.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>