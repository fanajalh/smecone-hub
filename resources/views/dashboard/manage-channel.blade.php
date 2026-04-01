@extends('layouts.app')
@section('title', '| Kelola Channel')

@section('content')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .tap-effect:active { transform: scale(0.98); transition: transform 0.1s; }
</style>

<div class="max-w-5xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-24 animate-page-in">
    
    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="/dashboard" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-[0_2px_10px_rgba(0,0,0,0.03)] border border-gray-100 text-gray-500 hover:bg-red-50 hover:text-[#E21F26] hover:border-red-100 transition-all tap-effect shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Kelola Channel</h1>
            <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">Sesuaikan informasi dan atur anggota channel diskusi.</p>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-8 shadow-sm flex items-center gap-3 animate-fade-in-up">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        
        {{-- BAGIAN KIRI: INFO & DANGER ZONE (5 Kolom) --}}
        <div class="lg:col-span-5 flex flex-col gap-6">
            
            {{-- Card Info Dasar --}}
            <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h2 class="text-lg font-extrabold text-gray-900">Informasi Dasar</h2>
                </div>
                
                <form action="/dashboard/channel/{{ $channel->id }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-2">Nama Channel</label>
                        <input type="text" name="title" value="{{ $channel->title }}" required 
                               class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-400 font-bold text-gray-900 transition-all placeholder-gray-400" placeholder="Misal: Diskusi Kelas XII">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-2">Topik / Deskripsi</label>
                        <textarea name="content" rows="4" required 
                                  class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-400 font-medium text-gray-700 text-sm transition-all resize-none" placeholder="Tuliskan tujuan channel ini dibuat...">{{ $channel->content }}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 text-white font-extrabold py-3.5 rounded-xl hover:bg-black shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-lg hover:-translate-y-0.5 transition-all tap-effect mt-2">
                        Simpan Perubahan
                    </button>
                </form>

                @if($channel->is_private && $channel->invite_code)
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Link Undangan Privat</h3>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ url('/forum/invite/' . $channel->invite_code) }}" class="w-full bg-gray-50 border border-gray-200 text-xs text-gray-500 font-medium px-3 py-2 rounded-lg truncate cursor-text" id="inviteLinkInput">
                        <button onclick="copyInviteLink()" class="bg-red-50 text-red-600 p-2 rounded-lg hover:bg-red-600 hover:text-white transition-colors border border-transparent hover:border-red-600" title="Copy Link">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>
                </div>
                <script>
                    function copyInviteLink() {
                        var copyText = document.getElementById("inviteLinkInput");
                        copyText.select();
                        copyText.setSelectionRange(0, 99999); 
                        navigator.clipboard.writeText(copyText.value);
                        alert("Link undangan berhasil disalin!");
                    }
                </script>
                @endif
    
                {{-- Tombol Export Rekap Nilai Keseluruhan --}}
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <a href="/dashboard/channel/{{ $channel->id }}/export-grades" class="w-full bg-emerald-50 text-emerald-700 font-extrabold py-3.5 rounded-xl border border-emerald-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 shadow-sm transition-all tap-effect flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export Rekap Semua Nilai (CSV)
                    </a>
                </div>
            </div>

            {{-- Card Danger Zone --}}
            <div class="bg-red-50/50 rounded-[2rem] border border-red-100 p-6 md:p-8 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 text-red-100">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="relative z-10">
                    <h2 class="text-sm font-black text-red-600 mb-2 uppercase tracking-widest flex items-center gap-2">
                        Zona Berbahaya
                    </h2>
                    <p class="text-xs text-red-500/80 font-medium mb-5">Tindakan ini tidak bisa dibatalkan. Semua pesan di channel ini akan terhapus.</p>
                    
                    <form action="/dashboard/channel/{{ $channel->id }}" method="POST" onsubmit="return confirm('Apakah kamu YAKIN ingin menghapus channel ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-white text-red-600 font-extrabold py-3 rounded-xl border border-red-200 hover:bg-red-600 hover:text-white hover:border-red-600 shadow-sm transition-all tap-effect flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus Channel
                        </button>
                    </form>
                </div>
            </div>
            
        </div>

        {{-- BAGIAN KANAN: MEMBER LIST (7 Kolom) --}}
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 p-6 md:p-8 flex flex-col h-full min-h-[500px]">
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h2 class="text-lg font-extrabold text-gray-900">Anggota Channel</h2>
                    </div>
                    <span class="bg-gray-100 text-gray-600 text-xs font-black px-3 py-1.5 rounded-lg border border-gray-200">
                        {{ count($channel->members) }} Orang
                    </span>
                </div>

                @if($pendingRequests->count() > 0)
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                    <h3 class="text-sm font-black text-yellow-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Persetujuan Gabung ({{ $pendingRequests->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach($pendingRequests as $req)
                        <div class="flex items-center justify-between bg-white px-4 py-2.5 rounded-xl border border-yellow-100 shadow-sm">
                            <div class="text-sm font-bold text-gray-800">{{ $req->user->name }} <span class="text-xs font-medium text-gray-500">({{ $req->user->nis ?? '-' }})</span></div>
                            <div class="flex gap-2">
                                <form action="/dashboard/channel/{{ $channel->id }}/request/{{ $req->id }}/approve" method="POST">
                                    @csrf 
                                    <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-600 hover:text-white border border-green-200 hover:border-green-600 px-3 py-1.5 rounded-lg text-[11px] font-bold transition">Terima</button>
                                </form>
                                <form action="/dashboard/channel/{{ $channel->id }}/request/{{ $req->id }}/reject" method="POST">
                                    @csrf 
                                    <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-600 hover:text-white border border-red-200 hover:border-red-600 px-3 py-1.5 rounded-lg text-[11px] font-bold transition">Tolak</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- Form Tambah Anggota --}}
                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 mb-6">
                    <form action="/dashboard/channel/{{ $channel->id }}/members" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <select name="user_id" required class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-[13px] font-medium text-gray-700 focus:ring-4 focus:ring-red-500/10 focus:border-red-400 appearance-none shadow-sm cursor-pointer">
                                <option value="" disabled selected>Pilih siswa untuk ditambahkan...</option>
                                @foreach($allUsers as $u)
                                    {{-- Cek jika user sudah ada di dalam channel --}}
                                    @if(!$channel->members->contains('id', $u->id))
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->nis }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-[#E21F26] text-white px-6 py-3 rounded-xl font-extrabold text-[13px] hover:bg-red-700 shadow-[0_4px_12px_rgba(226,31,38,0.2)] hover:shadow-lg transition-all tap-effect shrink-0">
                            Tambah Anggota
                        </button>
                    </form>
                </div>

                {{-- List Anggota --}}
                <div class="flex-1 overflow-y-auto space-y-3 pr-2 hide-scrollbar">
                    @forelse($channel->members as $member)
                    <div class="group flex items-center justify-between bg-white p-3.5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:border-red-100 transition-all">
                        <div class="flex items-center gap-3.5">
                            {{-- Avatar Bulat --}}
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center font-black text-sm text-gray-600 shadow-inner border border-gray-200/50">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-extrabold text-gray-900 text-[14px] leading-tight">{{ $member->name }}</p>
                                <p class="text-[11px] font-medium text-gray-500 mt-0.5">{{ $member->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            @if($member->id !== auth()->id())
                            <form action="/dashboard/channel/{{ $channel->id }}/members/{{ $member->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Keluarkan Anggota" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-[#E21F26] hover:text-white transition-colors tap-effect border border-transparent hover:border-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </button>
                            </form>
                            @else
                            <div class="bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Pembuat</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-600 mb-1">Belum ada anggota</p>
                        <p class="text-xs text-gray-400 font-medium">Tambahkan siswa lain untuk mulai berdiskusi.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>
@endsection