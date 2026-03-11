@extends('layouts.app')
@section('title', '| Chat #' . $channel->title)

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Mencegah scroll body agar UI Chat terasa seperti App Native */
    body { overflow: hidden; position: fixed; width: 100%; } 

    /* Animasi Bouncing untuk Balon Chat Baru */
    .msg-anim { animation: chatPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes chatPop {
        from { opacity: 0; transform: scale(0.8) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Floating Menu untuk Reaction */
    .reaction-overlay {
        display: none;
        position: absolute;
        top: -45px;
        background: white;
        padding: 6px 12px;
        border-radius: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 100;
        border: 1px solid #f1f5f9;
        gap: 10px;
    }
    .chat-bubble-container:active .reaction-overlay { display: flex; }
</style>

<div class="w-full max-w-4xl mx-auto h-[100dvh] flex flex-col bg-[#fdfdfd] md:border-x border-gray-100 relative">
    
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
        <button class="text-gray-300 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
    </div>

    <div class="flex-1 overflow-y-auto px-4 pt-6 pb-44 space-y-6 hide-scrollbar scroll-smooth" id="chatContainer">
        
        <div class="flex justify-center mb-4">
            <span class="bg-gray-100 text-gray-400 px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Awal Percakapan</span>
        </div>

        @foreach($chats as $chat)
            <div class="flex {{ $chat->user_id == auth()->id() ? 'justify-end' : 'justify-start' }} chat-bubble-container relative group" id="chat-{{ $chat->id }}">
                
                <div class="reaction-overlay {{ $chat->user_id == auth()->id() ? 'right-0' : 'left-0' }}">
                    <button class="text-lg hover:scale-125 transition active:scale-150">❤️</button>
                    <button class="text-lg hover:scale-125 transition active:scale-150">🔥</button>
                    <button class="text-lg hover:scale-125 transition active:scale-150">😂</button>
                    <button onclick="triggerReply('{{ $chat->user->name }}', '{{ $chat->content }}')" class="bg-gray-100 px-3 py-1 rounded-full text-[10px] font-black text-gray-500 ml-2">REPLY</button>
                </div>

                <div class="flex flex-col {{ $chat->user_id == auth()->id() ? 'items-end' : 'items-start' }} max-w-[85%]">
                    @if($chat->user_id != auth()->id())
                        <span class="text-[10px] font-black text-gray-400 mb-1 ml-1">{{ strtoupper($chat->user->name) }}</span>
                    @endif

                    <div class="px-4 py-3 rounded-[22px] shadow-sm relative {{ $chat->user_id == auth()->id() ? 'bg-red-600 text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none' }}">
                        <p class="text-[13px] md:text-sm font-medium leading-relaxed">{{ $chat->content }}</p>
                        <div class="flex items-center gap-1 mt-1 justify-end opacity-60">
                             <span class="text-[8px] font-bold">{{ $chat->created_at->format('H:i') }}</span>
                             @if($chat->user_id == auth()->id())
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.856 6.635a.75.75 0 01.032 1.06L9.207 12.378a.75.75 0 01-1.077-.015l-2.01-2.09a.75.75 0 111.088-1.035l1.479 1.536 4.109-4.207a.75.75 0 011.06-.032z"></path></svg>
                             @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="replyPreview" class="hidden absolute bottom-[135px] left-4 right-4 bg-white/90 backdrop-blur-md border border-gray-100 p-3 rounded-2xl z-50 shadow-xl animate-bounce-in">
        <div class="flex justify-between items-start border-l-4 border-red-500 pl-3">
            <div>
                <p id="replyTarget" class="text-[10px] font-black text-red-600 uppercase mb-0.5"></p>
                <p id="replyText" class="text-xs text-gray-500 italic truncate w-60"></p>
            </div>
            <button onclick="closeReply()" class="text-gray-400 p-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg></button>
        </div>
    </div>

    <div class="fixed bottom-[75px] left-0 w-full px-4 py-3 z-[60] bg-gradient-to-t from-[#fdfdfd] via-[#fdfdfd] to-transparent">
        <form id="chatForm" class="flex items-center gap-2 bg-white p-1.5 rounded-[24px] shadow-[0_10px_30px_rgba(0,0,0,0.08)] border border-gray-50">
            @csrf
            <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-400 active:scale-90 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
            
            <input type="text" id="chatInput" placeholder="Ketik pesan..." required autocomplete="off"
                   class="flex-1 bg-transparent border-none focus:ring-0 text-[14px] font-bold text-gray-800 placeholder-gray-400 py-2">

            <button type="submit" class="w-11 h-11 bg-red-600 text-white rounded-[18px] flex items-center justify-center shadow-lg shadow-red-200 active:scale-90 transition shrink-0">
                <svg class="w-5 h-5 rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
            </button>
        </form>
    </div>

</div>

<script>
// Fungsi Reply Logic
function triggerReply(user, content) {
    const preview = document.getElementById('replyPreview');
    document.getElementById('replyTarget').innerText = "Membalas " + user;
    document.getElementById('replyText').innerText = content;
    preview.classList.remove('hidden');
    document.getElementById('chatInput').focus();
}

function closeReply() {
    document.getElementById('replyPreview').classList.add('hidden');
}

document.addEventListener("DOMContentLoaded", function() {
    const chatContainer = document.getElementById("chatContainer");
    const chatForm = document.getElementById("chatForm");
    const chatInput = document.getElementById("chatInput");
    
    // Push chat ke paling bawah
    chatContainer.scrollTop = chatContainer.scrollHeight;

    let lastChatId = {{ $chats->last()->id ?? 0 }};
    const currentUserId = {{ auth()->id() }};
    const csrfToken = document.querySelector('input[name="_token"]').value;

    function appendChat(chat, isMe) {
        let dateObj = new Date(chat.created_at);
        let time = String(dateObj.getHours()).padStart(2, '0') + ":" + String(dateObj.getMinutes()).padStart(2, '0');
        
        const chatHtml = `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'} chat-bubble-container relative group msg-anim mt-4">
                <div class="reaction-overlay ${isMe ? 'right-0' : 'left-0'}">
                    <button class="text-lg">❤️</button><button class="text-lg">🔥</button><button class="text-lg">😂</button>
                    <button onclick="triggerReply('${chat.user.name}', '${chat.content}')" class="bg-gray-100 px-3 py-1 rounded-full text-[10px] font-black text-gray-500 ml-2">REPLY</button>
                </div>
                <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'} max-w-[85%]">
                    ${!isMe ? `<span class="text-[10px] font-black text-gray-400 mb-1 ml-1">${chat.user.name.toUpperCase()}</span>` : ''}
                    <div class="px-4 py-3 rounded-[22px] shadow-sm relative ${isMe ? 'bg-red-600 text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none'}">
                        <p class="text-[13px] md:text-sm font-medium leading-relaxed">${chat.content}</p>
                        <div class="flex items-center gap-1 mt-1 justify-end opacity-60">
                             <span class="text-[8px] font-bold">${time}</span>
                        </div>
                    </div>
                </div>
            </div>`;
        
        chatContainer.insertAdjacentHTML('beforeend', chatHtml);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = chatInput.value.trim();
        if(!content) return;
        
        chatInput.value = '';
        closeReply();

        const formData = new FormData();
        formData.append('content', content);
        formData.append('_token', csrfToken);

        try {
            const response = await fetch("/forum/{{ $channel->id }}/message", {
                method: "POST",
                body: formData,
                headers: { "Accept": "application/json" }
            });
            const data = await response.json();
            if(data.success) {
                appendChat(data.chat, true);
                lastChatId = data.chat.id;
            }
        } catch (error) { console.error("Error sending message:", error); }
    });

    // Polling Pesan Baru
    setInterval(async () => {
        try {
            const response = await fetch(`/forum/{{ $channel->id }}/messages?last_id=${lastChatId}`);
            const newChats = await response.json();
            if(newChats.length > 0) {
                newChats.forEach(chat => {
                    if(chat.user_id !== currentUserId) appendChat(chat, false);
                    if(chat.id > lastChatId) lastChatId = chat.id;
                });
            }
        } catch (error) {}
    }, 3000);
});
</script>
@endsection