@extends('layouts.app')
@section('title', '| Lapak Saya')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Lapak Saya 🏪</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Pantau performa jualanmu di Smecone Mart.</p>
        </div>
        <a href="/marketplace/create" class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-700 shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-gray-400 mb-1 text-sm font-bold uppercase tracking-wider">Total Dilihat 👀</div>
            <div class="text-3xl font-black text-gray-900">{{ $totalViews }} <span class="text-sm text-gray-400 font-medium">kali</span></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-gray-400 mb-1 text-sm font-bold uppercase tracking-wider">Produk Aktif 📦</div>
            <div class="text-3xl font-black text-green-500">{{ $activeProducts }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-gray-400 mb-1 text-sm font-bold uppercase tracking-wider">Terjual / Habis 🔴</div>
            <div class="text-3xl font-black text-red-500">{{ $soldProducts }}</div>
        </div>
        <div class="bg-gradient-to-br from-red-600 to-red-800 p-5 rounded-2xl shadow-lg flex flex-col justify-center relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-10 text-6xl">🔥</div>
            <div class="text-red-100 mb-1 text-sm font-bold uppercase tracking-wider relative z-10">Beli Iklan?</div>
            <div class="text-sm font-medium text-white relative z-10 mt-1">Tingkatkan <span class="font-bold text-yellow-300">Viewers</span> mu dengan Iklan Smecone. (Segera Hadir)</div>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-extrabold text-gray-900">Daftar Produkmu</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-bold">Produk</th>
                        <th class="p-4 font-bold">Harga</th>
                        <th class="p-4 font-bold text-center">Viewers</th>
                        <th class="p-4 font-bold text-center">Status</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="p-4 flex items-center gap-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <a href="/marketplace/{{ $product->id }}" class="font-bold text-gray-900 hover:text-red-600 transition">{{ $product->item_name }}</a>
                                <div class="text-xs text-gray-500">{{ $product->category }}</div>
                            </div>
                        </td>
                        <td class="p-4 font-black text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-4 text-center font-bold text-gray-600"><span class="bg-gray-100 px-3 py-1 rounded-full">{{ $product->views_count }} 👀</span></td>
                        <td class="p-4 text-center">
                            @if($product->is_sold)
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">Habis</span>
                            @else
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">Aktif</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition" title="Ubah Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </button>
                                </form>
                                <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500 font-medium">Kamu belum punya lapak jualan. Mulai buka lapak sekarang!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection