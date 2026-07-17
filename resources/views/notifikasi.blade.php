@extends('layouts.app')
@section('title', '| Notifikasi')

@section('content')
<style>
    /* Efek tekan pada tombol/card */
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s; }
</style>

<div class="max-w-2xl mx-auto px-4 py-6 md:py-10 animate-page-in">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#E21F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notifikasi
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-0.5">Pantau seluruh log aktivitas akunmu di sini.</p>
        </div>

        {{-- Actions --}}
        @if(count($notifications) > 0)
            <div class="flex items-center gap-2">
                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-[11px] font-extrabold text-gray-500 hover:text-[#E21F26] bg-white border border-gray-100 hover:border-red-100 px-3.5 py-2 rounded-xl transition-all tap-effect shadow-sm">
                        Tandai Semua Dibaca
                    </button>
                </form>
                <form action="{{ route('notifications.clearAll') }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[11px] font-extrabold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3.5 py-2 rounded-xl transition-all tap-effect">
                        Hapus Semua
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs p-3.5 rounded-xl mb-4 font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Notification List --}}
    @forelse($notifications as $date => $group)
        <div class="mb-6">
            {{-- Date Divider --}}
            <h3 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3 pl-1">{{ $date }}</h3>
            
            <div class="space-y-3">
                @foreach($group as $notif)
                    @php
                        // Deteksi background/ikon berdasarkan tipe
                        $bgIcon = 'bg-gray-100 text-gray-600';
                        $iconSvg = '';
                        
                        if ($notif->type === 'login') {
                            $bgIcon = 'bg-sky-50 text-sky-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>';
                        } elseif ($notif->type === 'logout') {
                            $bgIcon = 'bg-gray-50 text-gray-500';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>';
                        } elseif ($notif->type === 'register') {
                            $bgIcon = 'bg-purple-50 text-purple-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>';
                        } elseif ($notif->type === 'purchase') {
                            $bgIcon = 'bg-emerald-50 text-emerald-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                        } elseif ($notif->type === 'payment_success') {
                            $bgIcon = 'bg-emerald-50 text-emerald-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>';
                        } elseif ($notif->type === 'payment_failed') {
                            $bgIcon = 'bg-red-50 text-red-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                        } elseif ($notif->type === 'item_sold') {
                            $bgIcon = 'bg-amber-50 text-amber-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                        } elseif ($notif->type === 'review_received') {
                            $bgIcon = 'bg-yellow-50 text-yellow-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>';
                        } elseif ($notif->type === 'review_given') {
                            $bgIcon = 'bg-indigo-50 text-indigo-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
                        } elseif ($notif->type === 'withdrawal') {
                            $bgIcon = 'bg-teal-50 text-teal-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>';
                        } elseif ($notif->type === 'product_listed') {
                            $bgIcon = 'bg-rose-50 text-rose-600';
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>';
                        } else {
                            $iconSvg = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>';
                        }
                    @endphp

                    <div class="relative bg-white border border-gray-100/80 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-gray-200 transition-all flex items-start gap-3.5 group {{ is_null($notif->read_at) ? 'ring-2 ring-red-500/10 border-red-100 bg-red-50/5' : '' }}">
                        {{-- Unread Dot --}}
                        @if(is_null($notif->read_at))
                            <span class="absolute top-4 right-4 w-2 h-2 bg-[#E21F26] rounded-full"></span>
                        @endif

                        {{-- Icon Circle --}}
                        <div class="w-10 h-10 rounded-xl {{ $bgIcon }} flex items-center justify-center shrink-0 shadow-inner">
                            {!! $iconSvg !!}
                        </div>

                        {{-- Content --}}
                        <div class="flex-grow min-w-0 pr-4">
                            <h4 class="text-[13px] font-extrabold text-gray-900 leading-tight">{{ $notif->title }}</h4>
                            <p class="text-[12px] text-gray-600 leading-relaxed font-medium mt-1">{{ $notif->message }}</p>
                            <span class="block text-[9px] text-gray-400 font-semibold mt-1.5">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Delete Button --}}
                        <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" class="m-0 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition-colors tap-effect" title="Hapus Notifikasi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center text-center py-20 bg-white rounded-3xl border border-dashed border-gray-200 p-8 shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-5 ring-8 ring-white shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <h3 class="text-gray-900 font-extrabold mb-1.5 text-lg">Belum Ada Notifikasi</h3>
            <p class="text-xs text-gray-500 font-semibold max-w-[280px]">Setiap aktivitas penting akun Anda akan tercatat secara rapi di halaman ini.</p>
        </div>
    @endforelse
</div>
@endsection
