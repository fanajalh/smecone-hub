@extends('layouts.app')
@section('title', '| Event Smecone')

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .active-scale:active { transform: scale(0.95); transition: transform 0.1s; }
</style>

<div class="bg-[#fcfcfc] min-h-screen pb-24 font-sans text-gray-900">

    {{-- TOP HEADER --}}
    <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100 px-4 py-3 md:px-6 md:py-4 flex items-center justify-between shadow-[0_2px_15px_rgba(0,0,0,0.03)] mb-6">
        <a href="/dashboard" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors active-scale">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-base md:text-lg font-medium text-gray-900 tracking-tight">Event Mendatang</h1>
        <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
    </div>
    {{-- FEED LIST --}}
    <div class="max-w-xl mx-auto flex flex-col gap-8 md:gap-12 px-4 md:px-0">
        @forelse($events as $event)
        <div class="bg-transparent flex flex-col">
            
            {{-- IMAGE CONTAINER w/ OVERLAY --}}
            <div class="relative w-full aspect-[4/5] sm:aspect-square rounded-[2.5rem] overflow-hidden shadow-[0_4px_25px_rgba(0,0,0,0.05)] bg-gray-100">
                @if($event->gambar && count($event->gambar) > 0)
                    <div class="flex overflow-x-auto snap-x snap-mandatory h-full hide-scrollbar">
                        @foreach($event->gambar as $img)
                            <div class="min-w-full h-full snap-center">
                                <img src="{{ asset('storage/' . $img) }}" alt="Event" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    @if(count($event->gambar) > 1)
                        <div class="absolute bottom-6 right-6 bg-black/50 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full flex items-center gap-1.5 z-20 pointer-events-none">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                            1 / {{ count($event->gambar) }}
                        </div>
                    @endif
                @else
                    <div class="w-full h-full bg-gradient-to-br from-red-600 via-red-500 to-orange-500 flex flex-col items-center justify-center text-white p-6">
                        <span class="text-6xl mb-4 drop-shadow-md">📅</span>
                        <h2 class="text-2xl font-bold text-center px-4 leading-tight drop-shadow">{{ $event->judul }}</h2>
                    </div>
                @endif

                {{-- TOP OVERLAY: Author Info --}}
                <div class="absolute top-0 inset-x-0 p-5 bg-gradient-to-b from-black/60 via-black/20 to-transparent flex justify-between items-start pointer-events-none">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full border-2 border-transparent bg-white/10 backdrop-blur-sm p-0.5 overflow-hidden shrink-0 flex items-center justify-center text-white shadow-sm pointer-events-auto cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name=OSIS+Smecone&background=fff&color=ef4444" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div class="text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.4)] pointer-events-auto">
                            <h3 class="font-bold text-[15px] leading-tight flex items-center gap-1.5">
                                OSIS Smecone 
                                <svg class="w-3.5 h-3.5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.441A3 3 0 018.53 2h2.94a3 3 0 012.263 1.441l1.166 1.602a1 1 0 00.793.407h1.808A3 3 0 0120 8.45v5.1a3 3 0 01-2.5 2.96h-1.808a1 1 0 00-.793.407l-1.166 1.602A3 3 0 0111.47 20H8.53a3 3 0 01-2.263-1.441l-1.166-1.602a1 1 0 00-.793-.407H2.5A3 3 0 010 13.55v-5.1a3 3 0 012.5-2.96h1.808a1 1 0 00.793-.407L6.267 3.441zm9.444 4.853a1 1 0 00-1.422-1.414L9 11.586 7.711 10.297a1 1 0 00-1.422 1.414l2 2a1 1 0 001.422 0l6-6z" clip-rule="evenodd"></path></svg>
                            </h3>
                            <p class="text-[12px] font-medium text-white/90">Event & Agenda</p>
                        </div>
                    </div>
                    <button class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center active-scale pointer-events-auto transition hover:bg-white/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                    </button>
                </div>
                
                {{-- BOTTOM OVERLAY: Date Badge --}}
                <div class="absolute bottom-4 left-4 right-4 flex justify-start pointer-events-none">
                    <div class="bg-red-600/90 backdrop-blur-md text-white border border-red-500/50 px-3 py-1.5 rounded-full text-[11px] font-bold shadow-lg flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($event->tanggal_event)->translatedFormat('d M Y') }}
                    </div>
                </div>
            </div>

            {{-- ACTIONS BAR --}}
            <div class="flex items-center justify-between px-2 pt-4 pb-2 mt-1">
                <div class="flex items-center gap-5 text-gray-800">
                    @php $isLiked = $event->likes ? $event->likes->where('user_id', auth()->id())->isNotEmpty() : false; @endphp
                    <form action="{{ route('like.toggle') }}" method="POST" class="m-0" onsubmit="toggleLike(event, this)">
                        @csrf
                        <input type="hidden" name="type" value="App\Models\Event">
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        <button type="submit" class="flex items-center transition active-scale outline-none {{ $isLiked ? 'text-red-500' : 'hover:text-gray-500' }}">
                            <svg class="w-[28px] h-[28px] {{ $isLiked ? 'fill-current text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </form>
                    
                    <button class="flex items-center transition active-scale hover:text-gray-500 outline-none" onclick="focusComment('comment-input-event-{{$event->id}}')">
                        <svg class="w-[28px] h-[28px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </button>

                    <button class="flex items-center transition active-scale hover:text-gray-500 outline-none">
                        <svg class="w-[28px] h-[28px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="flex -space-x-2">
                        <div class="w-[22px] h-[22px] rounded-full bg-gray-200 border-2 border-[#fcfcfc] z-20 flex items-center justify-center overflow-hidden"><img src="https://ui-avatars.com/api/?name=E&background=random" class="w-full h-full object-cover"></div>
                        <div class="w-[22px] h-[22px] rounded-full bg-gray-300 border-2 border-[#fcfcfc] z-10 flex items-center justify-center overflow-hidden"><img src="https://ui-avatars.com/api/?name=V&background=random" class="w-full h-full object-cover"></div>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900"><span class="like-count">{{ $event->likes ? $event->likes->count() : 0 }}</span> Liked</span>
                </div>
            </div>

            {{-- CAPTION & INFO --}}
            <div class="px-2 mt-1">
                <div class="text-[12px] text-gray-400 font-medium mb-1.5">{{ \Carbon\Carbon::parse($event->created_at)->diffForHumans() }}</div>
                <p class="text-[14px] text-gray-800 leading-relaxed font-light">
                    @php
                        $likesText = "";
                        if($event->likes && $event->likes->count() > 0) {
                            $firstLiker = $event->likes->first()->user;
                            if ($firstLiker) {
                                $liker = $firstLiker->name;
                                $likesText = "<span class='font-medium text-gray-900'>@" . explode(' ', $liker)[0] . "</span> and others liked this post!<br>";
                            }
                        }
                    @endphp
                    {!! $likesText !!}
                    <span class="font-bold text-gray-900 mr-1">@osis.smecone</span>
                    Ayo ramaikan acara <span class="font-semibold text-red-500">{{ $event->judul }}</span>! Jangan sampai kelewatan ya! ✨
                    {{ \Illuminate\Support\Str::limit($event->deskripsi, 80) }}
                    @if(strlen($event->deskripsi) > 80)
                        <span class="text-gray-400 cursor-pointer">...more</span>
                    @endif
                </p>
                
                <!-- Additional Event Tags -->
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="text-[11px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full border border-gray-200">📌 {{ $event->kategori ?? 'UMUM' }}</span>
                    <span class="text-[11px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full border border-gray-200">📍 {{ $event->lokasi ?? 'Smecone' }}</span>
                </div>
            </div>

            {{-- COMMENTS SECTION --}}
            <div class="px-2 mt-2">
                @if($event->comments && $event->comments->count() > 0)
                    <div class="mb-3 space-y-2 comment-list">
                        @foreach($event->comments->take(3) as $comment)
                        <div class="flex gap-2 items-start mt-2">
                            <span class="font-medium text-[13px] text-gray-900 leading-snug">{{ $comment->user?->name ?? 'Pengguna' }}</span>
                            <p class="text-[13px] text-gray-700 leading-snug font-light">{{ $comment->body }}</p>
                        </div>
                        @endforeach
                        
                        <div class="hidden all-comments-{{ $event->id }}">
                            @foreach($event->comments->skip(3) as $comment)
                            <div class="flex gap-2 items-start mt-2">
                                <span class="font-medium text-[13px] text-gray-900 leading-snug">{{ $comment->user?->name ?? 'Pengguna' }}</span>
                                <p class="text-[13px] text-gray-700 leading-snug font-light">{{ $comment->body }}</p>
                            </div>
                            @endforeach
                        </div>

                        @if($event->comments->count() > 3)
                            <button type="button" onclick="document.querySelector('.all-comments-{{ $event->id }}').classList.toggle('hidden'); this.textContent = this.textContent.includes('Lihat') ? 'Sembunyikan komentar' : 'Lihat semua {{ $event->comments->count() }} komentar'" class="mt-2 text-[11px] bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium px-3 py-1.5 rounded-full transition-colors active-scale border border-gray-200">Lihat semua {{ $event->comments->count() }} komentar</button>
                        @endif
                    </div>
                @else
                    <div class="comment-list mt-1"></div>
                @endif
                
                {{-- Comment Input --}}
                <div class="flex items-center gap-3 mt-3">
                    <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 border border-gray-200">
                        @if(auth()->user() && auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=fff&color=ef4444" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <form action="{{ route('comment.store') }}" method="POST" class="flex-1 flex items-center bg-gray-100 rounded-full px-4 py-1.5 border border-gray-100 focus-within:border-gray-300 transition-colors" onsubmit="submitComment(event, this)">
                        @csrf
                        <input type="hidden" name="type" value="App\Models\Event">
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        <input type="text" name="body" id="comment-input-event-{{$event->id}}" placeholder="Tambahkan komentar..." class="bg-transparent border-none focus:ring-0 text-[13px] w-full py-1.5 px-0 outline-none text-gray-800 placeholder-gray-500 font-light" autocomplete="off" required>
                        <button type="submit" class="text-red-500 font-bold text-[13px] shrink-0 ml-2 active-scale hidden" id="submit-btn-event-{{$event->id}}">Kirim</button>
                        
                        <!-- Show submit button only when typed -->
                        <script>
                            document.getElementById('comment-input-event-{{$event->id}}').addEventListener('input', function() {
                                const btn = document.getElementById('submit-btn-event-{{$event->id}}');
                                if(this.value.trim() !== '') { btn.classList.remove('hidden'); } else { btn.classList.add('hidden'); }
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
        
        @if(!$loop->last)
            <hr class="border-gray-200/60 my-6 mx-2">
        @endif
        
        @empty
        <div class="text-center py-20 bg-white rounded-[2rem] border border-dashed border-gray-200 mx-4">
            <span class="text-5xl mb-4 block opacity-50">📭</span>
            <h3 class="text-[15px] font-bold text-gray-600">Belum Ada Postingan</h3>
        </div>
        @endforelse
    </div>
</div>

<script>
    const myAvatarUrl = "{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : '' }}";
    const myName = "{{ auth()->user() ? auth()->user()->name : 'User' }}";

    function focusComment(id) {
        document.getElementById(id).focus();
    }

    async function toggleLike(event, form) {
        event.preventDefault();
        const formData = new FormData(form);
        const button = form.querySelector('button');
        const icon = button.querySelector('svg');
        const countSpan = form.closest('.px-2').querySelector('.like-count');

        try {
            const response = await fetch(form.action, { 
                method: 'POST', 
                body: formData, 
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } 
            });
            const data = await response.json();
            
            if (data.success) {
                if(countSpan) countSpan.textContent = data.likeCount;
                if (data.isLiked) {
                    button.classList.add('text-red-500');
                    button.classList.remove('hover:text-gray-500');
                    icon.classList.add('fill-current', 'text-red-500');
                    
                    // Pop animation
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => icon.style.transform = 'scale(1)', 200);
                } else {
                    button.classList.remove('text-red-500');
                    button.classList.add('hover:text-gray-500');
                    icon.classList.remove('fill-current', 'text-red-500');
                }
            }
        } catch (error) { console.error("Error:", error); }
    }

    async function submitComment(event, form) {
        event.preventDefault();
        const formData = new FormData(form);
        const input = form.querySelector('input[name="body"]');
        const container = form.closest('.px-2').querySelector('.comment-list');
        const submitBtn = form.querySelector('button[type="submit"]');
        
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
                submitBtn.classList.add('hidden');
                
                const div = document.createElement('div');
                div.className = 'flex gap-2 items-start mt-2 fade-in';
                div.innerHTML = `
                    <span class="font-bold text-[13px] text-gray-900 leading-snug">${data.user}</span>
                    <p class="text-[13px] text-gray-700 leading-snug font-light">${data.comment}</p>
                `;
                container.appendChild(div);
            }
        } catch (error) { console.error("Error:", error); }
    }
</script>
@endsection