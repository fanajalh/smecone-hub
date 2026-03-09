@extends('layouts.app')
@section('title', '| Repository')

@section('content')
<div class="max-w-7xl mx-auto pt-4 px-4 sm:px-6 lg:px-8 pb-24 md:pb-10">
    <div class="bg-white px-6 py-5 md:mt-4 shadow-sm flex flex-col md:flex-row md:justify-between items-start md:items-center gap-4 rounded-2xl border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-red-600 tracking-tight">Repository</h1>
            <p class="text-sm text-gray-500 mt-1">Berbagi karya dan tugas praktik antar siswa.</p>
        </div>
        <a href="#" class="w-full md:w-auto bg-red-600 text-white px-5 py-3 rounded-xl shadow-md hover:bg-red-700 active:scale-95 transition-all flex items-center justify-center gap-2 font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            <span>Upload File</span>
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if($repositories->isEmpty())
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-gray-100">
                <p class="text-gray-500 font-medium text-lg">Belum ada file yang dibagikan.</p>
            </div>
        @endif

        @foreach($repositories as $repo)
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white {{ $repo->type == 'karya' ? 'bg-gradient-to-br from-purple-500 to-indigo-600' : 'bg-gradient-to-br from-red-500 to-orange-500' }}">
                        {{ $repo->type == 'karya' ? '🎨' : '📝' }}
                    </div>
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">{{ $repo->type }}</span>
                        <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ $repo->title }}</h2>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 flex-grow mb-4">{{ $repo->description }}</p>
            <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center text-[10px] font-bold text-red-600">
                        {{ substr($repo->user->name, 0, 1) }}
                    </div>
                    <span class="text-xs font-medium text-gray-500">{{ $repo->user->name }}</span>
                </div>
                <a href="{{ $repo->file_link }}" target="_blank" class="text-sm font-bold text-red-600 hover:text-red-800 bg-red-50 px-4 py-2 rounded-lg transition-colors">Lihat File</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection