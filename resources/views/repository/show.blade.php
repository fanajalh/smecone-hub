@extends('layouts.app')
@section('title', '| ' . $repository->name)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="bg-white rounded-[32px] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 mb-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 relative z-10">
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 flex-wrap text-xl md:text-2xl font-extrabold text-gray-800 tracking-tight">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-1 shadow-inner">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <a href="#" class="hover:text-red-600 transition-colors">{{ $repository->user->name }}</a> 
                    <span class="text-gray-300 font-light mx-1">/</span> 
                    <a href="#" class="text-red-600 hover:text-red-700 transition-colors">{{ $repository->name }}</a>
                </div>
                
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-widest flex items-center gap-1 {{ $repository->visibility == 'public' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                        @if($repository->visibility == 'public')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        @else
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        @endif
                        {{ $repository->visibility }}
                    </span>
                    @if($repository->major)
                        <span class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-wider border border-red-100">{{ $repository->major }}</span>
                    @else
                        <span class="px-3 py-1.5 rounded-lg bg-gray-50 text-gray-500 text-[10px] font-black uppercase tracking-wider border border-gray-200">Umum</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-2.5 flex-wrap w-full md:w-auto">
                <form action="/repository/{{ $repository->id }}/star" method="POST" class="flex-grow md:flex-grow-0">
                    @csrf
                    @php $hasStarred = $repository->stars->contains(auth()->id()); @endphp
                    <button type="submit" class="w-full md:w-auto px-5 py-3 rounded-xl font-extrabold text-[13px] shadow-sm flex justify-center items-center gap-2 tap-effect transition-all border {{ $hasStarred ? 'bg-yellow-50 text-yellow-600 border-yellow-200 hover:bg-yellow-100' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4 {{ $hasStarred ? 'text-yellow-500' : 'text-gray-400' }}" fill="{{ $hasStarred ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        <span>{{ $hasStarred ? 'Starred' : 'Star' }}</span>
                        <div class="w-px h-4 bg-gray-200 mx-1"></div>
                        <span>{{ $repository->stars->count() }}</span>
                    </button>
                </form>

                @if(($isOwner || $isCollaborator) && $repository->git_path)
                <form action="/repository/{{ $repository->id }}/sync-git" method="POST" class="flex-grow md:flex-grow-0">
                    @csrf
                    <button type="submit" class="w-full md:w-auto bg-gray-900 border border-gray-800 text-white px-5 py-3 rounded-xl font-extrabold text-[13px] shadow-sm flex justify-center items-center gap-2 hover:bg-black tap-effect transition-all">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Sync Git
                    </button>
                </form>
                @endif

                @if(($isOwner || $isCollaborator) && $repository->files->count() > 0)
                <form action="/repository/{{ $repository->id }}/clear" method="POST" onsubmit="return confirm('Yakin ingin MENGHAPUS SEMUA file web? Ini tidak bisa dibatalkan.')" class="flex-grow md:flex-grow-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full md:w-auto bg-white border border-red-200 text-red-600 px-5 py-3 rounded-xl font-extrabold text-[13px] shadow-sm flex justify-center items-center gap-2 hover:bg-red-50 hover:border-red-300 tap-effect transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Reset Web
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @if(session('success')) 
        <div class="mb-6 bg-green-50 text-green-700 px-5 py-4 rounded-[20px] text-[13px] border border-green-100 font-bold shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div> 
    @endif
    @if(session('error')) 
        <div class="mb-6 bg-red-50 text-red-700 px-5 py-4 rounded-[20px] text-[13px] border border-red-100 font-bold shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div> 
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <div class="lg:col-span-3 flex flex-col gap-6">
            
            <div class="bg-gray-900 rounded-[24px] border border-gray-800 shadow-xl overflow-hidden">
                <div class="bg-[#1e1e1e] px-5 py-3.5 border-b border-gray-800 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                            <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                            <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                        </div>
                        <span class="text-[11px] font-mono text-gray-500 font-medium">~/smecone-hub/{{ strtolower($repository->name) }}</span>
                    </div>
                </div>
                
                <div class="p-6 font-mono text-[13px] text-green-400 bg-[#0d1117] overflow-x-auto">
                    <p class="text-gray-500 mb-2"># Kloning repositori ini secara lokal (HTTPS):</p>
                    <div class="flex items-center gap-3 group">
                        <span class="text-pink-500">➜</span>
                        <span class="text-blue-400">~</span>
                        <code class="flex-1 select-all break-all text-gray-300">git clone {{ $repository->git_path ? url('/git/' . basename($repository->git_path)) : 'Belum terhubung ke Git Server' }}</code>
                    </div>
                </div>

                <div class="bg-[#161b22] px-6 py-5 border-t border-gray-800">
                    <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Commit
                    </h4>
                    @if(isset($gitLog) && count($gitLog) > 0)
                        <ul class="space-y-3">
                            @foreach($gitLog as $log)
                            <li class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 text-[13px] bg-[#0d1117] p-3.5 rounded-[14px] border border-gray-800 hover:border-gray-700 transition-colors">
                                <span class="text-yellow-400 font-mono text-xs bg-yellow-500/10 px-2 py-1 rounded-md border border-yellow-500/20">{{ substr($log['hash'], 0, 7) }}</span>
                                <span class="text-gray-300 font-medium truncate flex-1">{{ $log['message'] }}</span>
                                <span class="text-gray-500 text-[11px] font-medium shrink-0 flex items-center gap-1.5">
                                    <div class="w-4 h-4 rounded-full bg-gray-700 flex items-center justify-center text-[8px] text-white font-bold">{{ substr($log['author'], 0, 1) }}</div>
                                    {{ $log['author'] }} <span class="text-gray-600 mx-1">•</span> {{ $log['time'] }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="bg-[#0d1117] p-5 rounded-[14px] border border-gray-800 border-dashed text-center">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Belum ada commit. Repository masih kosong.</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($isOwner || $isCollaborator)
            <div class="relative group border-2 border-dashed border-red-200 hover:border-red-400 bg-red-50/30 hover:bg-red-50/50 rounded-[24px] p-8 md:p-10 flex flex-col items-center justify-center text-center transition-all duration-300 shadow-sm overflow-hidden">
                
                <form action="/repository/{{ $repository->id }}/upload" method="POST" enctype="multipart/form-data" id="uploadForm" class="absolute inset-0 w-full h-full z-10 cursor-pointer">
                    @csrf
                    <input type="file" name="files[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('uploadForm').submit();">
                </form>

                <div class="w-16 h-16 bg-white border border-red-100 rounded-full flex items-center justify-center shadow-[0_4px_20px_rgba(220,38,38,0.15)] text-red-600 mb-4 group-hover:-translate-y-2 group-hover:shadow-lg group-hover:bg-red-600 group-hover:text-white transition-all duration-300 relative z-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <h3 class="font-black text-gray-900 text-lg md:text-xl relative z-0">Smart Upload & Auto-Extract</h3>
                <p class="text-[13px] md:text-sm text-gray-500 font-medium mt-1.5 mb-6 relative z-0">Klik area ini atau tarik (drag & drop) file .ZIP Project ke sini.</p>
                
                <a href="/repository/{{ $repository->id }}/download-cli" class="relative z-20 bg-white border border-gray-200 text-gray-700 hover:text-red-600 hover:border-red-200 hover:bg-red-50 px-6 py-3 rounded-[14px] text-xs font-extrabold shadow-sm flex items-center gap-2 transition-all tap-effect cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    UNDUH DOCU-PUSH CLI (.BAT)
                </a>
            </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-[24px] overflow-hidden shadow-[0_2px_15px_rgba(0,0,0,0.02)]">
                <form action="/repository/{{ $repository->id }}/download-zip" method="POST" id="downloadZipForm">
                    @csrf
                    
                    <div class="bg-gray-50 border-b border-gray-100 px-5 md:px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3 text-[13px] font-extrabold text-gray-700">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4 cursor-pointer">
                            <span class="uppercase tracking-widest text-xs">File Explorer</span>
                        </div>
                        
                        <div class="flex items-center justify-end w-full md:w-auto">
                            <button type="submit" class="w-full md:w-auto bg-gray-900 text-white px-5 py-2.5 rounded-[14px] font-extrabold text-[12px] hover:bg-black transition-all shadow-sm tap-effect flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Down Selected
                            </button>
                        </div>
                    </div>

                    <ul id="listView" class="divide-y divide-gray-50 text-[13px] font-medium">
                        @forelse($repository->files as $file)
                        @php
                            $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                            $displayPath = str_replace('/', ' <span class="text-gray-300 font-normal mx-1">/</span> ', $file->file_name);
                        @endphp
                        <li class="px-5 md:px-6 py-3.5 hover:bg-gray-50 transition-colors flex items-center justify-between group cursor-default">
                            <div class="flex items-center gap-4 flex-1 truncate">
                                <input type="checkbox" name="file_ids[]" value="{{ $file->id }}" class="file-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4 cursor-pointer shrink-0">
                                
                                @if(in_array($ext, ['png','jpg','jpeg','gif']))
                                    <svg class="w-5 h-5 text-purple-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                @elseif(in_array($ext, ['php','js','html','css','py','json']))
                                    <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-2 16H8v-2h4v2zm4-4H8v-2h8v2zm0-4H8V8h8v2z"/></svg>
                                @endif

                                <button type="button" onclick="openPreview({{ $file->id }}, '{{ basename($file->file_name) }}')" class="text-gray-700 truncate max-w-full hover:text-red-600 hover:underline transition text-left cursor-pointer font-bold">
                                    {!! $displayPath !!}
                                </button>
                            </div>
                            
                            <div class="flex items-center gap-5 shrink-0 ml-4">
                                <span class="text-gray-400 hidden md:block text-xs font-mono">{{ $file->file_size }}</span>
                                <a href="/repository/file/{{ $file->id }}/download" class="text-gray-400 hover:text-red-600 transition p-2 hover:bg-red-50 rounded-xl" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            </div>
                        </li>
                        @empty
                        <li class="px-5 py-16 text-center text-gray-500 font-extrabold uppercase tracking-widest text-xs">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            </div>
                            Kosong Melompong.
                        </li>
                        @endforelse
                    </ul>
                </form>
            </div>
            
            @if($readmeContent)
            <div class="border border-gray-100 rounded-[24px] overflow-hidden shadow-[0_2px_15px_rgba(0,0,0,0.02)] bg-white">
                <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 font-black text-[11px] text-gray-600 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    README.md
                </div>
                <div class="p-8 md:p-10 prose prose-sm md:prose-base prose-red max-w-none text-gray-700 font-medium">
                    {!! $readmeContent !!}
                </div>
            </div>
            @endif

        </div>

        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-[0_2px_15px_rgba(0,0,0,0.02)]">
                <h3 class="font-extrabold text-gray-900 mb-4 text-[15px] tracking-tight">Tentang Project</h3>
                <p class="text-[13px] text-gray-600 mb-6 font-medium leading-relaxed">{{ $repository->description ?? 'Tidak ada deskripsi rinci untuk repositori ini.' }}</p>
                
                @if($repository->demo_link)
                <a href="{{ $repository->demo_link }}" target="_blank" class="w-full mb-6 bg-blue-600 text-white py-3 rounded-xl font-extrabold text-[13px] flex justify-center items-center gap-2 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all tap-effect">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Preview Live Website
                </a>
                @endif

                <div class="space-y-4 text-[13px] font-bold text-gray-700 border-t border-gray-100 pt-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></div>
                        <div class="flex flex-col"><span class="text-[10px] text-gray-400 uppercase tracking-widest">Diunduh</span><span class="text-gray-900">{{ $repository->downloads_count }} Kali</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-[0_2px_15px_rgba(0,0,0,0.02)]">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                    <h3 class="font-extrabold text-gray-900 text-[15px] tracking-tight">Tim Developer</h3>
                    <span class="bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full text-[10px] font-black">{{ $repository->collaborators->count() + 1 }}</span>
                </div>
                
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-full flex items-center justify-center font-black text-xs shadow-sm">{{ strtoupper(substr($repository->user->name, 0, 1)) }}</div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[13px] font-extrabold text-gray-900 truncate">{{ $repository->user->name }}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Owner / Admin</span>
                        </div>
                    </li>
                    
                    @foreach($repository->collaborators as $collab)
                    <li class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-50 text-gray-700 rounded-full border border-gray-200 flex items-center justify-center font-black text-xs">{{ strtoupper(substr($collab->name, 0, 1)) }}</div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[13px] font-bold text-gray-800 truncate">{{ $collab->name }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Collaborator</span>
                            </div>
                        </div>
                        @if($isOwner)
                        <form action="/repository/{{ $repository->id }}/collaborator/{{ $collab->id }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors p-1.5 rounded-lg" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </form>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection