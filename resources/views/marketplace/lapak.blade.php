@extends('layouts.app')
@section('title', '| Lapak Saya')

@section('content')
<div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <!-- Store Header -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 mb-8 relative">
        <!-- Banner -->
        <div class="h-32 md:h-48 w-full bg-gray-200 relative">
            @if(auth()->user()->store_banner)
                <img src="{{ asset('storage/' . auth()->user()->store_banner) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-red-600 to-red-400"></div>
            @endif
            <button onclick="openProfileModal()" class="absolute top-4 right-4 bg-black/50 hover:bg-black/70 backdrop-blur-md text-white px-4 py-2 rounded-full text-xs font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Edit Profil Toko
            </button>
        </div>

        <!-- Info & Actions -->
        <div class="px-6 pb-6 pt-4 flex flex-col md:flex-row gap-4 items-start md:items-end justify-between relative">
            <!-- Profile Photo -->
            <div class="flex items-end gap-4 -mt-16 md:-mt-20 relative z-10 w-full md:w-auto">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-lg shrink-0 overflow-hidden bg-gray-100 flex items-center justify-center text-gray-400 text-3xl font-black uppercase">
                    @if(auth()->user()->store_photo)
                        <img src="{{ asset('storage/' . auth()->user()->store_photo) }}" class="w-full h-full object-cover">
                    @elseif(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(auth()->user()->store_name, 0, 1) }}
                    @endif
                </div>
                <div class="mb-2 md:mb-4">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">{{ auth()->user()->store_name }}</h1>
                    <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mt-1">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Terverifikasi
                    </div>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex gap-2 w-full md:w-auto mt-2 md:mt-0">
                <a href="/marketplace/toko/{{ auth()->id() }}" class="flex-1 md:flex-none justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat Lapak
                </a>
                <a href="{{ route('marketplace.sales') }}" class="w-10 md:w-11 justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 py-2.5 rounded-xl transition-all active:scale-95 flex items-center" title="Riwayat Penjualan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </a>
                <a href="/marketplace/create" class="flex-1 md:flex-none justify-center bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-700 shadow-md transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8">
        <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-gray-400 mb-1 text-xs md:text-sm font-bold uppercase tracking-wider">Total Dilihat 👀</div>
            <div class="text-2xl md:text-3xl font-black text-gray-900">{{ $totalViews }} <span class="text-xs md:text-sm text-gray-400 font-medium">kali</span></div>
        </div>
        <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-gray-400 mb-1 text-xs md:text-sm font-bold uppercase tracking-wider">Produk Aktif 📦</div>
            <div class="text-2xl md:text-3xl font-black text-green-500">{{ $activeProducts }}</div>
        </div>
        <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-gray-400 mb-1 text-xs md:text-sm font-bold uppercase tracking-wider">Terjual / Habis 🔴</div>
            <div class="text-2xl md:text-3xl font-black text-red-500">{{ $soldProducts }}</div>
        </div>
        <div class="bg-gradient-to-br from-red-600 to-red-800 p-4 md:p-5 rounded-2xl shadow-lg flex flex-col justify-center relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-10 text-5xl md:text-6xl">🔥</div>
            <div class="text-red-100 mb-1 text-xs md:text-sm font-bold uppercase tracking-wider relative z-10">Beli Iklan?</div>
            <div class="text-xs md:text-sm font-medium text-white relative z-10 mt-1">Tingkatkan <span class="font-bold text-yellow-300">Viewers</span> mu. (Segera Hadir)</div>
        </div>
    </div>

    <div class="bg-blue-50 rounded-[24px] border border-blue-100 shadow-sm p-4 md:p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                WhatsApp Toko
            </h3>
            <p class="text-sm text-blue-700 mt-1">Nomor ini untuk menerima notif pesanan & dihubungi pembeli.</p>
        </div>
        <form action="{{ route('marketplace.updateWa') }}" method="POST" class="flex w-full md:w-auto gap-2">
            @csrf
            <input type="text" name="whatsapp_number" value="{{ auth()->user()->whatsapp_number }}" placeholder="Contoh: 628123456789" class="w-full md:w-64 px-4 py-2 rounded-lg border border-blue-200 outline-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm">Simpan</button>
        </form>
    </div>

    <div class="bg-white rounded-[24px] border border-green-100 shadow-sm overflow-hidden mb-8 border-l-4 border-l-green-500">
        <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Smecone Bot Integrasi
                </h2>
                <p class="text-sm text-gray-500 mt-1">Broadcast otomatis produk jualanmu ke Grup WA.</p>
            </div>
            <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full border border-green-200 self-start md:self-auto">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-bold text-green-700">API Bot Tersambung</span>
            </div>
        </div>
        <div class="p-4 md:p-6 bg-gray-50 flex items-center justify-between">
            <div class="text-xs md:text-sm text-gray-600 leading-relaxed">
                <span class="font-bold text-gray-800">Cara Pakai:</span> Klik tombol ikon <b>Pesawat Kertas Hijau</b> di bawah untuk menyesuaikan & mengirim iklan.
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-6 border-b border-gray-100">
            <h2 class="text-lg font-extrabold text-gray-900">Daftar Produkmu</h2>
        </div>

        <div class="block md:hidden p-4 space-y-4 bg-gray-50/50">
            @forelse($products as $product)
                @php
                    $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya di web:* \n" . url('/marketplace/' . $product->id);
                @endphp
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex gap-4 items-start mb-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200 shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <a href="/marketplace/{{ $product->id }}" class="font-bold text-gray-900 text-sm hover:text-red-600 line-clamp-2">{{ $product->item_name }}</a>
                            <div class="text-xs text-gray-500 mt-1">{{ $product->category }}</div>
                            <div class="font-black text-gray-800 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <div class="flex gap-2 items-center">
                            <span class="bg-gray-100 px-2 py-1 rounded-md text-[10px] font-bold text-gray-600">{{ $product->views_count }} 👀</span>
                            @if($product->is_sold)
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-[10px] font-black uppercase">Habis</span>
                            @else
                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-md text-[10px] font-black uppercase">Aktif</span>
                            @endif
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" data-id="{{ $product->id }}" data-pesan="{{ $defaultPesan }}" onclick="openBroadcastModal(this)" class="p-2 bg-green-50 text-green-600 rounded-lg shadow-sm active:scale-95">
                                <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                            <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                @csrf
                                <button type="submit" class="p-2 bg-gray-100 text-gray-700 rounded-lg active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </button>
                            </form>
                            <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-8 text-gray-500 font-medium text-sm">Kamu belum punya lapak jualan. Mulai buka lapak sekarang!</div>
            @endforelse
        </div>

        <div class="hidden md:block overflow-x-auto">
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
                    @php
                        $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya di web:* \n" . url('/marketplace/' . $product->id);
                    @endphp
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
                                <button type="button" data-id="{{ $product->id }}" data-pesan="{{ $defaultPesan }}" onclick="openBroadcastModal(this)" class="p-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition shadow-sm" title="Broadcast Iklan ke WA">
                                    <svg class="w-4 h-4 transform -rotate-45 ml-0.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                                <form action="/marketplace/{{ $product->id }}/toggle-sold" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition" title="Ubah Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </button>
                                </form>
                                <form action="/marketplace/{{ $product->id }}/delete" method="POST" onsubmit="confirmDelete(event, this)">
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

<div id="broadcastModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-60 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all relative">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Kirim ke Grup WA
            </h3>
            <button onclick="closeBroadcastModal()" class="text-gray-400 hover:text-red-500 transition font-bold text-xl">&times;</button>
        </div>
        <form id="broadcastForm" method="POST" enctype="multipart/form-data" class="p-5 md:p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Caption Pesan</label>
                <textarea name="pesan" id="modalPesan" rows="6" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition resize-none bg-gray-50 focus:bg-white" required></textarea>
            </div>
            
            <div class="mb-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Gambar (Opsional)</label>
                <input type="file" name="custom_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition cursor-pointer border border-dashed border-gray-300 rounded-xl p-2">
                <p class="text-[11px] text-gray-400 mt-2">Biarkan kosong jika ingin menggunakan gambar asli produk.</p>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeBroadcastModal()" class="px-5 py-2.5 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 text-sm font-bold transition w-full md:w-auto">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-white bg-green-600 rounded-xl hover:bg-green-700 text-sm font-bold flex items-center justify-center gap-2 transition active:scale-95 shadow-md w-full md:w-auto">
                    <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Profil Toko -->
<div id="profileModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-60 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all relative">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                Upload Foto & Banner
            </h3>
            <button onclick="closeProfileModal()" class="text-gray-400 hover:text-red-500 transition font-bold text-xl">&times;</button>
        </div>
        <form action="{{ route('marketplace.updateStoreProfile') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6">
            @csrf
            
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil Toko</label>
                <input type="file" name="store_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition cursor-pointer border border-dashed border-gray-300 rounded-xl p-2">
                <p class="text-[11px] text-gray-400 mt-2">Disarankan rasio 1:1, max 2MB. Jika dikosongkan akan menggunakan Avatar default.</p>
            </div>
            
            <div class="mb-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Banner Toko <span class="text-red-500 text-xs">(Rekomendasi Lanskap)</span></label>
                <input type="file" name="store_banner" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition cursor-pointer border border-dashed border-gray-300 rounded-xl p-2">
                <p class="text-[11px] text-gray-400 mt-2">Gambar sampul akan muncul di lapak kamu. Max 4MB.</p>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="button" onclick="closeProfileModal()" class="px-5 py-2.5 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 text-sm font-bold transition w-full md:w-auto">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-white bg-gray-900 rounded-xl hover:bg-black text-sm font-bold flex items-center justify-center shadow-md transition w-full md:w-auto active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Script Modal Broadcast
    function openBroadcastModal(btn) {
        const productId = btn.getAttribute('data-id');
        const defaultPesan = btn.getAttribute('data-pesan');
        document.getElementById('broadcastForm').action = `/marketplace/${productId}/broadcast`;
        document.getElementById('modalPesan').value = defaultPesan;
        document.getElementById('broadcastModal').classList.remove('hidden');
    }

    function closeBroadcastModal() {
        document.getElementById('broadcastModal').classList.add('hidden');
    }

    // Modal Edit Profile
    function openProfileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }

    function closeProfileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }

    // 2. Script Konfirmasi Delete dengan SweetAlert2
    function confirmDelete(event, form) {
        event.preventDefault(); // Mencegah form langsung tersubmit
        
        Swal.fire({
            title: 'Yakin hapus produk?',
            text: "Produk akan ditarik permanen dari etalase Smecone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500
            cancelButtonColor: '#9ca3af', // Gray-400
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            shape: 'rounded-xl'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading sebentar
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                form.submit(); // Lanjutkan submit form
            }
        });
    }

    // 3. Script Notifikasi Session Laravel menggunakan SweetAlert2
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#10b981',
                timer: 3000, // Otomatis hilang dalam 3 detik
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif
    });
</script>
@endsection