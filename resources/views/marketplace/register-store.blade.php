    @extends('layouts.app')
@section('title', '| Buka Lapak Baru')

@section('content')
<div class="max-w-3xl mx-auto pt-10 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in">
    
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        
        <div class="bg-gradient-to-br from-red-600 to-red-800 p-8 md:p-12 text-center relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-orange-400 opacity-20 rounded-full blur-xl"></div>
            
            <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center shadow-lg mb-5 relative z-10">
                <span class="text-4xl">🏪</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight relative z-10">Satu Langkah Lagi!</h1>
            <p class="text-red-100 mt-2 font-medium relative z-10 max-w-md mx-auto">Sebelum mulai berjualan, yuk daftarkan nama lapak dan nomor WhatsApp kamu agar pembeli mudah menghubungi.</p>
        </div>

        <div class="p-8 md:p-10">
            <form action="/marketplace/register-store" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-extrabold text-gray-900 mb-2">Nama Lapak / Toko <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <input type="text" name="store_name" required placeholder="Misal: Kantin Bu Siti, Joki Tugas PPLG..." value="{{ old('store_name') }}"
                               class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 font-medium text-sm transition-all @error('store_name') border-red-500 @enderror">
                    </div>
                    @error('store_name') <p class="text-xs text-red-500 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-extrabold text-gray-900 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mb-2 font-medium">Nomor ini akan digunakan pembeli untuk COD / Konfirmasi ke kamu.</p>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <input type="number" name="whatsapp_number" required placeholder="Misal: 08123456789" value="{{ old('whatsapp_number') }}"
                               class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 font-medium text-sm transition-all @error('whatsapp_number') border-red-500 @enderror">
                    </div>
                    @error('whatsapp_number') <p class="text-xs text-red-500 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 mt-4">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs font-medium text-blue-800 leading-relaxed">
                        Dengan membuka lapak, kamu setuju untuk berjualan dengan jujur dan mengikuti aturan sekolah. Fitur Notifikasi Bot WA akan segera aktif di nomormu.
                    </p>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-extrabold text-sm py-4 rounded-xl shadow-[0_8px_20px_rgba(220,38,38,0.2)] hover:bg-red-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Daftar & Buka Lapak Sekarang 🚀
                </button>
                
                <div class="text-center mt-4">
                    <a href="/marketplace" class="text-xs font-bold text-gray-500 hover:text-gray-800 transition">Batal, kembali ke Smecone Mart</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection