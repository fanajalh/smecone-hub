@extends(auth()->user()->is_admin ? 'layouts.admin' : 'layouts.app')
@section('title', '| Chat Laporan')

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    body { overflow: hidden; } 
</style>

<div class="w-full max-w-4xl mx-auto h-[calc(100vh-60px)] md:h-[calc(100vh-80px)] flex flex-col bg-white md:border-x border-gray-200 relative">
    
    <div class="bg-white border-b border-gray-100 px-4 py-3 md:py-4 flex items-center justify-between shadow-sm z-20">
        <div class="flex items-center gap-3 md:gap-4">
            @php $backUrl = auth()->user()->is_admin ? '/admin' : '/lost-found/'.$item->id; @endphp
            <a href="{{ $backUrl }}" class="text-gray-400 hover:text-red-600 bg-gray-50 p-2 rounded-full transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden shrink-0 border border-gray-200">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                @endif
            </div>
            <div>
                <h1 class="font-extrabold text-gray-800 text-base md:text-lg leading-tight truncate w-48 md:w-96">{{ $item->item_name }}</h1>
                <p class="text-xs font-medium text-gray-500">
                    {{ auth()->user()->is_admin ? 'Chat dengan '.$item->user->name : 'Chat dengan Kesiswaan' }}
                </p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-gray-50/50 hide-scrollbar pb-32" id="chatContainer">
        <div class="flex justify-center my-4">
            <span class="bg-gray-200/60 text-gray-500 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Laporan Dibuat: {{ $item->created_at->format('d M Y') }}</span>
        </div>

        @forelse($chats as $chat)
            @if($chat->user_id == auth()->id())
                <div class="flex justify-end gap-2 chat-message">
                    <div class="flex flex-col items-end max-w-[85%] md:max-w-[70%]">
                        <span class="text-[10px] font-bold text-gray-400 mb-1 mr-1">Kamu • {{ $chat->created_at->format('H:i') }}</span>
                        <div class="bg-red-600 text-white p-3 md:p-4 rounded-2xl rounded-tr-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed break-words">{{ $chat->message }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex justify-start gap-3 chat-message">
                    <div class="w-8 h-8 rounded-full {{ $chat->user->is_admin ? 'bg-gray-800 text-white' : 'bg-white text-gray-800 border border-gray-200' }} flex items-center justify-center font-bold text-xs shrink-0 mt-4 shadow-sm">
                        {{ $chat->user->is_admin ? 'K' : substr($chat->user->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col items-start max-w-[85%] md:max-w-[70%]">
                        <span class="text-[10px] font-bold text-gray-500 mb-1 ml-1">
                            {{ $chat->user->is_admin ? 'Admin Kesiswaan' : $chat->user->name }} • {{ $chat->created_at->format('H:i') }}
                        </span>
                        <div class="bg-white border border-gray-100 text-gray-800 p-3 md:p-4 rounded-2xl rounded-tl-sm shadow-sm">
                            <p class="text-sm md:text-base leading-relaxed break-words">{{ $chat->message }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="h-full flex flex-col items-center justify-center opacity-50">
                <p class="text-gray-500 font-bold">Belum ada pesan.</p>
                <p class="text-xs text-gray-400 mt-1 text-center">Silakan koordinasi mengenai<br>penyerahan atau klaim barang ini.</p>
            </div>
        @endforelse
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
    
    chatContainer.scrollTop = chatContainer.scrollHeight;

    let lastChatId = {{ $chats->last()->id ?? 0 }};
    const currentUserId = {{ auth()->id() }};
    const csrfToken = document.querySelector('input[name="_token"]').value;

    function appendChat(chat, isMe) {
        let chatHtml = "";
        let time = new Date(chat.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        if(isMe) {
            chatHtml = `
            <div class="flex justify-end gap-2 chat-message mt-4">
                <div class="flex flex-col items-end max-w-[85%] md:max-w-[70%]">
                    <span class="text-[10px] font-bold text-gray-400 mb-1 mr-1">Kamu • ${time}</span>
                    <div class="bg-red-600 text-white p-3 md:p-4 rounded-2xl rounded-tr-sm shadow-sm">
                        <p class="text-sm md:text-base leading-relaxed break-words">${chat.message}</p>
                    </div>
                </div>
            </div>`;
        } else {
            let avatar = chat.user.is_admin ? 'K' : chat.user.name.charAt(0).toUpperCase();
            let avatarClass = chat.user.is_admin ? 'bg-gray-800 text-white' : 'bg-white text-gray-800 border border-gray-200';
            let name = chat.user.is_admin ? 'Admin Kesiswaan' : chat.user.name;

            chatHtml = `
            <div class="flex justify-start gap-3 chat-message mt-4">
                <div class="w-8 h-8 rounded-full ${avatarClass} flex items-center justify-center font-bold text-xs shrink-0 mt-4 shadow-sm">${avatar}</div>
                <div class="flex flex-col items-start max-w-[85%] md:max-w-[70%]">
                    <span class="text-[10px] font-bold text-gray-500 mb-1 ml-1">${name} • ${time}</span>
                    <div class="bg-white border border-gray-100 text-gray-800 p-3 md:p-4 rounded-2xl rounded-tl-sm shadow-sm">
                        <p class="text-sm md:text-base leading-relaxed break-words">${chat.message}</p>
                    </div>
                </div>
            </div>`;
        }
        
        chatContainer.insertAdjacentHTML('beforeend', chatHtml);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if(!message) return;
        
        chatInput.value = '';

        const formData = new FormData();
        formData.append('message', message);
        formData.append('_token', csrfToken);

        try {
            const response = await fetch(`/chat/item/{{ $item->id }}`, {
                method: "POST",
                body: formData,
                headers: { "Accept": "application/json" }
            });
            const data = await response.json();
            
            if(data.success) {
                appendChat(data.chat, true);
                if(data.chat.id > lastChatId) lastChatId = data.chat.id;
            }
        } catch (error) { console.error("Error", error); }
    });

    setInterval(async () => {
        try {
            const response = await fetch(`/chat/item/{{ $item->id }}/fetch?last_id=${lastChatId}`);
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