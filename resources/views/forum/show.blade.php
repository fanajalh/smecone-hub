@extends('layouts.app')
@section('title', '| Chat #' . $channel->title)

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    body { overflow: hidden; position: fixed; width: 100%; } 

    .msg-anim { animation: chatPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes chatPop {
        from { opacity: 0; transform: scale(0.8) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .reaction-overlay {
        display: none; position: absolute; top: -45px; background: white; padding: 6px 12px;
        border-radius: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 100; border: 1px solid #f1f5f9; gap: 10px;
    }
    
    /* Sentuh/Hover chat untuk memunculkan menu */
    .chat-bubble-container:hover .reaction-overlay,
    .chat-bubble-container:active .reaction-overlay { display: flex; } 

    .chat-link { color: #3b82f6; text-decoration: underline; word-break: break-all; }
    .chat-me .chat-link { color: #fff; text-decoration: underline; font-weight: bold; }
</style>

<div class="w-full max-w-4xl mx-auto h-[100dvh] flex flex-col bg-[#e5ddd5] md:border-x border-gray-100 relative">
    
    <div class="bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 py-3 flex items-center justify-between shadow-sm z-50 shrink-0">
        <div class="flex items-center gap-3">
            <a href="/forum" class="text-gray-400 p-2 -ml-2 active:scale-90 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="w-10 h-10 rounded-2xl bg-red-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-red-200">#</div>
            <div>
                <h1 class="font-black text-gray-900 text-[15px] leading-tight">{{ $channel->title }}</h1>
                <p class="text-[10px] font-bold text-green-500 uppercase tracking-widest flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Aktif Sekarang
                </p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 pt-6 pb-44 space-y-6 hide-scrollbar scroll-smooth" id="chatContainer">
        <div class="flex justify-center mb-4">
            <span class="bg-white text-gray-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">Awal Percakapan</span>
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
                        <span class="text-[10px] font-black text-gray-500 mb-1 ml-1">{{ strtoupper($chat->user->name) }}</span>
                    @endif

                    <div class="px-4 py-3 rounded-[22px] shadow-sm relative {{ $isMe ? 'bg-red-600 text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none' }}">
                        
                        @if($chat->reply_to_id && $chat->repliedMessage)
                            <div class="bg-black/10 rounded-xl p-2 mb-2 border-l-4 {{ $isMe ? 'border-white/50' : 'border-red-500' }} text-[11px] opacity-90 cursor-pointer" onclick="document.getElementById('chat-{{ $chat->reply_to_id }}').scrollIntoView({behavior: 'smooth'})">
                                <span class="font-black block">{{ $chat->repliedMessage->user->name }}</span>
                                <span class="line-clamp-1">{{ $chat->repliedMessage->content }}</span>
                            </div>
                        @endif

                        @if($chat->content)
                            <p class="text-[13px] md:text-sm font-medium leading-relaxed whitespace-pre-wrap chat-content" id="chat-text-{{ $chat->id }}">{!! preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="chat-link">$1</a>', htmlspecialchars($chat->content)) !!}</p>
                        @endif

                        @if($chat->poll_data)
                            <div class="mt-2 bg-black/5 rounded-xl p-3 w-full min-w-[200px]">
                                <p class="font-black text-sm mb-2">📊 {{ $chat->poll_data['question'] }}</p>
                                @foreach($chat->poll_data['options'] as $index => $option)
                                    <button class="w-full text-left bg-white/80 hover:bg-white text-gray-800 text-xs font-bold py-2 px-3 rounded-lg mb-1 transition shadow-sm border border-gray-200">
                                        {{ $option }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-1 mt-1 justify-end opacity-60">
                            @if($chat->is_edited)
                                <span class="text-[8px] font-bold italic mr-1">Telah diedit</span>
                            @endif
                             <span class="text-[8px] font-bold">{{ $chat->created_at->format('H:i') }}</span>
                             @if($isMe)
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.856 6.635a.75.75 0 01.032 1.06L9.207 12.378a.75.75 0 01-1.077-.015l-2.01-2.09a.75.75 0 111.088-1.035l1.479 1.536 4.109-4.207a.75.75 0 011.06-.032z"></path></svg>
                             @endif
                        </div>
                    </div>

                    @if($chat->reactions && count($chat->reactions) > 0)
                        <div class="flex gap-1 mt-1 {{ $isMe ? 'mr-2' : 'ml-2' }}">
                            @foreach($chat->reactions as $emj => $users)
                                <div class="bg-white border border-gray-200 shadow-sm rounded-full px-1.5 py-0.5 text-[10px] font-bold flex items-center gap-1">
                                    {{ $emj }} <span class="text-gray-500">{{ count($users) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div id="actionPreview" class="hidden absolute bottom-[80px] left-2 right-2 bg-gray-100 border border-gray-200 p-3 rounded-t-2xl z-40 shadow-inner">
        <div class="flex justify-between items-start border-l-4 border-red-500 pl-3">
            <div>
                <p id="actionTitle" class="text-[10px] font-black text-red-600 uppercase mb-0.5"></p>
                <p id="actionText" class="text-xs text-gray-500 italic truncate w-60"></p>
            </div>
            <button type="button" onclick="cancelAction()" class="text-gray-500 p-1 bg-gray-200 rounded-full hover:bg-gray-300"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg></button>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 w-full bg-[#f0f2f5] p-2 z-[60] border-t border-gray-200">
        <form id="chatForm" class="max-w-4xl mx-auto flex items-end gap-2">
            @csrf
            <input type="hidden" id="replyToId" name="reply_to_id" value="">
            <input type="hidden" id="editChatId" value="">

            <button type="button" onclick="togglePollModal()" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 transition shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>
            
            <div class="flex-1 bg-white rounded-3xl shadow-sm border border-gray-200 flex items-center px-2 py-1 relative min-h-[44px]">
                <button type="button" onclick="document.getElementById('emojiPicker').classList.toggle('hidden')" class="p-2 text-gray-400 hover:text-gray-600 transition shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>

                <div id="emojiPicker" class="hidden absolute bottom-14 left-0 bg-white border border-gray-200 shadow-xl rounded-2xl p-3 grid grid-cols-5 gap-2 text-xl z-50">
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="😀">😀</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="😂">😂</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="🥰">🥰</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="😎">😎</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="😭">😭</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="🙏">🙏</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="👍">👍</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="🔥">🔥</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="❤️">❤️</span>
                    <span class="cursor-pointer hover:scale-125 transition btn-emoji" data-emoji="✨">✨</span>
                </div>

                <textarea id="chatInput" placeholder="Ketik pesan (Enter utk kirim)..." rows="1" class="flex-1 bg-transparent border-none focus:ring-0 text-sm font-medium text-gray-800 placeholder-gray-400 resize-none py-2 max-h-24 overflow-y-auto"></textarea>
            </div>

            <button type="submit" id="sendBtn" class="w-11 h-11 bg-teal-500 text-white rounded-full flex items-center justify-center shadow-md active:scale-90 transition shrink-0 mb-0.5">
                <svg class="w-5 h-5 rotate-90 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
            </button>
        </form>
    </div>

    <div id="pollModal" class="hidden fixed inset-0 bg-black/60 z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl">
            <h2 class="text-lg font-black text-gray-800 mb-4">Buat Polling Baru 📊</h2>
            <input type="text" id="pollQuestion" placeholder="Ajukan pertanyaan..." class="w-full border border-gray-200 rounded-xl p-3 mb-4 focus:ring-red-500 font-bold">
            <div class="space-y-2 mb-4" id="pollOptionsContainer">
                <input type="text" class="poll-option w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-red-500" placeholder="Pilihan 1">
                <input type="text" class="poll-option w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-red-500" placeholder="Pilihan 2">
            </div>
            <button type="button" onclick="addPollOption()" class="text-red-500 text-sm font-bold mb-6">+ Tambah Pilihan</button>
            <div class="flex gap-2">
                <button type="button" onclick="togglePollModal()" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-xl">Batal</button>
                <button type="button" onclick="sendPoll()" class="flex-1 bg-red-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-red-200">Kirim Polling</button>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    let isEditing = false;

    // Auto-scroll ke bawah
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // Auto-resize textarea
    const tx = document.getElementById('chatInput');
    tx.addEventListener("input", function() {
        this.style.height = "auto";
        this.style.height = (this.scrollHeight) + "px";
    });

    // Tekan ENTER untuk mengirim (Tanpa Shift)
    tx.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault(); // Cegah Enter bikin baris baru
            document.getElementById('chatForm').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }
    });

    // Event Listener Reply
    document.querySelectorAll('.btn-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            isEditing = false;
            document.getElementById('replyToId').value = this.dataset.id;
            document.getElementById('actionTitle').innerText = "Membalas " + this.dataset.user;
            document.getElementById('actionText').innerText = this.dataset.content;
            document.getElementById('actionPreview').classList.remove('hidden');
            tx.focus();
        });
    });

    // Event Listener Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            isEditing = true;
            document.getElementById('editChatId').value = this.dataset.id;
            document.getElementById('actionTitle').innerText = "Mengedit Pesan";
            document.getElementById('actionText').innerText = this.dataset.content;
            tx.value = this.dataset.content;
            document.getElementById('actionPreview').classList.remove('hidden');
            tx.focus();
        });
    });

    // Event Listener Delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async function() {
            if(!confirm('Hapus pesan ini selamanya?')) return;
            const chatId = this.dataset.id;
            const res = await fetch(`/forum/message/${chatId}/delete`, {
                method: "DELETE", headers: { "X-CSRF-TOKEN": csrfToken }
            });
            if(res.ok) document.getElementById(`chat-${chatId}`).remove();
        });
    });

    // Event Listener Reaction
    document.querySelectorAll('.btn-react').forEach(btn => {
        btn.addEventListener('click', async function() {
            const formData = new FormData();
            formData.append('emoji', this.dataset.emoji);
            formData.append('_token', csrfToken);
            await fetch(`/forum/message/${this.dataset.id}/react`, { method: "POST", body: formData });
            window.location.reload(); // Simple reload untuk update UI Emoji
        });
    });

    // Event Listener Insert Emoji
    document.querySelectorAll('.btn-emoji').forEach(btn => {
        btn.addEventListener('click', function() {
            tx.value += this.dataset.emoji;
            document.getElementById('emojiPicker').classList.add('hidden');
            tx.focus();
        });
    });

    function cancelAction() {
        isEditing = false;
        document.getElementById('replyToId').value = '';
        document.getElementById('editChatId').value = '';
        tx.value = '';
        document.getElementById('actionPreview').classList.add('hidden');
    }

    // Polling System
    function togglePollModal() { document.getElementById('pollModal').classList.toggle('hidden'); }
    function addPollOption() {
        document.getElementById('pollOptionsContainer').insertAdjacentHTML('beforeend', '<input type="text" class="poll-option w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm mt-2 focus:ring-red-500" placeholder="Pilihan Baru">');
    }
    async function sendPoll() {
        const question = document.getElementById('pollQuestion').value;
        const options = Array.from(document.querySelectorAll('.poll-option')).map(opt => opt.value).filter(val => val.trim() !== '');
        if(!question || options.length < 2) { alert('Isi pertanyaan & minimal 2 opsi!'); return; }

        await fetch("/forum/{{ $channel->id }}/message", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify({ _token: csrfToken, poll_data: { question: question, options: options } })
        });
        window.location.reload();
    }

    // Main Submit Chat Form
    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = tx.value.trim();
        if(!content) return;

        const replyToId = document.getElementById('replyToId').value;
        const editId = document.getElementById('editChatId').value;
        
        const formData = new FormData();
        formData.append('content', content);
        formData.append('_token', csrfToken);
        if (replyToId) formData.append('reply_to_id', replyToId);
        if (isEditing) formData.append('_method', 'PUT'); // Trick for Laravel PUT request

        let url = isEditing ? `/forum/message/${editId}/edit` : `/forum/{{ $channel->id }}/message`;

        try {
            const response = await fetch(url, { method: "POST", body: formData, headers: { "Accept": "application/json" } });
            if(response.ok) {
                tx.value = '';
                cancelAction();
                window.location.reload(); // Reload to fetch fresh data
            }
        } catch (error) { console.error(error); }
    });
</script>
@endsection