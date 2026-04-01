@extends('layouts.app')
@section('title', '| ' . $repository->name)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

<style>
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s ease-in-out; }
    .tab-active { border-bottom: 2px solid #EE2737; color: #EE2737; font-weight: 800; }
    .tab-inactive { border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; }
    .tab-inactive:hover { color: #374151; border-bottom: 2px solid #d1d5db; }

    /* File Explorer View Modes */
    .file-container { transition: all 0.3s ease; }
    
    /* List Mode */
    .view-list { display: flex; flex-direction: column; }
    .view-list li { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid #f3f4f6; transition: background 0.2s; }
    .view-list li:hover { background-color: #f9fafb; }
    .view-list .file-main { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; }
    .view-list .file-icon-wrap { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .view-list .file-icon-wrap svg { width: 20px; height: 20px; }
    .view-list .file-name { font-size: 13px; text-align: left; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .view-list .file-meta { display: flex; align-items: center; gap: 16px; flex-shrink: 0; }
    
    /* Grid Mode (Google Drive Style) */
    .view-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 16px; padding: 16px; border-bottom: none; }
    .view-grid li { display: flex; flex-direction: column; align-items: center; position: relative; padding: 20px 12px 12px; border: 1px solid #f3f4f6; border-radius: 12px; background: #fff; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .view-grid li:hover { border-color: #EE2737; box-shadow: 0 4px 12px rgba(238,39,55,0.08); transform: translateY(-2px); }
    .view-grid .file-main { display: flex; flex-direction: column; align-items: center; width: 100%; gap: 12px; }
    .view-grid .file-icon-wrap { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: #f9fafb; border-radius: 12px; }
    .view-grid .file-icon-wrap svg { width: 28px; height: 28px; }
    .view-grid .file-name { font-size: 12px; text-align: center; width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; white-space: normal; line-height: 1.4; }
    .view-grid .file-checkbox { position: absolute; top: 10px; left: 10px; z-index: 10; }
    .view-grid .file-meta { position: absolute; top: 8px; right: 8px; opacity: 0; transition: opacity 0.2s; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .view-grid li:hover .file-meta { opacity: 1; }
    .view-grid .file-size { display: none; } /* Hide size in grid */
</style>

<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-16 font-sans">
    
    {{-- ALERT MESSAGES --}}
    @if(session('success')) 
        <div class="mb-6 bg-green-50 text-green-700 px-5 py-4 rounded-2xl text-[13px] border border-green-200 font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div> 
    @endif
    @if(session('error')) 
        <div class="mb-6 bg-red-50 text-red-700 px-5 py-4 rounded-2xl text-[13px] border border-red-200 font-bold shadow-sm flex items-center gap-3 animate-page-in">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div> 
    @endif

    {{-- HEADER REPOSITORY (GitHub Style Header) --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            
            <div class="flex items-center gap-2 flex-wrap text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">
                <div class="w-8 h-8 rounded-xl bg-[#EE2737] flex items-center justify-center text-white mr-1 shadow-sm shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                </div>
                <a href="#" class="hover:text-[#EE2737] transition-colors text-gray-600">{{ $repository->user->name }}</a> 
                <span class="text-gray-300 font-light mx-1">/</span> 
                <a href="#" class="text-[#EE2737] hover:underline transition-colors">{{ $repository->name }}</a>
                
                <span class="ml-2 px-2.5 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wide flex items-center gap-1 {{ $repository->visibility == 'public' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                    {{ $repository->visibility }}
                </span>
            </div>

            <div class="flex gap-2 flex-wrap w-full md:w-auto">
                <form action="/repository/{{ $repository->id }}/star" method="POST" class="flex-grow md:flex-grow-0">
                    @csrf
                    @php $hasStarred = $repository->stars->contains(auth()->id()); @endphp
                    <button type="submit" class="w-full md:w-auto px-4 py-2 rounded-lg font-semibold text-[13px] border flex justify-center items-center gap-2 tap-effect transition-all {{ $hasStarred ? 'bg-yellow-50 text-yellow-700 border-yellow-300 hover:bg-yellow-100' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 {{ $hasStarred ? 'text-yellow-500 fill-yellow-500' : 'text-gray-400' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        <span>{{ $hasStarred ? 'Starred' : 'Star' }}</span>
                        <div class="w-px h-4 bg-gray-300 mx-1"></div>
                        <span>{{ $repository->stars->count() }}</span>
                    </button>
                </form>

                @if($isOwner || $isCollaborator)
                    @if($repository->git_path)
                    <form action="/repository/{{ $repository->id }}/sync-git" method="POST" class="flex-grow md:flex-grow-0">
                        @csrf
                        <button type="submit" class="w-full md:w-auto bg-gray-900 border border-gray-800 text-white px-4 py-2 rounded-lg font-semibold text-[13px] flex justify-center items-center gap-2 hover:bg-black tap-effect transition-all shadow-sm">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Sync Git
                        </button>
                    </form>
                    @endif

                    @if($repository->files->count() > 0)
                    <form action="/repository/{{ $repository->id }}/clear" method="POST" onsubmit="confirmSubmit(event, 'Hapus Semua File?', 'Yakin ingin menghapus seluruh file dalam satu klik? File tidak dapat dikembalikan!', 'Ya, Hapus Semua')" class="flex-grow md:flex-grow-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full md:w-auto bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-lg font-semibold text-[13px] flex justify-center items-center gap-2 hover:bg-gray-50 hover:text-red-600 tap-effect transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Reset
                        </button>
                    </form>
                    @endif
                @endif

                {{-- TOMBOL HAPUS REPO (Hanya Muncul Untuk Owner) --}}
                @if($isOwner)
                <form action="/repository/{{ $repository->id }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus permanen repositori ini beserta isinya? Tindakan ini tidak dapat dibatalkan.')" class="flex-grow md:flex-grow-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full md:w-auto bg-[#EE2737] text-white px-4 py-2 rounded-lg font-semibold text-[13px] flex justify-center items-center gap-2 hover:bg-[#D41C29] tap-effect transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Repo
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- TAB NAVIGATION --}}
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-6 overflow-x-auto hide-scrollbar" aria-label="Tabs">
            <button onclick="switchTab('code')" id="tab-btn-code" class="tab-active py-3 px-1 text-[14px] whitespace-nowrap transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                Karya & File
            </button>
            <button onclick="switchTab('docs')" id="tab-btn-docs" class="tab-inactive py-3 px-1 text-[14px] whitespace-nowrap transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Panduan & Dokumentasi
            </button>
        </nav>
    </div>

    {{-- MAIN GRID CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- LEFT COLUMN (Main Content) --}}
        <div class="lg:col-span-3">
            
            {{-- TAB 1: KARYA & FILE --}}
            <div id="tab-content-code" class="flex flex-col gap-6">
                
                {{-- Toolbar: Clone & Upload --}}
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="flex-1 w-full bg-gray-50 border border-gray-200 rounded-lg flex items-center overflow-hidden h-10">
                        <span class="bg-gray-100 text-gray-500 font-bold text-xs px-3 py-full h-full flex items-center border-r border-gray-200">HTTPS</span>
                        <input type="text" readonly value="git clone {{ $repository->git_path ? url('/git/' . basename($repository->git_path)) : 'Belum terhubung ke Git Server' }}" class="flex-1 bg-transparent px-3 text-[12px] font-mono text-gray-700 outline-none w-full">
                        <button onclick="navigator.clipboard.writeText('git clone {{ $repository->git_path ? url('/git/' . basename($repository->git_path)) : '' }}'); alert('Perintah clone disalin!')" class="h-full px-3 text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition-colors" title="Copy">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    @if($isOwner || $isCollaborator)
                    <div class="w-full md:w-auto relative group">
                        <form action="/repository/{{ $repository->id }}/upload" method="POST" enctype="multipart/form-data" id="uploadFormSlim">
                            @csrf
                            <input type="file" name="files[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('uploadFormSlim').submit();">
                            <div class="h-10 bg-[#EE2737] text-white px-4 rounded-lg font-semibold text-[13px] shadow-sm flex items-center justify-center gap-2 group-hover:bg-[#D41C29] transition-colors relative z-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Upload / Drop File
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- FILE EXPLORER --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <form action="/repository/{{ $repository->id }}/download-zip" method="POST" id="downloadZipForm">
                        @csrf
                        {{-- Toolbar File Explorer --}}
                        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center justify-between text-[13px] font-bold text-gray-700">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-[#EE2737] focus:ring-[#EE2737] w-4 h-4 cursor-pointer">
                                <span>File Explorer</span>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                {{-- View Toggle (Grid / List) --}}
                                <div class="hidden sm:flex items-center bg-gray-200/50 p-0.5 rounded-md border border-gray-200">
                                    <button type="button" onclick="setFileView('list')" id="btn-view-list" class="p-1 rounded bg-white shadow-sm text-gray-800 transition" title="List View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </button>
                                    <button type="button" onclick="setFileView('grid')" id="btn-view-grid" class="p-1 rounded text-gray-400 hover:text-gray-800 transition" title="Grid View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    </button>
                                </div>
                                
                                <div class="w-px h-4 bg-gray-300 hidden sm:block"></div>

                                <button type="submit" class="bg-gray-900 text-white px-3 py-1.5 rounded-md text-[11px] font-semibold hover:bg-black transition-colors tap-effect flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        {{-- File List Container --}}
                        <ul id="file-container" class="file-container view-list font-medium">
                            @forelse($repository->files as $file)
                            @php
                                $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                            @endphp
                            <li>
                                <div class="file-main">
                                    <input type="checkbox" name="file_ids[]" value="{{ $file->id }}" class="file-checkbox rounded border-gray-300 text-[#EE2737] focus:ring-[#EE2737] w-4 h-4 cursor-pointer">
                                    
                                    <div class="file-icon-wrap text-gray-400">
                                        @if(in_array($ext, ['png','jpg','jpeg','gif', 'svg']))
                                            <svg class="text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @elseif(in_array($ext, ['php','js','html','css','py','json']))
                                            <svg class="text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                        @else
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>

                                    <button type="button" onclick="openPreview({{ $file->id }}, '{{ basename($file->file_name) }}')" class="file-name text-gray-800 hover:text-[#EE2737] hover:underline transition cursor-pointer font-semibold">
                                        {{ $file->file_name }}
                                    </button>
                                </div>
                                
                                <div class="file-meta">
                                    <span class="file-size text-gray-400 text-[11px] font-mono">{{ $file->file_size }}</span>
                                    <a href="/repository/file/{{ $file->id }}/download" class="text-gray-500 hover:text-[#EE2737] hover:bg-red-50 rounded-lg transition p-1.5" title="Download">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                </div>
                            </li>
                            @empty
                            <li class="px-5 py-10 text-center text-gray-500 text-[13px] block border-none">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    Belum ada file yang diunggah.
                                </div>
                            </li>
                            @endforelse
                        </ul>
                    </form>
                </div>
                
                {{-- README SECTION --}}
                @if($readmeContent)
                <div class="border border-gray-200 rounded-xl overflow-hidden bg-white mt-4">
                    <div class="bg-white border-b border-gray-200 px-5 py-3 font-bold text-[13px] text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        README.md
                    </div>
                    <div class="p-6 md:p-8 prose prose-sm md:prose-base prose-red max-w-none text-gray-700 font-medium">
                        {!! $readmeContent !!}
                    </div>
                </div>
                @endif
            </div>

            {{-- TAB 2: PANDUAN PENGGUNAAN (DOCUMENTATION) --}}
            <div id="tab-content-docs" class="hidden flex-col gap-6">
                
                {{-- CLI Download Hero --}}
                @if($isOwner || $isCollaborator)
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 md:p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-500/20 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-extrabold mb-2">Docu-Push CLI v1.0</h3>
                        <p class="text-[13px] text-gray-300 max-w-md">Push file langsung dari komputermu ke repositori ini tanpa perlu buka browser. Cocok untuk update proyek yang cepat.</p>
                    </div>
                    <a href="/repository/{{ $repository->id }}/download-cli" class="relative z-10 bg-[#EE2737] hover:bg-[#D41C29] text-white px-6 py-3 rounded-xl text-[13px] font-semibold shadow-sm flex items-center gap-2 transition-all tap-effect whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Unduh CLI (.BAT)
                    </a>
                </div>
                @endif

                {{-- Documentation Reading Area (Highly Detailed) --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-10 shadow-sm">
                    <article class="prose prose-sm md:prose-base prose-red max-w-none text-gray-700">
                        <h1 class="text-gray-900 border-b pb-4">Panduan Mengelola Repositori</h1>
                        <p class="lead">Dokumentasi ini akan memandu Anda untuk berkolaborasi, mengunduh, dan mengunggah kode ke repositori <strong>{{ $repository->name }}</strong> menggunakan Git maupun tools yang tersedia di platform Smecone.</p>
                        
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg my-6 not-prose">
                            <h4 class="text-blue-800 font-bold mb-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Prasyarat Sistem
                            </h4>
                            <p class="text-blue-700 text-sm m-0">Pastikan <a href="https://git-scm.com/downloads" target="_blank" class="underline font-bold">Git</a> sudah terinstal di komputer/laptop Anda sebelum memulai langkah-langkah di bawah ini.</p>
                        </div>

                        <h2 id="clone">1. Mengunduh Kode (Clone)</h2>
                        <p>Untuk mulai bekerja di komputer lokal, Anda perlu melakukan cloning. Buka <strong>Terminal</strong> (Mac/Linux) atau <strong>Git Bash / Command Prompt</strong> (Windows) dan jalankan perintah ini:</p>
                        
                        <div class="relative group mt-4 mb-6">
                            <div class="absolute -inset-1 bg-gradient-to-r from-red-400 to-red-600 rounded-lg blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                            <pre class="relative bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto shadow-inner"><code class="language-bash">git clone {{ $repository->git_path ? url('/git/' . basename($repository->git_path)) : 'https://smecone-hub.test/repo-url.git' }}
cd {{ Str::slug($repository->name) }}</code></pre>
                        </div>

                        <h2 id="push-terminal">2. Mengunggah Kode via Terminal (CLI)</h2>
                        <p>Jika Anda sudah melakukan perubahan pada file, simpan pembaruan tersebut ke server menggunakan perintah dasar Git berikut:</p>
                        <ol>
                            <li>
                                <strong>Menambahkan Perubahan:</strong> Daftarkan semua file yang berubah untuk di-commit.
                                <pre class="bg-gray-100 text-gray-800 p-3 rounded-md mt-2"><code>git add .</code></pre>
                            </li>
                            <li>
                                <strong>Membuat Commit:</strong> Berikan pesan yang jelas mengenai perubahan apa yang Anda buat.
                                <pre class="bg-gray-100 text-gray-800 p-3 rounded-md mt-2"><code>git commit -m "Menambahkan fitur login pengguna"</code></pre>
                            </li>
                            <li>
                                <strong>Push ke Server:</strong> Unggah commit tersebut ke server Smecone Hub.
                                <pre class="bg-gray-100 text-gray-800 p-3 rounded-md mt-2"><code>git push origin main</code></pre>
                            </li>
                        </ol>

                        <h2 id="push-vscode">3. Mengunggah Kode via Visual Studio Code (GUI)</h2>
                        <p>Bagi Anda yang lebih nyaman menggunakan tampilan visual (GUI) di VS Code, ikuti langkah ini:</p>
                        <ul>
                            <li>Buka tab <strong>Source Control</strong> (Ikon cabang/branch di sidebar kiri VS Code atau tekan <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">Ctrl</kbd> + <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">Shift</kbd> + <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">G</kbd>).</li>
                            <li>Tulis pesan commit Anda pada kotak teks (kolom <em>Message</em>).</li>
                            <li>Klik tombol biru <strong>Commit</strong> (atau tanda Centang ✔️ di bagian atas panel).</li>
                            <li>Klik tombol <strong>Sync Changes</strong> (atau klik ikon tiga titik <code>...</code> lalu pilih <strong>Pull, Push &rarr; Push</strong>).</li>
                        </ul>

                        <h2 id="conflict">4. Menangani Konflik (Merge Conflict)</h2>
                        <p>Jika Anda berkolaborasi dengan teman, terkadang Git menolak proses Push Anda karena teman Anda sudah mengunggah kode baru di baris yang sama. Lakukan ini:</p>
                        <ol>
                            <li>Jalankan <code>git pull origin main</code>.</li>
                            <li>Buka VS Code. File yang berkonflik akan ditandai dengan warna.</li>
                            <li>Pilih <em>"Accept Current Change"</em> (kode Anda), <em>"Accept Incoming Change"</em> (kode teman), atau <em>"Accept Both"</em>.</li>
                            <li>Simpan file, lakukan <code>git add .</code>, lalu <code>git commit</code>, dan <code>git push</code> kembali.</li>
                        </ol>

                    </article>
                </div>

                {{-- Commit History (Changelog) --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mt-2">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 font-bold text-[13px] text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Perubahan (Changelog)
                    </div>
                    <div class="p-6">
                        @if(isset($gitLog) && count($gitLog) > 0)
                            <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                                @foreach($gitLog as $log)
                                <div class="relative pl-6 group">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 bg-white border-2 border-[#EE2737] rounded-full group-hover:bg-[#EE2737] transition-colors"></div>
                                    <p class="text-[13px] font-bold text-gray-900 mb-1">{{ $log['message'] }}</p>
                                    <div class="flex items-center gap-2 text-[11px] text-gray-500 font-medium">
                                        <span class="text-blue-600 font-mono bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ substr($log['hash'], 0, 7) }}</span>
                                        <span>•</span>
                                        <span class="font-bold text-gray-700">{{ $log['author'] }}</span>
                                        <span>•</span>
                                        <span>{{ $log['time'] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-gray-500 text-[13px] font-medium">Belum ada riwayat commit terekam di repository ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            
        </div>

        {{-- RIGHT COLUMN (Sidebar Info) --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- About Section --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3 text-[15px]">Tentang</h3>
                <p class="text-[13px] text-gray-600 mb-5 font-medium leading-relaxed">{{ $repository->description ?? 'Tidak ada deskripsi rinci untuk repositori ini.' }}</p>
                
                @if($repository->demo_link)
                <a href="{{ $repository->demo_link }}" target="_blank" class="w-full mb-5 bg-white border border-gray-300 text-gray-700 py-2.5 rounded-lg font-semibold text-[13px] flex justify-center items-center gap-2 hover:bg-gray-50 hover:text-blue-600 transition-all tap-effect shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Preview Live App
                </a>
                @endif

                <div class="pt-4 border-t border-gray-100 flex flex-col gap-3 text-[13px] text-gray-600 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        <strong>{{ $repository->stars->count() }}</strong> stars
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <strong>{{ $repository->downloads_count }}</strong> downloads
                    </div>
                    @if($repository->major)
                    <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-50">
                        <span class="w-2 h-2 rounded-full bg-[#EE2737] ml-1 mr-1"></span>
                        Jurusan <strong class="text-[#EE2737]">{{ $repository->major }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Collaborators Section --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="{ addOpen: false }">

                {{-- Header --}}
                <div class="flex justify-between items-center px-5 pt-5 pb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <h3 class="font-bold text-gray-900 text-[14px]">Tim Developer</h3>
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $repository->collaborators->count() + 1 }}</span>
                    </div>
                    @if($isOwner)
                    <button @click="addOpen = !addOpen"
                            :class="addOpen ? 'bg-gray-100 text-gray-700 rotate-45' : 'bg-[#EE2737] text-white'"
                            class="w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200 hover:opacity-80 tap-effect shrink-0"
                            title="Tambah Collaborator">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                    @endif
                </div>

                {{-- Form Tambah Collaborator (hanya owner, collapsible) --}}
                @if($isOwner)
                <div x-show="addOpen" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="px-5 pb-4">
                    <form action="/repository/{{ $repository->id }}/collaborator" method="POST">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input type="text" name="username" placeholder="Username atau Email..." required
                                       class="w-full pl-9 pr-3 py-2.5 text-[13px] bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#EE2737]/20 focus:border-[#EE2737] focus:bg-white transition-all font-medium placeholder-gray-400">
                            </div>
                            <button type="submit" class="w-full sm:w-auto bg-[#EE2737] text-white px-4 py-2.5 rounded-xl text-[13px] font-bold hover:bg-[#D41C29] transition-colors tap-effect flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Tambah
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Divider --}}
                <div class="border-t border-gray-100 mx-5"></div>

                {{-- Member List --}}
                <ul class="divide-y divide-gray-50 px-2 py-2">
                    {{-- Owner --}}
                    <li class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-[#EE2737] text-white rounded-xl flex items-center justify-center font-black text-sm shadow-sm shrink-0">
                            {{ strtoupper(substr($repository->user->name, 0, 1)) }}
                        </div>
                        <div class="flex flex-col min-w-0 flex-1">
                            <span class="text-[13px] font-bold text-gray-900 truncate leading-tight">{{ $repository->user->name }}</span>
                            <span class="text-[10px] text-[#EE2737] font-bold uppercase tracking-wider leading-tight mt-0.5">Owner</span>
                        </div>
                        <div class="shrink-0 w-5 h-5 rounded-full bg-[#EE2737]/10 flex items-center justify-center">
                            <svg class="w-3 h-3 text-[#EE2737]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                    </li>

                    @forelse($repository->collaborators as $collab)
                    <li class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-9 h-9 bg-gray-100 text-gray-600 rounded-xl border border-gray-200 flex items-center justify-center font-black text-sm shrink-0 group-hover:bg-gray-200 transition-colors">
                            {{ strtoupper(substr($collab->name, 0, 1)) }}
                        </div>
                        <div class="flex flex-col min-w-0 flex-1">
                            <span class="text-[13px] font-semibold text-gray-800 truncate leading-tight">{{ $collab->name }}</span>
                            <span class="text-[10px] text-gray-400 font-medium leading-tight mt-0.5">Collaborator</span>
                        </div>
                        @if($isOwner)
                        <form action="/repository/{{ $repository->id }}/collaborator/{{ $collab->id }}" method="POST" class="shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Hapus {{ $collab->name }} dari tim?')"
                                    class="w-7 h-7 rounded-lg text-gray-300 hover:text-[#EE2737] hover:bg-red-50 flex items-center justify-center transition-colors opacity-0 group-hover:opacity-100"
                                    title="Hapus dari Tim">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                        @endif
                    </li>
                    @empty
                    <li class="px-3 py-5 text-center text-[12px] text-gray-400 font-medium">
                        Belum ada kolaborator. Klik <span class="text-[#EE2737] font-bold">+</span> untuk mengundang.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Script Inti (Tab & View Switcher) --}}
<script>
    // 1. Logika Pindah Tab (Karya vs Panduan)
    function switchTab(tabId) {
        const btnCode = document.getElementById('tab-btn-code');
        const btnDocs = document.getElementById('tab-btn-docs');
        const contentCode = document.getElementById('tab-content-code');
        const contentDocs = document.getElementById('tab-content-docs');

        if(tabId === 'code') {
            btnCode.className = 'tab-active py-3 px-1 text-[14px] whitespace-nowrap transition-colors flex items-center gap-2';
            btnDocs.className = 'tab-inactive py-3 px-1 text-[14px] whitespace-nowrap transition-colors flex items-center gap-2';
            contentCode.style.display = 'flex';
            contentDocs.style.display = 'none';
        } else {
            btnDocs.className = 'tab-active py-3 px-1 text-[14px] whitespace-nowrap transition-colors flex items-center gap-2';
            btnCode.className = 'tab-inactive py-3 px-1 text-[14px] whitespace-nowrap transition-colors flex items-center gap-2';
            contentCode.style.display = 'none';
            contentDocs.style.display = 'flex';
        }
    }
    
    // 2. Logika Grid / List View Explorer
    function setFileView(mode) {
        const container = document.getElementById('file-container');
        const btnList = document.getElementById('btn-view-list');
        const btnGrid = document.getElementById('btn-view-grid');

        if (mode === 'grid') {
            container.classList.remove('view-list');
            container.classList.add('view-grid');
            
            // Ubah style tombol (Aktifkan Grid)
            btnGrid.classList.replace('text-gray-400', 'text-gray-800');
            btnGrid.classList.replace('bg-transparent', 'bg-white');
            btnGrid.classList.add('shadow-sm');
            
            // Matikan style tombol List
            btnList.classList.replace('text-gray-800', 'text-gray-400');
            btnList.classList.remove('bg-white', 'shadow-sm');
            
            // Simpan preferensi pengguna di browser
            localStorage.setItem('smeconeFileView', 'grid');
        } else {
            container.classList.remove('view-grid');
            container.classList.add('view-list');
            
            // Ubah style tombol (Aktifkan List)
            btnList.classList.replace('text-gray-400', 'text-gray-800');
            btnList.classList.add('bg-white', 'shadow-sm');
            
            // Matikan style tombol Grid
            btnGrid.classList.replace('text-gray-800', 'text-gray-400');
            btnGrid.classList.remove('bg-white', 'shadow-sm');
            
            // Simpan preferensi
            localStorage.setItem('smeconeFileView', 'list');
        }
    }

    // Terapkan preferensi yang tersimpan saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        const savedView = localStorage.getItem('smeconeFileView');
        if (savedView === 'grid') {
            setFileView('grid');
        }
    });

    // 3. Logika Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function(e) {
        let checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });

    // Highlight.js initialization (jika ada block code)
    if(typeof hljs !== 'undefined') {
        hljs.highlightAll();
    }
</script>
@endsection