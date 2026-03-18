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
    
    <div class="mb-6 sm:rounded-xl overflow-hidden bg-white border sm:border border-gray-300 shadow-sm relative">
        
        <div class="h-32 md:h-44 bg-slate-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-800 to-purple-800"></div>
            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&q=80&w=2000" alt="Cover" class="w-full h-full object-cover opacity-20 mix-blend-overlay">
        </div>

        <div class="absolute top-16 md:top-24 left-4 md:left-6">
            <div class="w-28 h-28 md:w-36 md:h-36 bg-white rounded-full p-1 shadow-sm relative">
                <div class="w-full h-full bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center text-4xl md:text-5xl border-4 border-white shadow-inner">
                    📅
                </div>
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-600 border-2 border-white rounded-full"></div>
            </div>
        </div>

        <div class="pt-16 pb-6 px-4 md:px-6 relative">
            <div class="absolute top-4 right-4 hidden sm:flex gap-2">
                <button class="bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 hover:border-blue-700 font-semibold text-[14px] px-4 py-1.5 rounded-full transition">
                    Bagikan profil
                </button>
            </div>

            <div class="flex justify-between items-start mt-2">
                <div class="max-w-2xl">
                    <h1 class="text-2xl md:text-[26px] font-semibold text-gray-900 tracking-tight leading-tight flex items-center gap-1.5">
                        Agenda & Event Smecone
                        <svg class="w-5 h-5 text-yellow-600 mt-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 5.09L19.45 4.14L19.91 8.54L23.55 10.91L21.41 14.86L22.82 19L18.64 20.09L16.27 23.73L12.32 21.59L8.23 23.73L5.86 20.09L1.68 19L3.09 14.86L0.95 10.91L4.59 8.54L5.05 4.14L9.41 5.09L12 2ZM10.5 16L17.07 9.41L15.66 8L10.5 13.17L8.34 11L6.93 12.41L10.5 16Z"></path></svg>
                    </h1>
                    
                    <p class="text-[15px] md:text-base text-gray-900 mt-1 leading-snug">
                        Pusat Informasi Acara, Seminar, Lomba, dan Kegiatan Siswa | Jangan Sampai Kelewatan!
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[13px] md:text-[14px] text-gray-500 mt-1.5">
                        <span>Purwokerto, Jawa Tengah, Indonesia</span>
                        <span class="hidden sm:inline font-bold">·</span>
                        <a href="#" class="text-blue-600 font-semibold hover:underline hover:text-blue-800 transition">
                            {{ $events->count() }} event tersedia
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-6 sm:gap-8">
        @forelse($events as $event)
        <div class="bg-white sm:rounded-2xl shadow-sm border-y sm:border border-gray-200 overflow-hidden">
            
            <div class="p-3 md:p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-pink-500 to-purple-600 flex items-center justify-center text-white font-black border-2 border-white shadow-sm shrink-0">O</div>
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
                    <span class="text-6xl md:text-7xl mb-2 drop-shadow-lg">📅</span>
                    <h2 class="text-lg md:text-xl font-black px-6 text-center drop-shadow-md">{{ $event->judul }}</h2>
                </div>
            @endif

            <div class="px-4 pt-3 pb-2 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @php $isLiked = $event->likes ? $event->likes->where('user_id', auth()->id())->isNotEmpty() : false; @endphp
                    <form action="{{ route('like.toggle') }}" method="POST" class="m-0" onsubmit="toggleLike(event, this)">
                        @csrf
                        <input type="hidden" name="type" value="App\Models\Event">
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        <button type="submit" class="flex items-center gap-1.5 transition active:scale-90 group {{ $isLiked ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center group-hover:bg-pink-50 transition">
                                <svg class="w-5 h-5 md:w-6 md:h-6 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <span class="text-[13px] font-medium">{{ $event->likes ? $event->likes->count() : 0 }}</span>
                        </button>
                    </form>
                    
                    <div class="flex items-center gap-1.5 text-gray-500 group cursor-pointer">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-500 transition">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <span class="text-[13px] font-medium comment-count">{{ $event->comments ? $event->comments->count() : 0 }}</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-1.5 text-pink-600 bg-pink-50 px-3 py-1 rounded-full border border-pink-100 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs">{{ \Carbon\Carbon::parse($event->tanggal_event)->translatedFormat('d M') }}</span>
                </div>
            </div>

            <div class="px-4 py-3">
                <p class="text-[14px] text-gray-900 leading-snug">
                    <span class="font-bold mr-1">OSIS Smecone</span>
                    Ayo ramaikan acara <span class="font-bold">{{ $event->judul }}</span>! Jangan sampai kelewatan ya! ✨
                </p>
                <p class="text-[14px] text-gray-700 mt-1">{{ $event->deskripsi }}</p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="text-[10px] md:text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">📌 {{ $event->kategori ?? 'UMUM' }}</span>
                    <span class="text-[10px] md:text-xs font-bold text-gray-600 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-200">📍 {{ $event->lokasi ?? 'Smecone' }}</span>
                </div>
            </div>

            <div class="px-4 pb-4">
                <hr class="border-gray-100 mb-4">
                
                <div class="max-h-60 overflow-y-auto space-y-4 pr-2 hide-scrollbar comment-list">
                    @if($event->comments && $event->comments->count() > 0)
                        @foreach($event->comments as $comment)
                        <div class="flex gap-3 group/comment">
                            @if($comment->user->avatar)
                                <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shrink-0">
                            @else
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-[11px] font-bold text-gray-600 shrink-0">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="flex-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-[14px] text-gray-900">{{ $comment->user->name }}</span>
                                    <span class="text-[12px] text-gray-500">· {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-[14px] text-gray-800 leading-snug mt-0.5">{{ $comment->body }}</p>
                                
                                <div class="mt-1 flex gap-3 opacity-0 group-hover/comment:opacity-100 transition-opacity">
                                    <button type="button" onclick="replyTo('{{ $comment->user->name }}', this)" class="text-[11px] font-bold text-gray-400 hover:text-blue-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                        Balas
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="flex gap-3 mt-4 pt-4 border-t border-gray-100 items-start">
                    
                    @if(auth()->user() && auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="My Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shrink-0">
                    @else
                        <div class="w-8 h-8 bg-blue-50 border border-blue-100 rounded-full flex items-center justify-center text-[11px] font-bold text-blue-600 shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif

                    <form action="{{ route('comment.store') }}" method="POST" class="flex-1 flex flex-col" onsubmit="submitComment(event, this)">
                        @csrf
                        <input type="hidden" name="type" value="App\Models\Event">
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        <textarea name="body" rows="1" required placeholder="Post your reply..." class="w-full text-[14px] bg-transparent border-none focus:ring-0 px-0 placeholder-gray-500 outline-none resize-none hide-scrollbar" style="min-height: 24px;"></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold text-[13px] px-5 py-1.5 rounded-full transition active:scale-95">Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-10 text-center mx-4 sm:mx-0 rounded-2xl">
            <h3 class="text-lg font-bold text-gray-700">Belum Ada Postingan</h3>
        </div>
        @endforelse
    </div>
</div>

<script>
    // Variabel bawaan untuk load foto avatar instan via JS AJAX
    const myAvatarUrl = "{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : '' }}";

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
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } 
            });
            const data = await response.json();
            
            if (data.success) {
                countSpan.textContent = data.likeCount;
                if (data.isLiked) {
                    button.classList.add('text-pink-600');
                    button.classList.remove('text-gray-500');
                    icon.classList.add('fill-current');
                } else {
                    button.classList.remove('text-pink-600');
                    button.classList.add('text-gray-500', 'hover:text-pink-600');
                    icon.classList.remove('fill-current');
                }
            }
        } catch (error) { console.error("Error:", error); }
    }

    function replyTo(username, btn) {
        const wrapper = btn.closest('.px-4'); 
        const textarea = wrapper.querySelector('textarea[name="body"]');
        if (textarea) {
            textarea.value = `@${username} `; 
            textarea.focus(); 
        }
    }

    async function submitComment(event, form) {
        event.preventDefault();
        const formData = new FormData(form);
        const input = form.querySelector('textarea[name="body"]');
        const container = form.closest('.px-4').querySelector('.comment-list');
        
        if(input.value.trim() === '') return;

        try {
            const response = await fetch(form.action, { 
                method: 'POST', 
                body: formData, 
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } 
            });
            const data = await response.json();
            
            if (data.success) {
                input.value = '';
                const initial = data.user.charAt(0).toUpperCase();
                
                // Cek pakai foto profil atau inisial
                const avatarHtml = myAvatarUrl 
                    ? `<img src="${myAvatarUrl}" class="w-8 h-8 rounded-full object-cover border border-gray-200 shrink-0">`
                    : `<div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-[11px] font-bold text-gray-600 shrink-0">${initial}</div>`;
                
                const div = document.createElement('div');
                div.className = 'flex gap-3 fade-in mt-4 group/comment';
                div.innerHTML = `
                    ${avatarHtml}
                    <div class="flex-1">
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-[14px] text-gray-900">${data.user}</span>
                            <span class="text-[12px] text-gray-500">· Baru saja</span>
                        </div>
                        <p class="text-[14px] text-gray-800 leading-snug mt-0.5">${data.comment}</p>
                        
                        <div class="mt-1 flex gap-3 opacity-0 group-hover/comment:opacity-100 transition-opacity">
                            <button type="button" onclick="replyTo('${data.user}', this)" class="text-[11px] font-bold text-gray-400 hover:text-blue-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                Balas
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(div);
                container.scrollTop = container.scrollHeight;

                const commentCountSpan = form.closest('.bg-white').querySelector('.comment-count');
                if(commentCountSpan) {
                    commentCountSpan.textContent = parseInt(commentCountSpan.textContent) + 1;
                }
            }
        } catch (error) { console.error("Error:", error); }
    }
</script>
@endsection