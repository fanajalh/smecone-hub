@extends('layouts.app')
@section('title', '| Chat #' . $channel->title)

@section('content')
<style>
    /* Sembunyikan scrollbar tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Lock layout agar tidak bisa di-scroll secara keseluruhan (hanya area chat) */
    body { overflow: hidden !important; background-color: #f8fafc; }

    /* Sembunyikan Navigasi Bawaan Smecone Hub */
    nav, footer, #bottom-nav, .bottom-nav, [class*="fixed bottom-0"] {
        display: none !important;
    }

    /* FULL SCREEN APP WRAPPER */
    .chat-wrapper {
        position: fixed !important;
        top: 0 !important; 
        left: 0 !important; 
        right: 0 !important; 
        bottom: 0 !important;
        width: 100vw !important;
        height: 100dvh !important;
        z-index: 999999 !important;
        background-color: #f8fafc; /* Latar belakang abu-abu sangat muda */
        background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); /* Pattern subtle ala WA */
        background-blend-mode: multiply;
        display: flex;
        flex-direction: column;
    }

    /* CHAT BUBBLE STYLING */
    .chat-bubble { position: relative; word-wrap: break-word; padding-bottom: 1.25rem !important; }
    .chat-spacer { display: inline-block; width: 3.5rem; height: 1px; }
    .chat-time { position: absolute; bottom: 0.35rem; right: 0.6rem; display: flex; align-items: center; gap: 0.2rem; }
    
    /* LINK STYLING */
    .chat-link { color: #2563eb; text-decoration: underline; font-weight: 500; word-break: break-all; }
    .chat-me .chat-link { color: #fde047; text-decoration: underline; font-weight: 600; }

    /* ANIMASI MUNCUL PESAN */
    .msg-anim { animation: chatPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-origin: bottom; }
    @keyframes chatPop {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* REACTION OVERLAY (POPUP MENU) */
    .reaction-overlay {
        display: none; position: absolute; top: -45px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px);
        padding: 6px 12px; border-radius: 99px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 50; border: 1px solid #f1f5f9; gap: 8px; align-items: center;
        animation: fadeIn 0.2s ease-out;
    }
    .chat-bubble-container:hover .reaction-overlay,
    .chat-bubble-container:active .reaction-overlay { display: flex; } 
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    /* INPUT AREA */
    textarea { resize: none; outline: none; }
    #chatFormContainer { display: flex !important; }

    /* SEARCH OVERLAY */
    .search-overlay { position: absolute; top: 100%; left: 0; right: 0; background: rgba(255,255,255,0.98); backdrop-filter: blur(10px); border-radius: 0 0 24px 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); max-height: 65vh; overflow-y: auto; z-index: 100; border: 1px solid #f1f5f9; border-top: none; }
    .search-result-item:hover { background: #fef2f2; }
    .search-result-item.highlight-flash { animation: flashHighlight 2s ease-out; }
    @keyframes flashHighlight { 0% { background: rgba(226,31,38,0.15); } 100% { background: transparent; } }

    /* LIGHTBOX & MEDIA PREVIEW */
    .media-preview-bar { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 16px; border-radius: 20px 20px 0 0; }
    .lightbox { position: fixed; inset: 0; background: rgba(0,0,0,0.95); backdrop-filter: blur(5px); z-index: 99999; align-items: center; justify-content: center; cursor: zoom-out; }
    .lightbox:not(.hidden) { display: flex; }
    
    .tap-effect:active { transform: scale(0.95); transition: transform 0.1s; }
</style>

<div class="chat-wrapper md:relative md:max-w-4xl md:mx-auto md:shadow-2xl md:border-x md:border-gray-200">
    
    {{-- ==================== APP BAR (HEADER) ==================== --}}
    <div class="bg-white/95 backdrop-blur-xl border-b border-gray-200 px-3 py-3 flex items-center shadow-sm shrink-0 z-40">
        <a href="/forum" class="text-gray-500 hover:text-[#E21F26] p-2 mr-1 rounded-full hover:bg-red-50 transition-colors tap-effect">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        
        <div class="relative w-11 h-11 rounded-2xl bg-gradient-to-br from-red-500 to-[#E21F26] flex items-center justify-center text-white font-black text-xl shadow-sm shrink-0 mr-3">
            #
            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
        </div>
        
        <div class="flex-1 truncate cursor-pointer" onclick="toggleSearchOverlay()">
            <h1 class="font-extrabold text-gray-900 text-[16px] leading-tight truncate">{{ $channel->title }}</h1>
            <p class="text-[12px] font-medium text-gray-500 truncate flex items-center gap-1">
                <span class="text-green-500 font-bold">Online</span> • Ketuk untuk mencari
            </p> 
        </div>
        
        @if(auth()->user()->is_teacher)
        <a href="{{ route('assignment.create', $channel->id) }}" class="text-[#E21F26] bg-red-50 p-2.5 rounded-xl mr-2 hover:bg-red-100 transition-colors shadow-sm tap-effect" title="Buat Tugas">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </a>
        @endif

        <button onclick="toggleSearchOverlay()" class="text-gray-400 hover:text-[#E21F26] p-2.5 rounded-xl hover:bg-gray-100 shrink-0 transition-colors tap-effect" title="Cari Pesan">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>

        {{-- SEARCH OVERLAY --}}
        <div id="searchOverlay" class="hidden search-overlay">
            <div class="p-3 border-b border-gray-100 bg-gray-50/50">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="searchInput" placeholder="Cari pesan dalam diskusi ini..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-[14px] font-medium outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-400 transition-all shadow-sm">
                </div>
            </div>
            <div id="searchResults" class="divide-y divide-gray-100 max-h-[50vh] overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[13px] font-medium">Ketik minimal 2 karakter untuk mencari...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== PINNED ASSIGNMENTS ==================== --}}
    @if($channel->assignments->count() > 0)
    <div class="px-3 py-3 bg-white/80 backdrop-blur-md border-b border-gray-200 flex gap-3 overflow-x-auto hide-scrollbar shrink-0 shadow-sm z-30">
        @foreach($channel->assignments as $assignment)
            @php 
                $mySubmission = $assignment->submissions->where('user_id', auth()->id())->first();
            @endphp
            <div class="min-w-[320px] max-w-[320px] bg-gradient-to-br from-white to-gray-50 rounded-2xl p-4 border border-gray-200 shadow-sm flex flex-col relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#E21F26]"></div>
                
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-extrabold text-[14px] text-gray-900 truncate pr-2 group-hover:text-[#E21F26] transition-colors">{{ $assignment->title }}</h3>
                    <div class="flex items-center gap-1 shrink-0">
                        <span class="text-[9px] bg-red-100 text-red-600 border border-red-200 px-2 py-0.5 rounded-md font-black uppercase tracking-widest">Tugas</span>
                        @if(auth()->user()->is_teacher)
                        <div class="relative x-dropdown" data-dropdown="false">
                            <button onclick="toggleDropdown(this)" class="p-0.5 text-gray-400 hover:text-gray-700 bg-white border border-gray-200 rounded-md shadow-sm transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                            </button>
                            <div class="hidden absolute right-0 mt-1 w-28 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                <a href="{{ route('assignment.edit', $assignment->id) }}" class="block px-3 py-1.5 text-[11px] font-bold text-gray-700 hover:bg-gray-50 hover:text-[#E21F26] transition">Edit</a>
                                <form action="{{ route('assignment.destroy', $assignment->id) }}" method="POST" class="inline" onsubmit="confirmDelete(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-3 py-1.5 text-[11px] font-bold text-red-600 hover:bg-red-50 transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <p class="text-[12px] text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $assignment->description }}</p>
                
                <div class="flex items-center text-[10px] font-bold text-orange-600 mb-4">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Tenggat: {{ $assignment->deadline->diffForHumans() }}
                </div>

                <div class="mt-auto">
                    @if(auth()->user()->is_teacher)
                        <div class="flex flex-col gap-1.5 w-full">
                            <a href="{{ route('assignment.submissions', $assignment->id) }}" class="w-full bg-gray-900 text-white text-[10px] font-black py-2 rounded-xl hover:bg-black transition shadow-sm tap-effect text-center block">
                                LIHAT PENGUMPULAN ({{ $assignment->submissions->count() }})
                            </a>
                            <a href="/assignment/{{ $assignment->id }}/export" class="w-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black py-2 rounded-xl text-center hover:bg-emerald-600 hover:text-white transition tap-effect flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                EXPORT CSV
                            </a>
                        </div>
                    @else
                        @if($mySubmission)
                            <div class="flex gap-2">
                                <div class="flex-1 bg-green-50 text-green-600 text-[11px] font-black py-2.5 rounded-xl text-center border border-green-200 shadow-sm">
                                    TERKIRIM @if($mySubmission->grade) • NILAI: {{ $mySubmission->grade }} @endif
                                </div>
                                <button onclick="togglePrivacy({{ $mySubmission->id }})" class="px-3 bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-[#E21F26] hover:bg-red-50 transition shadow-sm tap-effect" title="Ubah Privasi Tugas">
                                    @if($mySubmission->is_private)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    @endif
                                </button>
                            </div>
                        @else
                            <button onclick="openSubmitModal({{ $assignment->id }}, '{{ $assignment->title }}')" class="w-full bg-[#E21F26] text-white text-[12px] font-extrabold py-2.5 rounded-xl hover:bg-red-700 shadow-[0_4px_12px_rgba(226,31,38,0.2)] transition active:scale-95 tap-effect">
                                KERJAKAN TUGAS
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- ==================== CHAT AREA ==================== --}}
    <div class="flex-1 overflow-y-auto px-4 pt-4 pb-6 space-y-5 hide-scrollbar scroll-smooth" id="chatContainer">
        
        <div class="flex justify-center mb-8 mt-4">
            <span class="bg-gray-200/60 backdrop-blur-sm text-gray-500 px-4 py-1.5 rounded-full text-[11px] font-bold shadow-sm">Percakapan Dimulai</span>
        </div>

        @foreach($chats as $chat)
            @php $isMe = $chat->user_id == auth()->id(); @endphp
            
            <div class="flex {{ $isMe ? 'justify-end chat-me' : 'justify-start' }} chat-bubble-container relative group msg-container msg-anim" id="chat-{{ $chat->id }}">
                
                {{-- MENU HOVER (REACTION & ACTION) --}}
                <div class="reaction-overlay {{ $isMe ? 'right-0' : 'left-0' }}">
                    <button type="button" class="btn-react text-xl hover:scale-125 transition-transform" data-id="{{ $chat->id }}" data-emoji="❤️">❤️</button>
                    <button type="button" class="btn-react text-xl hover:scale-125 transition-transform" data-id="{{ $chat->id }}" data-emoji="😂">😂</button>
                    <button type="button" class="btn-react text-xl hover:scale-125 transition-transform" data-id="{{ $chat->id }}" data-emoji="👍">👍</button>
                    <div class="w-[1px] h-5 bg-gray-200 mx-1"></div>
                    
                    <button type="button" class="btn-reply w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors" 
                            data-id="{{ $chat->id }}" data-user="{{ $chat->user->name }}" data-content="{{ Str::limit($chat->content, 30) }}" title="Balas">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                    </button>
                    
                    @if($isMe)
                    <button type="button" class="btn-edit w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-blue-100 transition-colors" 
                            data-id="{{ $chat->id }}" data-content="{{ $chat->content }}" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <button type="button" class="btn-delete w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 hover:bg-red-100 transition-colors" 
                            data-id="{{ $chat->id }}" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    @endif
                </div>

                {{-- BUBBLE CHAT --}}
                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[85%] md:max-w-[70%]">
                    
                    {{-- Nama Pengirim (Jika bukan saya) --}}
                    @if(!$isMe)
                        <span class="text-[11px] font-extrabold text-gray-500 mb-1 pl-1.5">{{ $chat->user->name }}</span>
                    @endif

                    <div class="px-3.5 py-2.5 shadow-sm chat-bubble {{ $isMe ? 'bg-gradient-to-br from-[#f84a4a] to-[#E21F26] text-white rounded-2xl rounded-tr-sm' : 'bg-white text-gray-800 rounded-2xl rounded-tl-sm border border-gray-100' }}">
                        
                        {{-- Jika Membalas Pesan --}}
                        @if($chat->reply_to_id && $chat->repliedMessage)
                            <div class="bg-black/10 rounded-xl p-2 mb-2 border-l-[3px] {{ $isMe ? 'border-white/50 text-white' : 'border-red-500 text-gray-600' }} text-[12px] opacity-90 cursor-pointer hover:opacity-100 transition-opacity" onclick="document.getElementById('chat-{{ $chat->reply_to_id }}').scrollIntoView({behavior: 'smooth'})">
                                <span class="font-extrabold block {{ $isMe ? 'text-white' : 'text-[#E21F26]' }}">{{ $chat->repliedMessage->user->name }}</span>
                                <span class="line-clamp-1 {{ $isMe ? 'text-white/80' : 'text-gray-500' }}">{{ $chat->repliedMessage->content }}</span>
                            </div>
                        @endif

                        {{-- Media Attachment --}}
                        @if($chat->media_path)
                            <div class="mb-1.5 rounded-xl overflow-hidden cursor-pointer bg-black/5" onclick="openLightbox('{{ asset('storage/' . $chat->media_path) }}', '{{ $chat->media_type }}')">
                                @if($chat->media_type === 'video')
                                    <div class="relative">
                                        <video src="{{ asset('storage/' . $chat->media_path) }}" class="max-w-[260px] md:max-w-[320px] rounded-xl" preload="metadata" muted></video>
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 hover:bg-black/10 transition-colors">
                                            <div class="w-12 h-12 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-900 shadow-lg">
                                                <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/' . $chat->media_path) }}" alt="Media" class="max-w-[260px] md:max-w-[320px] rounded-xl hover:opacity-95 transition-opacity" loading="lazy">
                                @endif
                            </div>
                        @endif

                        {{-- Teks Pesan --}}
                        @if($chat->content)
                            <p class="text-[14px] md:text-[15px] leading-relaxed whitespace-pre-wrap chat-content font-medium" id="chat-text-{{ $chat->id }}">{!! preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="chat-link hover:opacity-80 transition-opacity">$1</a>', htmlspecialchars($chat->content)) !!}<span class="chat-spacer"></span></p>
                        @endif

                        {{-- Tampilan Polling --}}
                        @if($chat->poll_data)
                            @php
                                $pollData = $chat->poll_data;
                                $votes = $pollData['votes'] ?? [];
                                $totalVotes = 0;
                                foreach($pollData['options'] as $opt) {
                                    $totalVotes += count($votes[$opt] ?? []);
                                }
                            @endphp
                            <div class="mt-2 {{ $isMe ? 'bg-white/10' : 'bg-gray-50 border border-gray-100' }} rounded-xl p-3.5 w-full min-w-[240px] mb-1.5 shadow-sm">
                                <p class="font-extrabold text-sm mb-3 {{ $isMe ? 'text-white' : 'text-gray-900' }}">📊 {{ $pollData['question'] }}</p>
                                @foreach($pollData['options'] as $index => $option)
                                    @php
                                        $optionVotes = count($votes[$option] ?? []);
                                        $percentage = $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100) : 0;
                                        $iVoted = in_array(auth()->id(), $votes[$option] ?? []);
                                    @endphp
                                    <button onclick="votePoll({{ $chat->id }}, '{{ addslashes($option) }}')" class="w-full text-left bg-white text-gray-800 text-sm font-bold py-2.5 px-3.5 rounded-lg mb-2 transition shadow-[0_2px_5px_rgba(0,0,0,0.02)] border {{ $iVoted ? 'border-[#E21F26]' : 'border-gray-200 group hover:border-[#E21F26]' }} tap-effect relative overflow-hidden">
                                        @if($totalVotes > 0)
                                            <div class="absolute inset-y-0 left-0 bg-red-50/80 transition-all duration-500 z-0" style="width: {{ $percentage }}%"></div>
                                        @endif
                                        <div class="flex justify-between items-center relative z-10 w-full">
                                            <span class="truncate pr-2 {{ $iVoted ? 'text-[#E21F26]' : '' }}">{{ $option }}</span>
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($totalVotes > 0)
                                                    <span class="text-[11px] font-extrabold {{ $iVoted ? 'text-[#E21F26]' : 'text-gray-500' }}">{{ $percentage }}%</span>
                                                @endif
                                                @if($iVoted)
                                                    <div class="w-4 h-4 rounded-full border-4 border-[#E21F26] shrink-0"></div>
                                                @else
                                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-[#E21F26] shrink-0"></div>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                                <p class="text-[10px] {{ $isMe ? 'text-white/70' : 'text-gray-400' }} font-bold mt-1 text-right">{{ $totalVotes }} suara</p>
                            </div>
                        @endif

                        {{-- Waktu & Centang --}}
                        <div class="chat-time text-[10px] {{ $isMe ? 'text-white/80' : 'text-gray-400' }} font-bold">
                            @if($chat->is_edited) <span class="italic mr-1" id="edited-mark-{{ $chat->id }}">Diedit</span> @endif
                            <span>{{ $chat->created_at->format('H:i') }}</span>
                            @if($isMe) 
                                <svg class="w-3.5 h-3.5 ml-0.5 {{ $isMe ? 'text-white' : 'text-[#E21F26]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Badges Reaksi --}}
                    @if($chat->reactions && count($chat->reactions) > 0)
                        <div class="flex flex-wrap gap-1 mt-0.5 {{ $isMe ? 'mr-2' : 'ml-2' }} bg-white rounded-full px-2 py-0.5 shadow-sm border border-gray-100 z-10 -mt-2.5 relative">
                            @foreach($chat->reactions as $emj => $users)
                                <div class="text-[11px] font-extrabold flex items-center gap-1 text-gray-600">
                                    <span>{{ $emj }}</span>
                                    <span class="text-gray-400">{{ count($users) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- ==================== INPUT AREA ==================== --}}
    <div id="chatFormContainer" class="w-full bg-[#f8fafc] z-20 shrink-0 relative flex flex-col pt-1 pb-[env(safe-area-inset-bottom)] shadow-[0_-10px_20px_rgba(0,0,0,0.03)] border-t border-gray-200">
        
        {{-- Preview Balas / Edit --}}
        <div id="actionPreview" class="hidden bg-[#f8fafc] px-3 pt-2 pb-1 w-full animate-fadeIn">
            <div class="flex justify-between items-center border-l-[4px] border-[#E21F26] bg-white rounded-xl pl-3 pr-2 py-2 shadow-sm border-y border-r border-gray-100">
                <div class="flex-1 overflow-hidden">
                    <p id="actionTitle" class="text-[12px] font-extrabold text-[#E21F26] mb-0.5"></p>
                    <p id="actionText" class="text-[13px] font-medium text-gray-500 truncate"></p>
                </div>
                <button type="button" onclick="cancelAction()" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        </div>

        {{-- Preview Media Attachment --}}
        <div id="mediaPreview" class="hidden px-3 pt-2">
            <div class="media-preview-bar flex items-center gap-3 bg-white shadow-sm border border-gray-100">
                <div id="mediaThumb" class="shrink-0 relative"></div>
                <div class="flex-1 min-w-0">
                    <p id="mediaFileName" class="text-[13px] font-bold text-gray-800 truncate"></p>
                    <p id="mediaFileSize" class="text-[11px] font-medium text-gray-400"></p>
                </div>
                <button type="button" onclick="clearMediaPreview()" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        {{-- Input Form Utama --}}
        <form id="chatForm" class="flex items-end gap-2 w-full p-2 md:px-4 md:py-3" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="replyToId" name="reply_to_id" value="">
            <input type="file" id="mediaInput" name="media" accept="image/*,video/mp4,video/webm,video/mov" class="hidden">
            
            {{-- Tombol Plus (+) Dropdown (NATIVE JS) --}}
            <div class="relative shrink-0 mb-1">
                <button type="button" onclick="document.getElementById('attachMenu').classList.toggle('hidden')" class="w-[42px] h-[42px] rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-gray-800 transition-colors tap-effect" title="Lampirkan File">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
                
                {{-- Dropdown Menu --}}
                <div id="attachMenu" class="hidden absolute bottom-[55px] left-0 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 w-48 z-[999] animate-fadeIn">
                    <button type="button" onclick="document.getElementById('attachMenu').classList.add('hidden'); document.getElementById('mediaInput').click();" class="w-full px-4 py-3 text-left text-[14px] font-extrabold text-gray-700 hover:bg-gray-50 flex items-center gap-3 transition">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg></div>
                        Foto / Video
                    </button>
                    <button type="button" onclick="document.getElementById('attachMenu').classList.add('hidden'); togglePollModal();" class="w-full px-4 py-3 text-left text-[14px] font-extrabold text-gray-700 hover:bg-gray-50 flex items-center gap-3 transition">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shadow-sm"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg></div>
                        Buat Polling
                    </button>
                </div>
            </div>
            
            {{-- Kolom Ketik Text --}}
            <div class="flex-1 bg-white border border-gray-200 rounded-[24px] flex items-end px-1 py-1 relative min-h-[46px] shadow-sm">
                
                {{-- Tombol Emoji --}}
                <button type="button" onclick="document.getElementById('emojiPicker').classList.toggle('hidden')" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-400 hover:text-yellow-500 hover:bg-gray-50 shrink-0 transition-colors tap-effect">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>

                {{-- Emoji Picker Sederhana --}}
                <div id="emojiPicker" class="hidden absolute bottom-[50px] left-0 bg-white border border-gray-100 shadow-2xl rounded-2xl p-4 grid grid-cols-6 gap-2 md:gap-3 text-2xl md:text-3xl z-50 w-[300px]">
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="😀">😀</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="😂">😂</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="🥰">🥰</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="😭">😭</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="🙏">🙏</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="👍">👍</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="🔥">🔥</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="❤️">❤️</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="🎉">🎉</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="🤔">🤔</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="🙄">🙄</span>
                    <span class="cursor-pointer hover:scale-125 transition-transform text-center btn-emoji tap-effect" data-emoji="👏">👏</span>
                </div>

                <textarea id="chatInput" name="content" placeholder="Ketik pesan..." rows="1" class="flex-1 bg-transparent border-none focus:ring-0 text-[15px] text-gray-900 py-2.5 font-medium max-h-[120px] overflow-y-auto placeholder-gray-400"></textarea>
            </div>

            {{-- Tombol Send Utama --}}
            <button type="submit" id="btnSend" class="w-[46px] h-[46px] bg-gradient-to-br from-[#ff4a4a] to-[#E21F26] hover:from-[#E21F26] hover:to-[#c1151b] text-white rounded-full flex items-center justify-center shadow-[0_4px_12px_rgba(226,31,38,0.3)] active:scale-90 transition-all shrink-0 mb-0.5">
                <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
            </button>
        </form>
    </div>

    {{-- ==================== MODALS ==================== --}}
    
    <div id="pollModal" class="hidden fixed inset-0 bg-gray-900/60 z-[300] flex items-center justify-center p-4 backdrop-blur-sm animate-fadeIn">
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-6 shadow-2xl border border-gray-100 transform transition-all scale-100">
            <h2 class="text-xl font-black text-gray-900 mb-5 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg></div>
                Buat Polling
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-bold text-gray-500 mb-1.5 ml-1">Pertanyaan</label>
                    <input type="text" id="pollQuestion" placeholder="Ajukan pertanyaan..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-[14px] focus:ring-4 focus:ring-red-500/10 focus:border-red-400 font-bold outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-gray-500 mb-1.5 ml-1">Pilihan Jawaban</label>
                    <div class="space-y-2.5" id="pollOptionsContainer">
                        <input type="text" class="poll-option w-full bg-white border border-gray-200 rounded-xl p-3 text-[14px] outline-none focus:border-red-400 focus:ring-4 focus:ring-red-500/10 font-medium transition-all" placeholder="Pilihan 1">
                        <input type="text" class="poll-option w-full bg-white border border-gray-200 rounded-xl p-3 text-[14px] outline-none focus:border-red-400 focus:ring-4 focus:ring-red-500/10 font-medium transition-all" placeholder="Pilihan 2">
                    </div>
                </div>
                <button type="button" onclick="addPollOption()" class="w-full py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-500 text-sm font-bold hover:border-red-300 hover:text-red-500 hover:bg-red-50 transition-colors">+ Tambah Pilihan</button>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="togglePollModal()" class="flex-1 bg-gray-100 text-gray-600 font-extrabold py-3.5 rounded-xl hover:bg-gray-200 transition-colors tap-effect">Batal</button>
                <button type="button" onclick="sendPoll()" class="flex-1 bg-[#E21F26] hover:bg-red-700 text-white font-extrabold py-3.5 rounded-xl shadow-[0_4px_12px_rgba(226,31,38,0.25)] transition-colors tap-effect">Kirim Polling</button>
            </div>
        </div>
    </div>



    <div id="submitModal" class="hidden fixed inset-0 bg-gray-900/60 z-[300] flex items-center justify-center p-4 backdrop-blur-sm animate-fadeIn">
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-6 md:p-8 shadow-2xl border border-gray-100">
            <h2 class="text-xl font-black text-gray-900 mb-2 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></div>
                Kirim Jawaban
            </h2>
            <p id="submitTaskTitle" class="text-[13px] text-gray-500 mb-6 font-medium bg-gray-50 p-3 rounded-xl border border-gray-100"></p>
            
            <form id="submitForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[12px] font-bold text-gray-500 mb-1.5 ml-1">Pilih Repositori Karyamu</label>
                    @if($myRepositories->count() > 0)
                        <div class="relative">
                            <select name="repo_link" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3.5 text-[14px] focus:ring-4 focus:ring-red-500/10 focus:border-red-400 font-bold outline-none transition appearance-none shadow-sm cursor-pointer">
                                <option value="" disabled selected>-- Pilih Project Smecone --</option>
                                @foreach($myRepositories as $repo)
                                    <option value="{{ url('/repository/'.$repo->id) }}">{{ $repo->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    @else
                        <div class="bg-red-50 p-4 rounded-xl text-[12px] font-medium border border-red-100 text-red-800 text-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                            Kamu belum membuat satupun proyek di Repositori. <br><br>
                            <a href="/repository/create" class="inline-block bg-[#E21F26] text-white px-5 py-2.5 rounded-lg font-bold shadow-sm hover:bg-red-700">Buat Repo Baru</a>
                        </div>
                    @endif
                </div>
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <p class="text-[11px] text-blue-700 font-medium leading-relaxed">Tugas akan di-set <strong>Private</strong> otomatis (hanya Guru yang bisa melihat) untuk mencegah menyontek.</p>
                </div>

                <div class="flex gap-3 pt-3">
                    <button type="button" onclick="document.getElementById('submitModal').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 font-extrabold py-3.5 rounded-xl hover:bg-gray-200 transition-colors tap-effect">Batal</button>
                    @if($myRepositories->count() > 0)
                        <button type="submit" class="flex-1 bg-[#E21F26] hover:bg-red-700 text-white font-extrabold py-3.5 rounded-xl shadow-[0_4px_12px_rgba(226,31,38,0.25)] transition-colors tap-effect">Kirim Jawaban</button>
                    @endif
                </div>
            </form>
        </div>
    </div>



    {{-- LIGHTBOX MEDIA --}}
    <div id="lightbox" class="lightbox hidden" onclick="closeLightbox()">
        <div id="lightboxContent" onclick="event.stopPropagation()" class="transform transition-transform scale-95 duration-300"></div>
        <button onclick="closeLightbox()" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white bg-black/40 backdrop-blur-md w-12 h-12 rounded-full flex items-center justify-center transition-colors">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<script>
    // ... [BAGIAN JAVASCRIPT TETAP SAMA PERSIS SEPERTI SEBELUMNYA] ...
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const currentUserId = {{ auth()->id() }};
    const assignmentsData = @json($channel->assignments);
    
    // STATE GLOBAL
    let stateIsEditing = false;
    let stateEditId = null;
    let lastChatId = {{ $chats->last()->id ?? 0 }};
    let selectedMediaFile = null;
    let searchDebounce = null;

    // Toggle dropdown function untuk Assignment cards
    function toggleDropdown(btn) {
        // Tutup dropdown lain yang sedang terbuka
        document.querySelectorAll('.x-dropdown > div').forEach(el => {
            if (el !== btn.nextElementSibling) el.classList.add('hidden');
        });
        const dropdown = btn.nextElementSibling;
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.x-dropdown')) {
            document.querySelectorAll('.x-dropdown > div').forEach(el => el.classList.add('hidden'));
        }
    });

    // SweetAlert2 Confirm Delete
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'Hapus Tugas?',
            text: "Tugas beserta pengumpulannya akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E21F26',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'font-bold rounded-xl px-5 py-2.5 shadow-sm',
                cancelButton: 'font-bold rounded-xl px-5 py-2.5 shadow-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    async function togglePrivacy(subId) {
        await fetch(`/submission/${subId}/toggle-privacy`, {
            method: "POST", headers: { "X-CSRF-TOKEN": csrfToken }
        });
        window.location.reload();
    }
    
    function openSubmitModal(id, title) {
        const modal = document.getElementById('submitModal');
        const form = document.getElementById('submitForm');
        document.getElementById('submitTaskTitle').innerText = title;
        form.action = `/assignment/${id}/submit`;
        modal.classList.remove('hidden');
    }




    const chatContainer = document.getElementById('chatContainer');
    const tx = document.getElementById('chatInput');

    chatContainer.scrollTop = chatContainer.scrollHeight;

    tx.addEventListener("input", function() {
        this.style.height = "auto";
        this.style.height = (this.scrollHeight) + "px";
        if(this.value === '') this.style.height = 'auto';
    });

    function cancelAction() {
        stateIsEditing = false;
        stateEditId = null;
        document.getElementById('replyToId').value = '';
        tx.value = '';
        tx.style.height = 'auto';
        document.getElementById('actionPreview').classList.add('hidden');
        clearMediaPreview();
    }

    // ==========================================
    // SEARCH SYSTEM
    // ==========================================
    function toggleSearchOverlay() {
        const overlay = document.getElementById('searchOverlay');
        overlay.classList.toggle('hidden');
        if (!overlay.classList.contains('hidden')) {
            setTimeout(() => document.getElementById('searchInput').focus(), 100);
        }
    }

    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        clearTimeout(searchDebounce);
        const q = e.target.value.trim();
        const container = document.getElementById('searchResults');

        if (q.length < 2) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[13px] font-medium">Ketik minimal 2 karakter untuk mencari...</p>
                </div>`;
            return;
        }

        container.innerHTML = '<p class="text-center text-gray-500 py-8 text-[13px] font-bold animate-pulse">Mencari pesan...</p>';

        searchDebounce = setTimeout(async () => {
            try {
                const res = await fetch(`/forum/{{ $channel->id }}/search?q=${encodeURIComponent(q)}`);
                const results = await res.json();

                if (results.length === 0) {
                    container.innerHTML = '<p class="text-center text-red-400 py-8 text-[13px] font-bold">Tidak ada pesan yang cocok.</p>';
                    return;
                }

                container.innerHTML = results.map(r => `
                    <button type="button" onclick="jumpToChat(${r.id})" class="search-result-item w-full text-left px-5 py-4 flex items-start gap-3.5 transition-colors border-b border-gray-50 last:border-0">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-red-100 to-red-50 text-red-600 border border-red-200 flex items-center justify-center font-black text-[14px] shrink-0 mt-0.5 shadow-inner">${r.user.charAt(0).toUpperCase()}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[14px] font-bold text-gray-900">${r.user}</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded font-medium">${r.time}</span>
                            </div>
                            <p class="text-[13px] text-gray-600 truncate">${highlightText(r.content, q)}</p>
                        </div>
                    </button>
                `).join('');
            } catch(e) {
                container.innerHTML = '<p class="text-center text-red-500 py-6 text-[13px] font-bold">Gagal mencari data.</p>';
            }
        }, 300);
    });

    function highlightText(text, query) {
        const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark class="bg-red-200 text-red-900 rounded-sm px-1 font-bold">$1</mark>');
    }

    function jumpToChat(chatId) {
        toggleSearchOverlay();
        document.getElementById('searchInput').value = '';
        const el = document.getElementById(`chat-${chatId}`);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('search-result-item', 'highlight-flash');
            setTimeout(() => el.classList.remove('highlight-flash'), 2500);
        }
    }

    // ==========================================
    // MEDIA UPLOAD SYSTEM
    // ==========================================
    document.getElementById('mediaInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Max 20MB
        if (file.size > 20 * 1024 * 1024) {
            alert('File terlalu besar! Maksimal ukuran file 20MB.');
            e.target.value = '';
            return;
        }

        selectedMediaFile = file;
        const preview = document.getElementById('mediaPreview');
        const thumb = document.getElementById('mediaThumb');
        const name = document.getElementById('mediaFileName');
        const size = document.getElementById('mediaFileSize');

        name.innerText = file.name;
        size.innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';

        // Generate thumbnail
        const isVideo = file.type.startsWith('video/');
        if (isVideo) {
            thumb.innerHTML = '<div class="w-[3.5rem] h-[3.5rem] bg-gray-900 rounded-xl flex items-center justify-center shadow-inner"><svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg></div>';
        } else {
            const url = URL.createObjectURL(file);
            thumb.innerHTML = `<img src="${url}" class="w-[3.5rem] h-[3.5rem] rounded-xl object-cover shadow-sm">`;
        }

        preview.classList.remove('hidden');
        tx.focus();
    });

    function clearMediaPreview() {
        selectedMediaFile = null;
        document.getElementById('mediaInput').value = '';
        document.getElementById('mediaPreview').classList.add('hidden');
        document.getElementById('mediaThumb').innerHTML = '';
    }

    // ==========================================
    // LIGHTBOX (View Gambar/Video Full)
    // ==========================================
    function openLightbox(url, type) {
        const lb = document.getElementById('lightbox');
        const content = document.getElementById('lightboxContent');
        if (type === 'video') {
            content.innerHTML = `<video src="${url}" controls autoplay class="max-w-[92vw] max-h-[90vh] rounded-2xl shadow-2xl ring-1 ring-white/10"></video>`;
        } else {
            content.innerHTML = `<img src="${url}" class="max-w-[92vw] max-h-[90vh] rounded-2xl shadow-2xl ring-1 ring-white/10">`;
        }
        lb.classList.remove('hidden');
        // Simple animation
        setTimeout(() => content.classList.replace('scale-95', 'scale-100'), 10);
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        const content = document.getElementById('lightboxContent');
        content.classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            lb.classList.add('hidden');
            content.innerHTML = '';
        }, 200);
    }

    document.addEventListener('keydown', e => { if(e.key === 'Escape') closeLightbox(); });

    // ==========================================
    // EVENT DELEGATION (Aksi Klik Melayang)
    // ==========================================
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.btn-edit')) {
            const btn = e.target.closest('.btn-edit');
            stateIsEditing = true;
            stateEditId = btn.dataset.id;
            document.getElementById('actionTitle').innerText = "Edit Pesan";
            document.getElementById('actionText').innerText = btn.dataset.content;
            tx.value = btn.dataset.content;
            document.getElementById('actionPreview').classList.remove('hidden');
            tx.focus();
        }

        if (e.target.closest('.btn-reply')) {
            const btn = e.target.closest('.btn-reply');
            stateIsEditing = false;
            document.getElementById('replyToId').value = btn.dataset.id;
            document.getElementById('actionTitle').innerText = "Membalas " + btn.dataset.user;
            document.getElementById('actionText').innerText = btn.dataset.content;
            document.getElementById('actionPreview').classList.remove('hidden');
            tx.focus();
        }

        if (e.target.closest('.btn-delete')) {
            if(!confirm('Apakah kamu yakin ingin menghapus pesan ini untuk semua orang?')) return;
            const btn = e.target.closest('.btn-delete');
            const res = await fetch(`/forum/message/${btn.dataset.id}/delete`, {
                method: "DELETE", headers: { "X-CSRF-TOKEN": csrfToken }
            });
            if(res.ok) document.getElementById(`chat-${btn.dataset.id}`).remove();
        }

        if (e.target.closest('.btn-emoji')) {
            tx.value += e.target.closest('.btn-emoji').dataset.emoji;
            document.getElementById('emojiPicker').classList.add('hidden');
            tx.focus();
        }
        
        if (e.target.closest('.btn-react')) {
            const btn = e.target.closest('.btn-react');
            const formData = new FormData();
            formData.append('emoji', btn.dataset.emoji);
            formData.append('_token', csrfToken);
            await fetch(`/forum/message/${btn.dataset.id}/react`, { method: "POST", body: formData });
            window.location.reload(); 
        }
    });

    // ==========================================
    // SUBMIT FORM (EDIT, KIRIM BARU, + MEDIA)
    // ==========================================
    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Buat FormData langsung dari elemen form HTML (Lebih native & stabil)
        const formData = new FormData(this);
        
        const contentStr = (formData.get('content') || '').trim();
        const mediaFile = formData.get('media'); // Bakal return obyek File. Jika tidak ada file yang dipilih, size = 0.
        const hasRealFile = mediaFile && mediaFile.size > 0;

        if (!contentStr && !hasRealFile) {
            return; // Jangan kirim jika kosong
        }

        const isEditMode = stateIsEditing;
        const targetEditId = stateEditId;
        const replyToId = document.getElementById('replyToId').value;
        
        // Disable button
        const btn = document.getElementById('btnSend');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'scale-90');

        cancelAction();
        document.getElementById('emojiPicker').classList.add('hidden');
        
        if (isEditMode && targetEditId) {
            formData.append('_method', 'PUT');
            try {
                const res = await fetch(`/forum/message/${targetEditId}/edit`, { 
                    method: "POST", body: formData, headers: { "Accept": "application/json" } 
                });
                const data = await res.json();
                if(data.success) {
                    const chatTextEl = document.getElementById(`chat-text-${targetEditId}`);
                    if(chatTextEl) chatTextEl.innerHTML = contentStr + '<span class="chat-spacer"></span>';
                }
            } catch (e) { console.error("Gagal Edit", e); }
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'scale-90');
        } else {
            if (replyToId) formData.set('reply_to_id', replyToId);

            try {
                // Hapus alert debug payload, langsung tembak ke path asli
                const res = await fetch(`/forum/{{ $channel->id }}/message`, { 
                    method: "POST", body: formData, headers: { "Accept": "application/json" } 
                });
                const data = await res.json();
                if(data.success) {
                    window.location.reload();
                } else if(data.errors) {
                    alert("Validasi Gagal:\n" + Object.values(data.errors).flat().join("\n"));
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'scale-90');
                } else {
                    alert(data.error || "Gagal mengirim pesan.");
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'scale-90');
                }
            } catch (e) { 
                console.error("Gagal Kirim", e);
                alert("Terjadi kesalahan jaringan.");
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'scale-90');
            }
        }
    });

    // ==========================================
    // SISTEM POLLING
    // ==========================================
    function togglePollModal() { document.getElementById('pollModal').classList.toggle('hidden'); }
    function addPollOption() {
        document.getElementById('pollOptionsContainer').insertAdjacentHTML('beforeend', '<input type="text" class="poll-option w-full bg-white border border-gray-200 rounded-xl p-3 text-[14px] outline-none focus:border-red-400 focus:ring-4 focus:ring-red-500/10 font-medium transition-all mt-2.5" placeholder="Pilihan Baru">');
    }
    async function sendPoll() {
        const question = document.getElementById('pollQuestion').value;
        const options = Array.from(document.querySelectorAll('.poll-option')).map(opt => opt.value).filter(val => val.trim() !== '');
        if(!question || options.length < 2) { alert('Harap isi pertanyaan dan minimal berikan 2 pilihan jawaban!'); return; }

        try {
            const res = await fetch("/forum/{{ $channel->id }}/message", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ poll_data: { question: question, options: options } })
            });
            const data = await res.json();
            if(data.success) {
                window.location.reload();
            } else if(data.errors) {
                alert("Validasi Gagal:\n" + Object.values(data.errors).flat().join("\n"));
            } else {
                alert(data.error || "Gagal membuat polling.");
            }
        } catch(e) {
            console.error(e);
            alert("Terjadi kesalahan jaringan saat membuat polling.");
        }
    }

    async function votePoll(chatId, option) {
        try {
            const formData = new FormData();
            formData.append('option', option);
            formData.append('_token', csrfToken);
            
            await fetch(`/forum/message/${chatId}/vote`, {
                method: "POST", body: formData
            });
            window.location.reload();
        } catch(e) {
            console.error("Gagal vote", e);
            alert("Gagal memberikan suara. Silakan coba lagi.");
        }
    }

    // Polling auto fetch pesan baru (Setiap 3 detik)
    setInterval(async () => {
        try {
            const response = await fetch(`/forum/{{ $channel->id }}/messages?last_id=${lastChatId}`);
            if(!response.ok) return;
            const newChats = await response.json();
            if(newChats.length > 0) window.location.reload(); 
        } catch(e) {}
    }, 3000);

    // Tutup overlay jika klik di luar
    document.addEventListener('click', function(e) {
        const overlay = document.getElementById('searchOverlay');
        const searchBtn = e.target.closest('[title="Cari Pesan"]');
        if (!overlay.contains(e.target) && !searchBtn && !overlay.classList.contains('hidden')) {
            overlay.classList.add('hidden');
        }
        
        const emojiPicker = document.getElementById('emojiPicker');
        const emojiBtn = e.target.closest('button[onclick*="emojiPicker"]');
        if (emojiPicker && !emojiPicker.contains(e.target) && !emojiBtn && !emojiPicker.classList.contains('hidden')) {
            emojiPicker.classList.add('hidden');
        }

        const attachMenu = document.getElementById('attachMenu');
        const attachBtn = e.target.closest('button[onclick*="attachMenu"]');
        if (attachMenu && !attachMenu.contains(e.target) && !attachBtn && !attachMenu.classList.contains('hidden')) {
            attachMenu.classList.add('hidden');
        }
    });

</script>
@endsection