@extends('layouts.app')
@section('title', '| Beranda')

@section('content')
<style>
    /* Menyembunyikan scrollbar tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    @keyframes waving-hand {
        0% { transform: rotate(0.0deg) }
        10% { transform: rotate(14.0deg) }
        20% { transform: rotate(-8.0deg) }
        30% { transform: rotate(14.0deg) }
        40% { transform: rotate(-4.0deg) }
        50% { transform: rotate(10.0deg) }
        60% { transform: rotate(0.0deg) }
        100% { transform: rotate(0.0deg) }
    }
    .animate-waving-hand {
        animation: waving-hand 2.5s infinite;
    }
</style>

{{-- Premium Header Background with Gradient and Decorative Elements --}}
<div class="relative bg-gradient-to-br from-[#E21F26] via-[#D31920] to-[#B31217] w-full pt-16 pb-28 lg:pt-44 lg:pb-36 rounded-b-[2.5rem] md:rounded-b-[3.5rem] overflow-hidden shadow-lg shadow-red-900/20">
    <!-- Premium Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-40 h-40 bg-black/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-5 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-6 z-10">
        
        {{-- Profile Section --}}
        <div class="flex items-center gap-5 text-white">
            <div class="relative w-20 h-20 md:w-24 md:h-24 rounded-[1.8rem] border-[3px] border-white/30 shadow-2xl overflow-hidden bg-white shrink-0 transition-all duration-500 hover:scale-105 hover:rotate-2 hover:border-white/60 hover:shadow-red-900/40">
                @if($user && $user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=fff&color=E21F26&bold=true" alt="Avatar" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <p class="text-[13px] md:text-sm font-semibold text-red-100 tracking-wider mb-1 opacity-90 uppercase">Selamat Datang,</p>
                <h1 class="text-3xl md:text-4xl font-black flex items-center gap-3 drop-shadow-md">
                    {{ explode(' ', $user->name ?? 'Warga')[0] }} <span class="animate-waving-hand inline-block origin-bottom-right">👋</span>
                </h1>
            </div>
        </div>

        {{-- Points & Stats (Glassmorphism) --}}
        <div class="flex items-center gap-3 md:gap-4">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-3 md:px-6 md:py-4 text-white border border-white/20 shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:bg-white/15 transition duration-300">
                <p class="text-[10px] md:text-xs text-red-100 uppercase tracking-widest font-bold mb-1 opacity-80">Peran Kamu</p>
                <p class="text-sm md:text-base font-black uppercase leading-tight mt-1 flex items-center gap-1.5">
                    <ion-icon name="shield-checkmark" class="text-yellow-400 text-lg drop-shadow-sm"></ion-icon>
                    @if(auth()->user()->is_admin) Admin @elseif(auth()->user()->is_teacher) Guru @else Siswa @endif
                </p>
            </div>
            <button onclick="window.playLogoutAnimation(() => { document.getElementById('logout-form').submit(); })" class="group bg-black/20 backdrop-blur-md rounded-2xl px-5 py-3 md:px-6 md:py-4 text-white border border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:bg-black/30 hover:border-red-400/50 transition-all duration-300 text-left outline-none cursor-pointer tap-effect">
                <p class="text-[10px] md:text-xs text-red-100 uppercase tracking-widest font-bold mb-1 opacity-80 group-hover:text-red-200">Aksi Sistem</p>
                <p class="text-sm md:text-base font-black uppercase leading-tight mt-1 flex items-center gap-1.5 group-hover:text-red-400 transition-colors">
                    <ion-icon name="log-out" class="text-lg"></ion-icon>
                    Keluar
                </p>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                 @csrf
            </form>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="max-w-6xl mx-auto px-4 lg:px-8 -mt-16 relative z-20 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        {{-- Left Column --}}
        <div class="lg:col-span-8 flex flex-col gap-8">
            
            {{-- Navigation Menu (Modern Grid Cards) --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] p-5 md:p-7 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/60 to-transparent pointer-events-none"></div>
                <div class="relative grid grid-cols-4 gap-3 md:gap-5 z-10">
                    <a href="/marketplace" class="group flex flex-col items-center gap-3 p-3 rounded-[1.5rem] hover:bg-red-50/80 hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.2rem] md:rounded-[1.4rem] bg-gradient-to-br from-red-100 to-red-50 text-[#E21F26] flex items-center justify-center shadow-inner group-hover:shadow-md transition-all">
                            <ion-icon name="storefront" class="text-3xl md:text-4xl drop-shadow-sm"></ion-icon>
                        </div>
                        <span class="text-[12px] md:text-sm font-extrabold text-gray-700 group-hover:text-[#E21F26] transition-colors">Jajan</span>
                    </a>
                    
                    <a href="/forum" class="group flex flex-col items-center gap-3 p-3 rounded-[1.5rem] hover:bg-orange-50/80 hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.2rem] md:rounded-[1.4rem] bg-gradient-to-br from-orange-100 to-orange-50 text-orange-600 flex items-center justify-center shadow-inner group-hover:shadow-md transition-all">
                            <ion-icon name="chatbubbles" class="text-3xl md:text-4xl drop-shadow-sm"></ion-icon>
                        </div>
                        <span class="text-[12px] md:text-sm font-extrabold text-gray-700 group-hover:text-orange-600 transition-colors">Forum</span>
                    </a>
                    
                    <a href="/repository" class="group flex flex-col items-center gap-3 p-3 rounded-[1.5rem] hover:bg-indigo-50/80 hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.2rem] md:rounded-[1.4rem] bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner group-hover:shadow-md transition-all">
                            <ion-icon name="cloud-upload" class="text-3xl md:text-4xl drop-shadow-sm"></ion-icon>
                        </div>
                        <span class="text-[12px] md:text-sm font-extrabold text-gray-700 group-hover:text-indigo-600 transition-colors">Repo</span>
                    </a>
                    
                    <button onclick="showAllShortcuts()" class="group flex flex-col items-center gap-3 p-3 rounded-[1.5rem] hover:bg-gray-100/80 hover:scale-105 transition-all duration-300 outline-none tap-effect">
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.2rem] md:rounded-[1.4rem] bg-gradient-to-br from-gray-200 to-gray-100 text-gray-600 flex items-center justify-center shadow-inner group-hover:shadow-md transition-all">
                            <ion-icon name="grid" class="text-3xl md:text-4xl drop-shadow-sm"></ion-icon>
                        </div>
                        <span class="text-[12px] md:text-sm font-extrabold text-gray-700 group-hover:text-gray-900 transition-colors">Lainnya</span>
                    </button>
                </div>
            </div>

            {{-- Horizontal Highlight Section --}}
            <div class="mt-2">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Update Sekolah</h2>
                </div>
                <div class="flex gap-4 overflow-x-auto hide-scrollbar snap-x pb-4">
                    @if($latestEvent)
                    <a href="/event" class="group min-w-[85%] md:min-w-[360px] h-44 rounded-[2rem] overflow-hidden relative snap-center shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        @if($latestEvent->gambar && count($latestEvent->gambar) > 0)
                            <img src="{{ asset('storage/' . $latestEvent->gambar[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent"></div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900"></div>
                        @endif
                        <div class="absolute bottom-5 left-5 right-5 text-white">
                            <span class="bg-[#E21F26] text-[10px] font-black px-2.5 py-1.5 rounded-lg uppercase tracking-widest mb-3 inline-block shadow-lg">Mendatang</span>
                            <h3 class="font-bold text-xl leading-snug line-clamp-2 text-white">{{ $latestEvent->judul }}</h3>
                        </div>
                    </a>
                    @endif

                    @if($latestPrestasi)
                    <a href="/prestasi" class="group min-w-[85%] md:min-w-[360px] h-44 rounded-[2rem] bg-gradient-to-br from-amber-400 to-amber-600 overflow-hidden relative snap-center shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mt-8 -mr-8 blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
                        <div class="absolute bottom-5 left-5 right-5 text-white z-10">
                            <span class="bg-white/30 backdrop-blur-md text-[10px] font-black px-2.5 py-1.5 rounded-lg uppercase tracking-widest mb-3 inline-block shadow-lg">Prestasi</span>
                            <h3 class="font-bold text-xl leading-snug line-clamp-2 text-white drop-shadow-md">{{ $latestPrestasi->judul }}</h3>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Marketplace Grid --}}
            <div>
                <div class="flex justify-between items-end mb-4 px-2">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Rekomendasi Jajan</h2>
                    </div>
                    <a href="/marketplace" class="text-sm font-bold text-[#E21F26] hover:text-red-700 transition flex items-center gap-1 group">
                        Semua
                        <ion-icon name="arrow-forward" class="transition-transform group-hover:translate-x-1"></ion-icon>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
                    @forelse($recentMarketplace as $item)
                    <a href="/marketplace/{{ $item->id }}" class="group bg-white rounded-[1.5rem] p-3 md:p-4 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 hover:shadow-2xl hover:border-red-100 transition-all duration-300 flex flex-col transform hover:-translate-y-1.5">
                        <div class="aspect-square bg-gray-50 rounded-2xl mb-4 overflow-hidden relative">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 group-hover:bg-gray-100 transition-colors">
                                    <ion-icon name="image-outline" class="text-4xl"></ion-icon>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-bold py-1.5 px-3 rounded-full shadow-lg">Lihat Produk</span>
                            </div>
                        </div>
                        <h3 class="text-[14px] md:text-base font-extrabold text-gray-800 line-clamp-1 group-hover:text-[#E21F26] transition-colors">{{ $item->item_name }}</h3>
                        <div class="flex items-center justify-between mt-1 mb-3">
                            <p class="text-[#E21F26] font-black text-sm md:text-base tracking-tight">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-extrabold text-gray-600 ring-2 ring-white shadow-sm overflow-hidden">
                                @if($item->user->avatar)
                                    <img src="{{ asset('storage/' . $item->user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($item->user->name, 0, 1) }}
                                @endif
                            </div>
                            <p class="text-[11px] md:text-xs text-gray-500 font-bold truncate">{{ explode(' ', $item->user->name)[0] }}</p>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full bg-gradient-to-b from-gray-50 to-white rounded-[2rem] p-12 text-center border border-dashed border-gray-200">
                        <div class="w-20 h-20 bg-white shadow-sm border border-gray-100 rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 text-gray-300 transform -rotate-6 transition-transform hover:rotate-0">
                            <ion-icon name="basket-outline" class="text-4xl"></ion-icon>
                        </div>
                        <p class="text-base font-extrabold text-gray-700 tracking-tight">Belum ada jajan hari ini</p>
                        <p class="text-[13px] text-gray-500 mt-1 font-medium">Coba cek lagi nanti atau jadilah yang pertama berjualan!</p>
                        <a href="/marketplace/create" class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-xl text-xs font-bold mt-4 hover:bg-red-100 transition tap-effect">
                            <ion-icon name="add-circle"></ion-icon> Buka Lapak
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-4 flex flex-col gap-6 lg:gap-8 mt-4 lg:mt-0">
            
            {{-- Channel Saya --}}
            <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-extrabold text-gray-900 tracking-tight">Channel Saya</h2>
                    <a href="/dashboard/channel/create" class="flex items-center gap-1 bg-red-50 text-[#E21F26] px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-[#E21F26] hover:text-white transition-all shadow-sm">
                        <ion-icon name="add"></ion-icon> Baru
                    </a>
                </div>
                <div class="flex flex-col gap-2">
                    @forelse($myChannels as $channel)
                    <div class="group flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100 hover:shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-[1rem] bg-red-50 text-[#E21F26] flex items-center justify-center font-black text-lg group-hover:bg-[#E21F26] group-hover:text-white transition-colors shadow-inner">
                                {{ substr($channel->title, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 line-clamp-1 group-hover:text-[#E21F26] transition-colors">{{ $channel->title }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5 font-medium flex items-center gap-1">
                                    <ion-icon name="chatbox-ellipses" class="text-gray-400"></ion-icon>
                                    {{ $channel->replies_count }} Pesan
                                </p>
                            </div>
                        </div>
                        <a href="/dashboard/channel/{{ $channel->id }}/manage" class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 group-hover:text-white group-hover:bg-[#E21F26] group-hover:border-[#E21F26] group-hover:shadow-md transition-all tap-effect">
                            <ion-icon name="settings"></ion-icon>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-8 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <div class="w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <ion-icon name="chatbubble-ellipses-outline" class="text-2xl"></ion-icon>
                        </div>
                        <p class="text-sm font-bold text-gray-600">Belum ada channel</p>
                        <p class="text-xs text-gray-400 mt-0.5">Buat channel pertamamu sekarang.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Repo Populer --}}
            <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-extrabold text-gray-900 tracking-tight">Repo Populer</h2>
                    <a href="/repository" class="text-sm font-bold text-[#E21F26] hover:text-red-700 hover:underline">Semua</a>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse($popularRepos as $repo)
                    <a href="/repository/{{ $repo->id }}" class="group block p-4 rounded-[1.5rem] bg-gray-50/50 border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/50 transition-all duration-300 hover:shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-bold text-gray-800 line-clamp-1 group-hover:text-indigo-700 transition-colors flex items-center gap-2">
                                <ion-icon name="folder-open" class="text-gray-400 group-hover:text-indigo-500 text-lg"></ion-icon>
                                {{ $repo->name }}
                            </h3>
                            <div class="flex items-center gap-1 bg-yellow-100/80 px-2 py-0.5 rounded-lg text-amber-600 text-xs font-black shadow-sm">
                                <ion-icon name="star"></ion-icon>
                                {{ $repo->stars_count }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                            <div class="w-5 h-5 rounded-full bg-gray-200 overflow-hidden ring-1 ring-gray-100">
                                @if($repo->user->avatar)
                                    <img src="{{ asset('storage/' . $repo->user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($repo->user->name) }}&background=e2e8f0&color=475569" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <span class="truncate">{{ $repo->user->name }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6 border border-dashed border-gray-200 rounded-2xl">
                        <p class="text-sm font-semibold text-gray-500">Belum ada repo populer.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showAllShortcuts() {
        Swal.fire({
            html: `
                <div class="text-left mb-4 mt-2 border-b border-gray-100 pb-3">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-tight">Navigasi Penuh</h2>
                    <p class="text-xs font-semibold text-gray-500 mt-0.5">Semua fitur Smecone Hub & Riwayat Aktivitasmu</p>
                </div>
                
                <h3 class="text-left text-[11px] font-black tracking-widest text-[#E21F26] uppercase mb-3">Menu Utama</h3>
                <div class="grid grid-cols-4 gap-2 mb-5">
                    <a href="/prestasi" class="group flex flex-col items-center gap-2 p-2 hover:bg-red-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-50 rounded-[1rem] flex items-center justify-center text-[#E21F26] group-hover:scale-110 transition-transform shadow-sm">
                            <ion-icon name="trophy" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight group-hover:text-[#E21F26]">Prestasi</span>
                    </a>
                    <a href="/event" class="group flex flex-col items-center gap-2 p-2 hover:bg-orange-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-50 rounded-[1rem] flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform shadow-sm">
                            <ion-icon name="calendar" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight group-hover:text-orange-600">Event</span>
                    </a>
                    <a href="/profile" class="group flex flex-col items-center gap-2 p-2 hover:bg-blue-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-[1rem] flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform shadow-sm">
                            <ion-icon name="person" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight group-hover:text-blue-600">Profil</span>
                    </a>
                    <a href="/keranjang" class="group flex flex-col items-center gap-2 p-2 hover:bg-emerald-50 rounded-2xl transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-[1rem] flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform shadow-sm">
                            <ion-icon name="cart" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 text-center leading-tight group-hover:text-emerald-600">Keranjang</span>
                    </a>
                </div>

                <h3 class="text-left text-[11px] font-black tracking-widest text-[#E21F26] uppercase mb-3">Aktivitas & Riwayat</h3>
                <div class="grid grid-cols-4 gap-2">
                    <a href="/marketplace/purchases" class="group flex flex-col items-center gap-2 p-2 hover:bg-emerald-50 rounded-2xl transition-all text-center">
                        <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-[1rem] flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                            <ion-icon name="bag-check-outline" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 leading-tight group-hover:text-emerald-700">Terbeli</span>
                    </a>
                    <a href="/marketplace/penjualan" class="group flex flex-col items-center gap-2 p-2 hover:bg-red-50 rounded-2xl transition-all text-center">
                        <div class="w-12 h-12 bg-red-50 border border-red-100 rounded-[1rem] flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                            <ion-icon name="wallet-outline" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 leading-tight group-hover:text-red-700">Terjual</span>
                    </a>
                    <a href="/marketplace/lapak-saya" class="group flex flex-col items-center gap-2 p-2 hover:bg-orange-50 rounded-2xl transition-all text-center">
                        <div class="w-12 h-12 bg-orange-50 border border-orange-100 rounded-[1rem] flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                            <ion-icon name="storefront-outline" class="text-2xl"></ion-icon>
                        </div>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-700 leading-tight group-hover:text-orange-700">Lapakku</span>
                    </a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-[2rem] p-4 shadow-2xl border border-gray-100',
                closeButton: 'focus:outline-none hover:text-red-500 transition text-gray-400 mt-2 mr-2'
            },
            width: '420px'
        });
    }
</script>
@endsection
