@extends('layouts.app')
@section('title', '| Chat #' . $channel->title)

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    body { overflow: hidden; } 
</style>

<div class="w-full max-w-4xl mx-auto h-[calc(100vh-60px)] md:h-[calc(100vh-80px)] flex flex-col bg-white md:border-x border-gray-200 relative">
    
    <div class="bg-white border-b border-gray-100 px-4 py-3 md:py-4 flex items-center justify-between shadow-sm z-20">
        <div class="flex items-center gap-3">
            <a href="/forum" class="text-gray-400 hover:text-red-600 bg-gray-50 p-2 rounded-full transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-black text-xl">#</div>
            <div>
                <h1 class="font-extrabold text-gray-800 text-lg leading-tight">{{ $channel->title }}</h1>
                <p class="text-xs font-medium text-gray-500 truncate w-48 md:w-96">{{ $channel->content }}</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-gray-50/50 hide-scrollbar pb-32" id="chatContainer">
        <div class="flex justify-center my-4">
            <span class="bg-gray-200/60 text-gray-500 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Channel Dibuat: {{ $channel->created_at->format('d M Y') }}</span>
        </div>

        @foreach($chats as $chat)
            @if($chat->user_id == auth()->id())
                <div class="flex justify-end gap-2 chat-message">
                    <div class="flex flex-col items-end max-w-[85%] md:max-w-[70%]">
                        <span class="text-[10px] font-bold text-gray-400 mb-1 mr-1">Kamu • {{ $chat->created_at->format('H:i') }}</span>
                        <div class="bg-red-600 text-white p-3 md:p-4 rounded-2xl rounded-tr-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed break-words">{{ $chat->content }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex justify-start gap-3 chat-message">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs shrink-0 mt-4 border border-white shadow-sm">
                        {{ substr($chat->user->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col items-start max-w-[85%] md:max-w-[70%]">
                        <span class="text-[10px] font-bold text-gray-500 mb-1 ml-1">{{ $chat->user->name }} • {{ $chat->created_at->format('H:i') }}</span>
                        <div class="bg-white border border-gray-100 text-gray-800 p-3 md:p-4 rounded-2xl rounded-tl-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed break-words">{{ $chat->content }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="absolute bottom-0 left-0 w-full bg-white border-t border-gray-100 p-3 md:p-4 z-20 mb-[68px] md:mb-0">
        <form id="chatForm" class="flex gap-2 relative">
            @csrf
            <input type="text" id="chatInput" required autocomplete="off" placeholder="Ketik pesan..." 
                   class="w-full bg-gray-100 border-transparent focus:bg-white focus:border-red-300 focus:ring-0 rounded-full py-3 md:py-4 pl-6 pr-14 text-sm font-medium transition-all shadow-inner">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 active:scale-90 transition shadow-md">
                <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatContainer = document.getElementById("chatContainer");
    const chatForm = document.getElementById("chatForm");
    const chatInput = document.getElementById("chatInput");
    
    // Auto-scroll ke bawah saat buka halaman
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // Simpan ID pesan terakhir untuk keperluan polling
    let lastChatId = {{ $chats->last()->id ?? 0 }};
    const currentUserId = {{ auth()->id() }};
    const csrfToken = document.querySelector('input[name="_token"]').value;

    // Fungsi Render Balon Chat Baru ke HTML
    function appendChat(chat, isMe) {
        let chatHtml = "";
        let time = new Date(chat.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        if(isMe) {
            chatHtml = `
            <div class="flex justify-end gap-2 chat-message mt-4">
                <div class="flex flex-col items-end max-w-[85%] md:max-w-[70%]">
                    <span class="text-[10px] font-bold text-gray-400 mb-1 mr-1">Kamu • ${time}</span>
                    <div class="bg-red-600 text-white p-3 md:p-4 rounded-2xl rounded-tr-sm shadow-sm">
                        <p class="text-sm md:text-base leading-relaxed break-words">${chat.content}</p>
                    </div>
                </div>
            </div>`;
        } else {
            let initial = chat.user.name.charAt(0).toUpperCase();
            chatHtml = `
            <div class="flex justify-start gap-3 chat-message mt-4">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs shrink-0 mt-4 border border-white shadow-sm">${initial}</div>
                <div class="flex flex-col items-start max-w-[85%] md:max-w-[70%]">
                    <span class="text-[10px] font-bold text-gray-500 mb-1 ml-1">${chat.user.name} • ${time}</span>
                    <div class="bg-white border border-gray-100 text-gray-800 p-3 md:p-4 rounded-2xl rounded-tl-sm shadow-sm">
                        <p class="text-sm md:text-base leading-relaxed break-words">${chat.content}</p>
                    </div>
                </div>
            </div>`;
        }
        
        chatContainer.insertAdjacentHTML('beforeend', chatHtml);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Aksi Saat Tombol Kirim Ditekan (POST via Fetch)
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = chatInput.value.trim();
        if(!content) return;
        
        chatInput.value = ''; // Kosongkan input langsung

        const formData = new FormData();
        formData.append('content', content);
        formData.append('_token', csrfToken);

        try {
            const response = await fetch("/forum/{{ $channel->id }}/chat", {
                method: "POST",
                body: formData,
                headers: { "Accept": "application/json" }
            });
            const data = await response.json();
            
            if(data.success) {
                appendChat(data.chat, true);
                if(data.chat.id > lastChatId) lastChatId = data.chat.id;
            }
        } catch (error) {
            console.error("Gagal mengirim pesan", error);
        }
    });

    // POLLING: Cek Pesan Baru Tiap 3 Detik
    setInterval(async () => {
        try {
            const response = await fetch(`/forum/{{ $channel->id }}/chats?last_id=${lastChatId}`);
            const newChats = await response.json();
            
            if(newChats.length > 0) {
                newChats.forEach(chat => {
                    // Hanya tambahkan pesan jika yang mengirim bukan diri sendiri (karena milik sendiri sudah ditambahkan saat POST)
                    if(chat.user_id !== currentUserId) {
                        appendChat(chat, false);
                    }
                    if(chat.id > lastChatId) lastChatId = chat.id;
                });
            }
        } catch (error) {
            console.error("Gagal menarik pesan", error);
        }
    }, 3000);

});
</script>
@endsection