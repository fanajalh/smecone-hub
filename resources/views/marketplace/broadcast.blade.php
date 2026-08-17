@extends('layouts.app')
@section('title', '| Siaran Promosi WhatsApp')

@section('content')
<div class="max-w-7xl mx-auto pt-24 md:pt-32 px-4 md:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in font-sans text-gray-800 min-h-[85vh] flex flex-col">
    
    <div class="mb-6 shrink-0">
        <a href="{{ route('marketplace.lapak') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Lapak
        </a>
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 md:w-10 md:h-10 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
            Siaran Promosi Multi-Grup
        </h1>
        <p class="text-gray-500 mt-2 text-base">Kirim pesan siaran produk <strong class="text-gray-800">{{ $product->item_name }}</strong> langsung dari nomor WhatsApp Anda ke grup-grup pilihan.</p>
    </div>

    <!-- Sesi WhatsApp Status Box -->
    <div class="bg-white rounded-[24px] md:rounded-[28px] p-6 mb-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 {{ $status === 'CONNECTED' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                @if($status === 'CONNECTED')
                    <i class="fa-solid fa-check"></i>
                @else
                    <i class="fa-solid fa-qrcode"></i>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $status === 'CONNECTED' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $status === 'CONNECTED' ? 'WhatsApp Terhubung' : 'Belum Terhubung' }}
                    </span>
                    @if($phone)
                        <span class="text-xs font-bold text-gray-500">+{{ $phone }}</span>
                    @endif
                </div>
                <p class="text-xs md:text-sm text-gray-600 mt-1">
                    @if($status === 'CONNECTED')
                        Nomor WhatsApp Anda aktif dan siap menyiarkan iklan ke grup-grup Anda.
                    @else
                        Tautkan WhatsApp Anda dengan scan QR agar sistem bisa mendeteksi grup dan menyiarkan promosi.
                    @endif
                </p>
            </div>
        </div>
        <div class="shrink-0 w-full md:w-auto">
            @if($status === 'CONNECTED')
                <button type="button" onclick="logoutWaSession()" class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-red-50 text-red-600 font-bold text-xs hover:bg-red-100 transition">
                    Ganti Nomor / Logout
                </button>
            @else
                <a href="{{ $qrUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full md:w-auto px-6 py-3 rounded-xl bg-[#25D366] text-white font-bold text-xs hover:bg-green-600 transition shadow-sm">
                    <i class="fa-solid fa-qrcode"></i> Buka Scan QR WhatsApp
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[24px] md:rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6 md:p-10 w-full">
        <form action="/marketplace/{{ $product->id }}/broadcast" method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8">
            @csrf
            
            <!-- Pilih Target Grup WA -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-[15px] font-bold text-gray-900">Pilih Grup Target Siaran</label>
                    @if(!empty($groups) && count($groups) > 0)
                        <button type="button" onclick="toggleSelectAllGroups()" id="btnSelectAll" class="text-xs font-bold text-green-600 hover:text-green-700">
                            Pilih Semua ({{ count($groups) }} Grup)
                        </button>
                    @endif
                </div>

                @if(!empty($groups) && count($groups) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[320px] overflow-y-auto p-4 border border-gray-200/80 rounded-2xl bg-gray-50/50 shadow-inner">
                        @foreach($groups as $idx => $grp)
                            <label class="flex items-start gap-3.5 p-4 rounded-xl bg-white border border-gray-200 hover:border-green-500 hover:shadow-md cursor-pointer transition-all duration-300 select-none relative overflow-hidden group">
                                <div class="absolute inset-0 bg-green-50/0 group-hover:bg-green-50/50 transition-colors pointer-events-none"></div>
                                <div class="pt-0.5">
                                    <input type="checkbox" name="target_groups[]" value="{{ $grp['id'] }}" class="group-checkbox w-5 h-5 text-green-600 rounded-md border-gray-300 focus:ring-green-500 transition-shadow relative z-10" checked>
                                </div>
                                <div class="min-w-0 flex-1 relative z-10">
                                    <p class="text-sm font-extrabold text-gray-900 truncate leading-tight group-hover:text-green-700 transition-colors">{{ $grp['subject'] }}</p>
                                    <p class="text-xs font-semibold text-gray-500 mt-1 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ $grp['participantsCount'] }} Anggota
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 text-xs text-gray-600">
                        @if($status === 'CONNECTED')
                            <p>Tidak ditemukan grup di akun WhatsApp ini, atau sistem akan mengirim ke Grup Resmi SMEconE Hub (<code>120363425273294200@g.us</code>).</p>
                        @else
                            <p>Hubungkan WhatsApp Anda di atas untuk menampilkan daftar grup secara otomatis.</p>
                        @endif
                        <input type="hidden" name="target_groups[]" value="120363425273294200@g.us">
                    </div>
                @endif
            </div>

            @php
                $defaultPesan = "*📢 IKLAN LAPAK SMECONE 📢*\n\n📦 *Barang:* {$product->item_name}\n🏷️ *Kategori:* {$product->category}\n💰 *Harga:* Rp " . number_format($product->price, 0, ',', '.') . "\n\n🔗 *Cek selengkapnya di web:* \n" . url('/marketplace/' . $product->id);
            @endphp

            <div>
                <label class="block text-[15px] font-bold text-gray-900 mb-3">Teks Promosi (Bisa di-edit bebas)</label>
                <textarea name="pesan" rows="10" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-5 text-[15px] focus:ring-2 focus:ring-green-500/40 focus:border-green-500 focus:bg-white focus:outline-none transition-all resize-y font-mono leading-relaxed" required>{{ $defaultPesan }}</textarea>
                <div class="flex items-center gap-2 mt-3 bg-blue-50/50 text-blue-700 px-4 py-2.5 rounded-xl text-[12px] font-medium border border-blue-100/50">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Gunakan <code class="bg-white text-blue-800 border border-blue-200 px-1 py-0.5 rounded font-bold">*tebal*</code> atau <code class="bg-white text-blue-800 border border-blue-200 px-1 py-0.5 rounded italic">_miring_</code> untuk formatting.</span>
                </div>
            </div>

            <div>
                <label class="block text-[15px] font-bold text-gray-900 mb-3">Gambar Custom (Opsional)</label>
                <div class="flex flex-col md:flex-row gap-5 items-start">
                    <div class="shrink-0">
                        @if($product->image)
                            @php 
                                $decoded = json_decode($product->image, true);
                                $firstImage = is_array($decoded) ? $decoded[0] : $product->image;
                            @endphp
                            <div class="w-28 h-28 rounded-2xl border-2 border-gray-100 overflow-hidden relative group">
                                <img src="{{ asset('storage/' . $firstImage) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 text-white text-[10px] font-bold flex items-center justify-center text-center px-1 opacity-0 group-hover:opacity-100 transition backdrop-blur-sm">
                                    Gambar Asli
                                </div>
                            </div>
                        @else
                            <div class="w-28 h-28 rounded-2xl border-2 border-gray-100 bg-gray-50 flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 w-full bg-gray-50/50 rounded-2xl p-4 border border-dashed border-gray-200">
                        <input type="file" name="custom_image" accept="image/*" class="w-full text-sm focus:outline-none file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-wider file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer transition">
                        <p class="text-[12px] text-gray-500 mt-2 font-medium">Jika tidak diunggah, foto produk bawaan akan otomatis dilampirkan.</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-3 md:justify-end">
                <a href="{{ route('marketplace.lapak') }}" class="px-8 py-3.5 rounded-2xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition text-center shrink-0 w-full md:w-auto">Batal</a>
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-2xl bg-[#25D366] text-white font-bold text-[14px] hover:bg-green-600 transition active:scale-[0.98] shadow-[0_8px_25px_rgba(37,211,102,0.3)] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Kirim Siaran Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let allSelected = true;
    function toggleSelectAllGroups() {
        const checkboxes = document.querySelectorAll('.group-checkbox');
        allSelected = !allSelected;
        checkboxes.forEach(cb => cb.checked = allSelected);
        document.getElementById('btnSelectAll').textContent = allSelected ? 'Hapus Pilihan Semua' : 'Pilih Semua Grup';
    }

    async function logoutWaSession() {
        if (confirm('Yakin ingin memutuskan koneksi sesi WhatsApp Anda?')) {
            try {
                await fetch('{{ rtrim(env('WA_SERVER_URL', 'http://13.212.247.120/smecone-wa'), '/') }}/session/{{ $sessionId }}/logout', { method: 'POST' });
                window.location.reload();
            } catch(e) {
                alert('Gagal logout WhatsApp.');
            }
        }
    }
</script>
@endsection
