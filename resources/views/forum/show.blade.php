@extends('layouts.app')
@section('title', '| Chat #' . $channel->title)

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Mencegah scroll body bawaan layout */
    body { overflow: hidden !important; }

    /* JALUR KERAS: Sembunyikan elemen Navbar/Footer dari layouts.app */
    nav, footer, #bottom-nav, .bottom-nav, [class*="fixed bottom-0"] {
        display: none !important;
    }

    /* Layout Full Screen Menutupi Segalanya */
    .chat-wrapper {
        position: fixed !important;
        top: 0 !important; 
        left: 0 !important; 
        right: 0 !important; 
        bottom: 0 !important;
        width: 100vw !important;
        height: 100dvh !important;
        z-index: 999999 !important; /* Z-index dewa buat nutupin navbar Smecone */
        background-color: #fdf2f2; /* Warna latar merah sangat muda */
        display: flex;
        flex-direction: column;
    }

    .chat-link { color: #dc2626; text-decoration: underline; word-break: break-all; } /* Link merah */
    .chat-me .chat-link { color: #fff; text-decoration: underline; font-weight: bold; }
    .chat-bubble { position: relative; word-wrap: break-word; padding-bottom: 1.1rem !important; }
    .chat-spacer { display: inline-block; width: 3.5rem; height: 1px; }
    .chat-time { position: absolute; bottom: 0.25rem; right: 0.5rem; display: flex; align-items: center; gap: 0.15rem; }
    
    .msg-anim { animation: chatPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes chatPop {
        from { opacity: 0; transform: scale(0.9) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .reaction-overlay {
        display: none; position: absolute; top: -45px; background: white; padding: 6px 12px;
        border-radius: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.15); z-index: 50; border: 1px solid #f1f5f9; gap: 10px;
    }
    .chat-bubble-container:hover .reaction-overlay,
    .chat-bubble-container:active .reaction-overlay { display: flex; } 
    textarea { resize: none; outline: none; }
    
    /* Kembalikan input chat yang ikut tersembunyi karena class "fixed bottom-0" */
    #chatFormContainer { display: flex !important; }
</style>

<div class="chat-wrapper md:relative md:max-w-4xl md:mx-auto">
    
    <div class="bg-white/95 backdrop-blur-md border-b border-red-100 px-3 py-3 flex items-center shadow-sm shrink-0 z-20">
        <a href="/forum" class="text-gray-500 hover:text-red-600 p-2 mr-2 active:bg-red-50 rounded-full transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-black text-xl shadow-inner shrink-0 mr-3">#</div>
        <div class="flex-1 truncate">
            <h1 class="font-bold text-gray-900 text-[16px] leading-tight truncate">{{ $channel->title }}</h1>
            <p class="text-[11px] font-medium text-red-500 truncate">Aktif Sekarang</p> 
        </div>
        
        @if(auth()->user()->is_teacher)
        <button onclick="toggleAssignmentModal()" class="bg-red-50 text-red-600 p-2 rounded-lg mr-2 hover:bg-red-100 transition shadow-sm border border-red-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        </button>
        @endif

        <button class="text-gray-400 hover:text-red-500 p-2 shrink-0 ml-2 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
        </button>
    </div>

    <!-- PINNED ASSIGNMENTS -->
    @if($channel->assignments->count() > 0)
    <div class="px-4 py-3 bg-white border-b border-red-50 flex gap-3 overflow-x-auto hide-scrollbar shrink-0">
        @foreach($channel->assignments as $assignment)
            @php 
                $mySubmission = $assignment->submissions->where('user_id', auth()->id())->first();
            @endphp
            <div class="min-w-[260px] max-w-[260px] bg-red-50/30 rounded-2xl p-4 border border-red-100/50 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-[13px] text-gray-900 truncate pr-2">{{ $assignment->title }}</h3>
                    <span class="text-[9px] bg-red-600 text-white px-2 py-0.5 rounded-full font-black uppercase tracking-wider">Tugas</span>
                </div>
                <p class="text-[11px] text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $assignment->description }}</p>
                <div class="flex items-center text-[10px] font-bold text-red-400 mb-4 bg-white/50 w-fit px-2 py-1 rounded-md">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    {{ $assignment->deadline->diffForHumans() }}
                </div>

                <div class="mt-auto">
                    @if(auth()->user()->is_teacher)
                        <button onclick="toggleSubmissionsModal({{ $assignment->id }})" class="w-full bg-gray-900 text-white text-[11px] font-black py-2.5 rounded-xl hover:bg-black transition shadow-sm">
                            LIST PENGUMPULAN ({{ $assignment->submissions->count() }})
                        </button>
                    @else
                        @if($mySubmission)
                            <div class="flex gap-2">
                                <div class="flex-1 bg-white text-green-600 text-[11px] font-black py-2.5 rounded-xl text-center border border-green-200 shadow-sm">
                                    TERKIRIM @if($mySubmission->grade) • NILAI: {{ $mySubmission->grade }} @endif
                                </div>
                                <button onclick="togglePrivacy({{ $mySubmission->id }})" class="px-3 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-red-500 transition shadow-sm">
                                    @if($mySubmission->is_private)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    @endif
                                </button>
                            </div>
                        @else
                            <button onclick="openSubmitModal({{ $assignment->id }}, '{{ $assignment->title }}')" class="w-full bg-red-600 text-white text-[11px] font-black py-2.5 rounded-xl hover:bg-red-700 shadow-md transition active:scale-95">
                                KERJAKAN TUGAS
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <div class="flex-1 overflow-y-auto px-4 pt-4 pb-6 space-y-4 hide-scrollbar scroll-smooth" id="chatContainer">
        <div class="flex justify-center mb-6 mt-2">
            <span class="bg-white/80 backdrop-blur-sm text-gray-500 px-4 py-1.5 rounded-lg text-[11px] font-bold shadow-sm border border-red-50">Awal Percakapan</span>
        </div>

        @foreach($chats as $chat)
            @php $isMe = $chat->user_id == auth()->id(); @endphp
            <div class="flex {{ $isMe ? 'justify-end chat-me' : 'justify-start' }} chat-bubble-container relative group msg-container" id="chat-{{ $chat->id }}">
                
                <div class="reaction-overlay {{ $isMe ? 'right-0' : 'left-0' }}">
                    <button type="button" class="btn-react text-lg hover:scale-125 transition" data-id="{{ $chat->id }}" data-emoji="❤️">❤️</button>
                    <button type="button" class="btn-react text-lg hover:scale-125 transition" data-id="{{ $chat->id }}" data-emoji="😂">😂</button>
                    <button type="button" class="btn-react text-lg hover:scale-125 transition" data-id="{{ $chat->id }}" data-emoji="👍">👍</button>
                    
                    <button type="button" class="btn-reply bg-gray-100 px-3 py-1 rounded-full text-[10px] font-black text-gray-500 ml-2 hover:bg-gray-200" 
                            data-id="{{ $chat->id }}" data-user="{{ $chat->user->name }}" data-content="{{ Str::limit($chat->content, 30) }}">
                        REPLY
                    </button>
                    
                    @if($isMe)
                    <button type="button" class="btn-edit bg-blue-50 text-blue-600 px-2 py-1 rounded-full text-[10px] font-black ml-1 hover:bg-blue-100" 
                            data-id="{{ $chat->id }}" data-content="{{ $chat->content }}">
                        EDIT
                    </button>
                    <button type="button" class="btn-delete bg-red-50 text-red-600 px-2 py-1 rounded-full text-[10px] font-black ml-1 hover:bg-red-100" 
                            data-id="{{ $chat->id }}">
                        DEL
                    </button>
                    @endif
                </div>

                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[85%] md:max-w-[70%]">
                    @if(!$isMe)
                        <span class="text-[11px] font-bold text-gray-500 mb-1 ml-1">{{ $chat->user->name }}</span>
                    @endif

                    <div class="px-3 py-2 rounded-[18px] shadow-sm chat-bubble {{ $isMe ? 'bg-[#fee2e2] text-gray-900 rounded-tr-none border border-red-200' : 'bg-white text-gray-800 rounded-tl-none border border-gray-100' }}">
                        @if($chat->reply_to_id && $chat->repliedMessage)
                            <div class="bg-black/5 rounded-lg p-2 mb-1 border-l-4 {{ $isMe ? 'border-red-500' : 'border-gray-400' }} text-[12px] opacity-90 cursor-pointer" onclick="document.getElementById('chat-{{ $chat->reply_to_id }}').scrollIntoView({behavior: 'smooth'})">
                                <span class="font-bold block text-{{ $isMe ? 'red' : 'gray' }}-600">{{ $chat->repliedMessage->user->name }}</span>
                                <span class="line-clamp-1 text-gray-500">{{ $chat->repliedMessage->content }}</span>
                            </div>
                        @endif

                        @if($chat->content)
                            <p class="text-[14px] leading-relaxed whitespace-pre-wrap chat-content" id="chat-text-{{ $chat->id }}">{!! preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="chat-link">$1</a>', htmlspecialchars($chat->content)) !!}<span class="chat-spacer"></span></p>
                        @endif

                        @if($chat->poll_data)
                            <div class="mt-2 bg-white/50 rounded-lg p-3 w-full min-w-[220px] mb-2 border border-black/5">
                                <p class="font-bold text-sm mb-3 text-gray-800">📊 {{ $chat->poll_data['question'] }}</p>
                                @foreach($chat->poll_data['options'] as $index => $option)
                                    <button onclick="alert('Kamu mem-vote: {{ $option }}')" class="w-full text-left bg-white hover:bg-red-50 hover:border-red-200 text-gray-800 text-sm font-medium py-2 px-3 rounded-md mb-1.5 transition shadow-sm border border-gray-200 flex justify-between items-center group">
                                        <span>{{ $option }}</span><div class="w-4 h-4 rounded-full border border-gray-300 group-hover:border-red-400"></div>
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <div class="chat-time text-[10px] text-gray-500 font-medium">
                            @if($chat->is_edited) <span class="italic mr-1" id="edited-mark-{{ $chat->id }}">Diedit</span> @endif
                            <span>{{ $chat->created_at->format('H:i') }}</span>
                            @if($isMe) <svg class="w-3.5 h-3.5 ml-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13.856 6.635a.75.75 0 01.032 1.06L9.207 12.378a.75.75 0 01-1.077-.015l-2.01-2.09a.75.75 0 111.088-1.035l1.479 1.536 4.109-4.207a.75.75 0 011.06-.032z"></path></svg> @endif
                        </div>
                    </div>
                    
                    @if($chat->reactions && count($chat->reactions) > 0)
                        <div class="flex gap-1 mt-0.5 {{ $isMe ? 'mr-1' : 'ml-1' }} bg-white rounded-full px-1.5 py-0.5 shadow-sm border border-red-50 z-10 -mt-2">
                            @foreach($chat->reactions as $emj => $users)
                                <div class="text-[11px] font-bold flex items-center gap-0.5 text-gray-600">
                                    {{ $emj }} <span class="text-gray-400">{{ count($users) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div id="chatFormContainer" class="w-full bg-[#f9f9f9] z-20 shrink-0 relative flex flex-col pt-1 pb-[env(safe-area-inset-bottom)] shadow-[0_-5px_10px_rgba(220,38,38,0.05)] border-t border-red-100">
        
        <div id="actionPreview" class="hidden bg-[#f9f9f9] border-t border-red-200 p-2 w-full h-[55px]">
            <div class="flex justify-between items-center border-l-4 border-red-500 bg-white rounded-r-lg pl-3 pr-2 py-1.5 h-full shadow-sm">
                <div class="flex-1 overflow-hidden">
                    <p id="actionTitle" class="text-[12px] font-bold text-red-600 mb-0.5"></p>
                    <p id="actionText" class="text-[13px] text-gray-500 truncate"></p>
                </div>
                <button type="button" onclick="cancelAction()" class="text-gray-400 hover:text-red-500 p-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg></button>
            </div>
        </div>

        <form id="chatForm" class="flex items-end gap-2 w-full p-2">
            @csrf
            <input type="hidden" id="replyToId" name="reply_to_id" value="">
            
            <button type="button" onclick="togglePollModal()" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 hover:text-red-600 transition shrink-0 mb-0.5">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>
            
            <div class="flex-1 bg-white border border-red-100 rounded-3xl flex items-end px-2 py-1 relative min-h-[44px] shadow-sm">
                <button type="button" onclick="document.getElementById('emojiPicker').classList.toggle('hidden')" class="p-2 text-gray-400 hover:text-red-500 shrink-0 mb-0.5 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>

                <div id="emojiPicker" class="hidden absolute bottom-14 left-0 bg-white border border-red-100 shadow-xl rounded-2xl p-3 grid grid-cols-5 gap-3 text-2xl z-50">
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="😀">😀</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="😂">😂</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="🥰">🥰</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="😭">😭</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="🙏">🙏</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="👍">👍</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="🔥">🔥</span>
                    <span class="cursor-pointer active:scale-125 btn-emoji" data-emoji="❤️">❤️</span>
                </div>

                <textarea id="chatInput" placeholder="Ketik pesan" rows="1" class="flex-1 bg-transparent border-none focus:ring-0 text-[15px] text-gray-900 py-2.5 max-h-28 overflow-y-auto"></textarea>
            </div>

            <button type="submit" class="w-11 h-11 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow-md active:scale-90 transition shrink-0 mb-0.5">
                <svg class="w-5 h-5 rotate-90 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
            </button>
        </form>
    </div>

    <div id="pollModal" class="hidden fixed inset-0 bg-black/60 z-[200] flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-sm p-5 shadow-2xl border border-red-100">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Buat Polling 📊</h2>
            <input type="text" id="pollQuestion" placeholder="Ajukan pertanyaan..." class="w-full bg-red-50 border border-red-100 rounded-xl p-3 mb-3 text-sm focus:ring-red-500 focus:border-red-500 font-bold outline-none transition">
            <div class="space-y-2 mb-3" id="pollOptionsContainer">
                <input type="text" class="poll-option w-full border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-red-300 transition" placeholder="Pilihan 1">
                <input type="text" class="poll-option w-full border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-red-300 transition" placeholder="Pilihan 2">
            </div>
            <button type="button" onclick="addPollOption()" class="text-red-500 text-sm font-bold mb-6 hover:text-red-600">+ Tambah Pilihan</button>
            <div class="flex gap-2">
                <button type="button" onclick="togglePollModal()" class="flex-1 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-100 transition">Batal</button>
                <button type="button" onclick="sendPoll()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl shadow-md transition">Kirim</button>
            </div>
        </div>
    </div>

    <!-- MODAL BUAT TUGAS (GURU) -->
    <div id="assignmentModal" class="hidden fixed inset-0 bg-black/60 z-[300] flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-red-100">
            <h2 class="text-lg font-black text-gray-900 mb-5 flex items-center">
                <span class="w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center mr-3 text-sm">📝</span>
                Buat Tugas Baru
            </h2>
            <form action="{{ route('assignment.store', $channel->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase mb-1 ml-1">Judul Tugas</label>
                    <input type="text" name="title" required class="w-full bg-gray-50 border border-gray-100 rounded-xl p-3 text-sm focus:ring-red-500 focus:border-red-500 font-bold outline-none transition" placeholder="Contoh: Tugas CRUD Laravel">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase mb-1 ml-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-xl p-3 text-sm focus:ring-red-500 focus:border-red-500 font-medium outline-none transition" placeholder="Jelaskan instruksi tugas..."></textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase mb-1 ml-1">Batas Waktu (Deadline)</label>
                    <input type="datetime-local" name="deadline" required class="w-full bg-gray-50 border border-gray-100 rounded-xl p-3 text-sm focus:ring-red-500 focus:border-red-500 font-bold outline-none transition">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="toggleAssignmentModal()" class="flex-1 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-100 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl shadow-md transition">Posting Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KUMPULKAN TUGAS (MURID) -->
    <div id="submitModal" class="hidden fixed inset-0 bg-black/60 z-[300] flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-red-100">
            <h2 class="text-lg font-black text-gray-900 mb-2 flex items-center">
                <span class="w-8 h-8 bg-green-600 text-white rounded-lg flex items-center justify-center mr-3 text-sm">🚀</span>
                Kirim Jawaban
            </h2>
            <p id="submitTaskTitle" class="text-[12px] text-gray-500 mb-5 font-medium ml-11"></p>
            <form id="submitForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase mb-1 ml-1">Pilih Smecone Repo Kamu</label>
                    @if($myRepositories->count() > 0)
                    <select name="repo_link" required class="w-full bg-gray-50 border border-gray-100 rounded-xl p-3 text-sm focus:ring-red-500 focus:border-red-500 font-bold outline-none transition appearance-none">
                        <option value="" disabled selected>-- Pilih Project Smecone --</option>
                        @foreach($myRepositories as $repo)
                            <option value="{{ url('/repository/'.$repo->id) }}">{{ $repo->name }}</option>
                        @endforeach
                    </select>
                    @else
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-[11px] font-bold border border-red-100">
                        Kamu belum punya Repository di Smecone Hub. <br><br>
                        <a href="/repository/create" class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg mt-2">Buat Repo Sekarang</a>
                    </div>
                    @endif
                </div>
                <p class="text-[10px] text-gray-400 italic px-1">Tugas akan dikirim sebagai "Private" (Hanya Guru yang bisa melihat) untuk mencegah plagiat.</p>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('submitModal').classList.add('hidden')" class="flex-1 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-100 transition">Batal</button>
                    @if($myRepositories->count() > 0)
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl shadow-md transition">Kirim Sekarang</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL LIST PENGUMPULAN (GURU) -->
    <div id="listSubmissionsModal" class="hidden fixed inset-0 bg-black/60 z-[300] flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 shadow-2xl border border-red-100 max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-black text-gray-900">List Pengumpulan</h2>
                <button onclick="document.getElementById('listSubmissionsModal').classList.add('hidden')" class="text-gray-400 hover:text-red-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-2 space-y-3 hide-scrollbar" id="submissionsListContainer">
                <!-- Content injected by JS -->
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const currentUserId = {{ auth()->id() }};
    const assignmentsData = @json($channel->assignments);
    
    // STATE GLOBAL
    let stateIsEditing = false;
    let stateEditId = null;
    let lastChatId = {{ $chats->last()->id ?? 0 }};

    function toggleAssignmentModal() { document.getElementById('assignmentModal').classList.toggle('hidden'); }
    
    function openSubmitModal(id, title) {
        const modal = document.getElementById('submitModal');
        const form = document.getElementById('submitForm');
        document.getElementById('submitTaskTitle').innerText = title;
        form.action = `/assignment/${id}/submit`;
        modal.classList.remove('hidden');
    }

    function toggleSubmissionsModal(assignmentId) {
        const modal = document.getElementById('listSubmissionsModal');
        const container = document.getElementById('submissionsListContainer');
        const assignment = assignmentsData.find(a => a.id == assignmentId);
        
        container.innerHTML = '';
        
        if(assignment.submissions.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-400 py-10 font-bold">Belum ada yang mengumpulkan.</p>';
        } else {
            assignment.submissions.forEach(sub => {
                container.innerHTML += `
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-black text-gray-900 text-sm">${sub.user.name}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">${new Date(sub.created_at).toLocaleString()}</p>
                            </div>
                            <div class="bg-white px-3 py-1 rounded-full border border-gray-100 text-[12px] font-black text-red-600">
                                ${sub.grade || 'BELUM DINILAI'}
                            </div>
                        </div>
                        <a href="${sub.repo_link}" target="_blank" class="block bg-white text-blue-600 text-[12px] p-3 rounded-xl border border-blue-100 truncate mb-3 font-medium hover:underline">
                            🔗 ${sub.repo_link}
                        </a>
                        <form action="/submission/${sub.id}/grade" method="POST" class="flex gap-2">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="number" name="grade" placeholder="Nilai" class="w-20 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500 font-bold">
                            <button type="submit" class="flex-1 bg-gray-900 text-white text-[11px] font-black rounded-lg hover:bg-black transition">BERI NILAI</button>
                        </form>
                    </div>
                `;
            });
        }
        modal.classList.remove('hidden');
    }

    async function togglePrivacy(subId) {
        await fetch(`/submission/${subId}/toggle-privacy`, {
            method: "POST", headers: { "X-CSRF-TOKEN": csrfToken }
        });
        window.location.reload();
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
    }

    // Aksi Klik Melayang (Event Delegation)
    document.addEventListener('click', async function(e) {
        // Edit Pesan
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

        // Reply Pesan
        if (e.target.closest('.btn-reply')) {
            const btn = e.target.closest('.btn-reply');
            stateIsEditing = false;
            document.getElementById('replyToId').value = btn.dataset.id;
            document.getElementById('actionTitle').innerText = "Membalas " + btn.dataset.user;
            document.getElementById('actionText').innerText = btn.dataset.content;
            document.getElementById('actionPreview').classList.remove('hidden');
            tx.focus();
        }

        // Hapus Pesan
        if (e.target.closest('.btn-delete')) {
            if(!confirm('Hapus pesan ini selamanya?')) return;
            const btn = e.target.closest('.btn-delete');
            const res = await fetch(`/forum/message/${btn.dataset.id}/delete`, {
                method: "DELETE", headers: { "X-CSRF-TOKEN": csrfToken }
            });
            if(res.ok) document.getElementById(`chat-${btn.dataset.id}`).remove();
        }

        // Pilih Emoji
        if (e.target.closest('.btn-emoji')) {
            tx.value += e.target.closest('.btn-emoji').dataset.emoji;
            document.getElementById('emojiPicker').classList.add('hidden');
            tx.focus();
        }
        
        // Reaksi Emoji
        if (e.target.closest('.btn-react')) {
            const btn = e.target.closest('.btn-react');
            const formData = new FormData();
            formData.append('emoji', btn.dataset.emoji);
            formData.append('_token', csrfToken);
            await fetch(`/forum/message/${btn.dataset.id}/react`, { method: "POST", body: formData });
            window.location.reload(); 
        }
    });

    // SUBMIT FORM (EDIT & KIRIM BARU)
    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = tx.value.trim();
        if(!content) return;

        // Ambil State
        const isEditMode = stateIsEditing;
        const targetEditId = stateEditId;
        const replyToId = document.getElementById('replyToId').value;
        
        cancelAction(); // Reset Form
        document.getElementById('emojiPicker').classList.add('hidden');

        const formData = new FormData();
        formData.append('content', content);
        formData.append('_token', csrfToken);
        
        if (isEditMode && targetEditId) {
            // PROSES EDIT PESAN
            formData.append('_method', 'PUT'); // Fake PUT Method untuk Laravel
            try {
                const res = await fetch(`/forum/message/${targetEditId}/edit`, { 
                    method: "POST", body: formData, headers: { "Accept": "application/json" } 
                });
                const data = await res.json();
                if(data.success) {
                    const chatTextEl = document.getElementById(`chat-text-${targetEditId}`);
                    if(chatTextEl) chatTextEl.innerHTML = content + '<span class="chat-spacer"></span>';
                }
            } catch (e) { console.error("Gagal Edit", e); }
        } else {
            // PROSES KIRIM PESAN BARU
            if (replyToId) formData.append('reply_to_id', replyToId);
            try {
                const res = await fetch(`/forum/{{ $channel->id }}/message`, { 
                    method: "POST", body: formData, headers: { "Accept": "application/json" } 
                });
                const data = await res.json();
                if(data.success) window.location.reload(); // Refresh instan untuk UI sementara
            } catch (e) { console.error("Gagal Kirim", e); }
        }
    });

    // SISTEM POLLING
    function togglePollModal() { document.getElementById('pollModal').classList.toggle('hidden'); }
    function addPollOption() {
        document.getElementById('pollOptionsContainer').insertAdjacentHTML('beforeend', '<input type="text" class="poll-option w-full border border-gray-200 rounded-xl p-3 text-sm mt-2 outline-none focus:border-red-300 transition" placeholder="Pilihan Baru">');
    }
    async function sendPoll() {
        const question = document.getElementById('pollQuestion').value;
        const options = Array.from(document.querySelectorAll('.poll-option')).map(opt => opt.value).filter(val => val.trim() !== '');
        if(!question || options.length < 2) { alert('Isi pertanyaan & minimal 2 opsi!'); return; }

        await fetch("/forum/{{ $channel->id }}/message", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": csrfToken },
            body: JSON.stringify({ poll_data: { question: question, options: options } })
        });
        window.location.reload();
    }

    // Polling auto fetch pesan baru
    setInterval(async () => {
        try {
            const response = await fetch(`/forum/{{ $channel->id }}/messages?last_id=${lastChatId}`);
            if(!response.ok) return;
            const newChats = await response.json();
            if(newChats.length > 0) window.location.reload(); 
        } catch(e) {}
    }, 3000);

</script>
@endsection