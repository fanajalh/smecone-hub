@extends('layouts.app')
@section('title', '| Event Smecone')

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="max-w-xl mx-auto px-0 sm:px-6 py-4 md:py-8 pb-24">
    <div class="px-4 sm:px-0 mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Agenda Smecone 🎉</h1>
            <p class="text-sm text-gray-500">Timeline event dan acara seru.</p>
        </div>
    </div>

    <div class="flex flex-col gap-6 sm:gap-8">
        @forelse($events as $event)
        <div class="bg-white sm:rounded-2xl shadow-sm border-y sm:border border-gray-200 overflow-hidden">
            
            <div class="p-3 md:p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-pink-500 to-purple-600 flex items-center justify-center text-white font-black border-2 border-white shadow-sm shrink-0">
                    O
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 flex items-center gap-1">
                        OSIS Smecone
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.441A3 3 0 018.53 2h2.94a3 3 0 012.263 1.441l1.166 1.602a1 1 0 00.793.407h1.808A3 3 0 0120 8.45v5.1a3 3 0 01-2.5 2.96h-1.808a1 1 0 00-.793.407l-1.166 1.602A3 3 0 0111.47 20H8.53a3 3 0 01-2.263-1.441l-1.166-1.602a1 1 0 00-.793-.407H2.5A3 3 0 010 13.55v-5.1a3 3 0 012.5-2.96h1.808a1 1 0 00.793-.407L6.267 3.441zm9.444 4.853a1 1 0 00-1.422-1.414L9 11.586 7.711 10.297a1 1 0 00-1.422 1.414l2 2a1 1 0 001.422 0l6-6z" clip-rule="evenodd"></path></svg>
                    </p>
                    <p class="text-[11px] text-gray-500">{{ \Carbon\Carbon::parse($event->created_at)->diffForHumans() }}</p>
                </div>
            </div>

            @if($event->gambar)
                <img src="{{ asset('storage/' . $event->gambar) }}" alt="Event" class="w-full h-auto max-h-[500px] object-cover border-y border-gray-100">
            @else
                <div class="w-full h-64 md:h-80 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex flex-col items-center justify-center text-white border-y border-gray-100 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <span class="text-6xl md:text-7xl mb-2 relative z-10 drop-shadow-lg">📅</span>
                    <h2 class="text-lg md:text-xl font-black px-6 text-center text-white drop-shadow-md relative z-10 leading-snug">{{ $event->judul }}</h2>
                </div>
            @endif

            <div class="px-4 pt-3 pb-2 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @php 
                        $isLiked = false;
                        if($event->likes) {
                            $isLiked = $event->likes->where('user_id', auth()->id())->isNotEmpty();
                        }
                    @endphp
                    <form action="{{ route('like.toggle') }}" method="POST" class="m-0" onsubmit="toggleLike(event, this)">
                        @csrf
                        <input type="hidden" name="type" value="App\Models\Event">
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        <button type="submit" class="flex items-center gap-1.5 transition active:scale-90 {{ $isLiked ? 'text-red-500' : 'text-gray-800 hover:text-red-500' }}">
                            <svg class="w-6 h-6 md:w-7 md:h-7 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span class="text-sm font-bold">{{ $event->likes ? $event->likes->count() : 0 }}</span>
                        </button>
                    </form>
                    
                    <div class="flex items-center gap-1.5 text-gray-800">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span class="text-sm font-bold">{{ $event->comments ? $event->comments->count() : 0 }}</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-1.5 text-pink-600 bg-pink-50 px-3 py-1 rounded-full border border-pink-100 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs">{{ \Carbon\Carbon::parse($event->tanggal_event)->translatedFormat('d M') }}</span>
                </div>
            </div>

            <div class="px-4 pb-3">
                <p class="text-[13px] md:text-sm text-gray-900 mb-1 leading-relaxed">
                    <span class="font-bold mr-1">OSIS Smecone</span>
                    Ayo ramaikan acara <span class="font-bold">{{ $event->judul }}</span>! Jangan sampai kelewatan ya! ✨
                </p>
                
                @if($event->deskripsi)
                    <p class="text-[13px] md:text-sm text-gray-700 mt-2">{{ $event->deskripsi }}</p>
                @endif

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="text-[10px] md:text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">📌 {{ $event->kategori ?? 'UMUM' }}</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-600 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-200">📍 {{ $event->lokasi ?? 'Smecone' }}</span>
                </div>
            </div>

            <div class="px-4 pb-4 border-t border-gray-100 pt-3 bg-gray-50/30">
                <div class="max-h-32 overflow-y-auto mb-3 space-y-2 pr-1 hide-scrollbar" id="comment-list-event-{{ $event->id }}">
                    @if($event->comments && $event->comments->count() > 0)
                        @foreach($event->comments as $comment)
                            <p class="text-[12px] md:text-[13px] leading-tight text-gray-800">
                                <span class="font-bold mr-1">{{ $comment->user->name }}</span>
                                {{ $comment->body }}
                            </p>
                        @endforeach
                    @endif
                </div>
                
                <form action="{{ route('comment.store') }}" method="POST" class="flex gap-2" onsubmit="submitComment(event, this)">
                    @csrf
                    <input type="hidden" name="type" value="App\Models\Event">
                    <input type="hidden" name="id" value="{{ $event->id }}">
                    <input type="text" name="body" required placeholder="Tambahkan komentar..." autocomplete="off" class="w-full text-[13px] bg-transparent border-none focus:ring-0 px-0 placeholder-gray-400 outline-none">
                    <button type="submit" class="text-blue-500 font-bold text-[13px] hover:text-blue-700 transition">Kirim</button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white p-10 sm:rounded-3xl border border-dashed border-gray-200 text-center mx-4 sm:mx-0">
            <div class="text-4xl mb-3">📅</div>
            <h3 class="text-lg font-bold text-gray-700">Belum Ada Postingan</h3>
            <p class="text-sm text-gray-500">Timeline event masih kosong.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    async function toggleLike(event, form) {
        event.preventDefault();
        const formData = new FormData(form);
        const button = form.querySelector('button');
        const icon = button.querySelector('svg');
        const countSpan = button.querySelector('span');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            if (data.success) {
                countSpan.textContent = data.likeCount;
                if (data.isLiked) {
                    button.classList.add('text-red-500');
                    button.classList.remove('text-gray-800');
                    icon.classList.add('fill-current');
                } else {
                    button.classList.remove('text-red-500');
                    button.classList.add('text-gray-800', 'hover:text-red-500');
                    icon.classList.remove('fill-current');
                }
            }
        } catch (error) { console.error("Error like:", error); }
    }

    async function submitComment(event, form) {
        event.preventDefault();
        const formData = new FormData(form);
        const input = form.querySelector('input[name="body"]');
        const container = form.previousElementSibling;
        
        if(input.value.trim() === '') return;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            if (data.success) {
                input.value = '';
                const p = document.createElement('p');
                p.className = 'text-[12px] md:text-[13px] leading-tight text-gray-800 fade-in';
                p.innerHTML = `<span class="font-bold mr-1">${data.user}</span> ${data.comment}`;
                container.appendChild(p);
                container.scrollTop = container.scrollHeight;

                const commentCountSpan = form.parentElement.previousElementSibling.previousElementSibling.querySelector('div.flex.items-center.text-gray-800 span');
                if(commentCountSpan) {
                    commentCountSpan.textContent = parseInt(commentCountSpan.textContent) + 1;
                }
            }
        } catch (error) { console.error("Error comment:", error); }
    }
</script>
@endsection