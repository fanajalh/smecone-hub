@extends('layouts.app')
@section('title', '| Kelola Channel')

@section('content')
<div class="max-w-4xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-24">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="/dashboard" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 text-gray-500 hover:text-red-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Kelola Channel</h1>
            <p class="text-sm text-gray-500">Edit info, hapus, atau atur siapa saja anggotanya.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold text-sm shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Dasar</h2>
            
            <form action="/dashboard/channel/{{ $channel->id }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Nama Channel</label>
                    <input type="text" name="title" value="{{ $channel->title }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-red-500 font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Topik / Deskripsi</label>
                    <textarea name="content" rows="3" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-red-500 text-sm">{{ $channel->content }}</textarea>
                </div>
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition">Simpan Perubahan</button>
            </form>

            <div class="mt-8 pt-6 border-t border-red-100">
                <h2 class="text-sm font-bold text-red-600 mb-2">Zona Berbahaya</h2>
                <form action="/dashboard/channel/{{ $channel->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus channel ini permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-50 text-red-600 font-bold py-3 rounded-xl border border-red-200 hover:bg-red-600 hover:text-white transition">Hapus Channel</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Anggota Channel</h2>
            
            <form action="/dashboard/channel/{{ $channel->id }}/members" method="POST" class="flex gap-2 mb-6">
                @csrf
                <select name="user_id" required class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-red-500">
                    <option value="" disabled selected>Pilih siswa untuk ditambahkan...</option>
                    @foreach($allUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->nis }})</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold text-sm hover:bg-blue-700">Tambah</button>
            </form>

            <div class="flex-1 overflow-y-auto space-y-2 pr-2">
                @foreach($channel->members as $member)
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-xs text-gray-600">{{ substr($member->name, 0, 1) }}</div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $member->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $member->email }}</p>
                        </div>
                    </div>
                    
                    @if($member->id !== auth()->id())
                    <form action="/dashboard/channel/{{ $channel->id }}/members/{{ $member->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">Keluarkan</button>
                    </form>
                    @else
                    <span class="text-xs font-bold text-gray-400 italic">Kamu (Pembuat)</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection