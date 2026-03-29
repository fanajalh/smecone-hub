@extends('layouts.app')
@section('title', '| Buka Lapak Baru')

@section('content')
<div class="max-w-2xl mx-auto pt-8 md:pt-10 px-4 sm:px-6 lg:px-8 pb-32 md:pb-12 animate-page-in font-sans text-gray-800">
    
    <div class="bg-white md:rounded-[32px] rounded-[24px] border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)] overflow-hidden">
        
        <div class="bg-gradient-to-br from-red-500 to-red-600 p-8 md:p-10 text-center relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-orange-300 opacity-20 rounded-full blur-xl"></div>
            
            <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-full mx-auto flex items-center justify-center shadow-sm mb-4 relative z-10">
                <span class="text-3xl md:text-4xl">🏪</span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold text-white tracking-tight relative z-10">Satu Langkah Lagi!</h1>
            <p class="text-red-100 mt-2 text-[13px] md:text-sm font-normal relative z-10 max-w-sm mx-auto leading-relaxed">Sebelum mulai berjualan, yuk daftarkan nama lapak dan nomor WhatsApp kamu agar pembeli mudah menghubungi.</p>
        </div>

        <div class="p-6 md:p-10">
            <form id="storeForm" action="/marketplace/register-store" method="POST" class="space-y-5 md:space-y-6">
                @csrf
                
                <div>
                    <label class="block text-[14px] font-semibold text-gray-900 mb-1.5">Nama Lapak / Toko <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <input type="text" name="store_name" required placeholder="Misal: Kantin Bu Siti, Jasa Tugas..." value="{{ old('store_name') }}"
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-1 focus:ring-red-500 focus:border-red-500 font-medium text-[14px] transition-colors @error('store_name') border-red-500 @enderror">
                    </div>
                    @error('store_name') <p class="text-[12px] text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-1.5">
                        <label class="block text-[14px] font-semibold text-gray-900">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <p class="text-[11px] md:text-[12px] text-gray-500 font-normal">Untuk menerima notif / COD pembeli.</p>
                    </div>
                    
                    <div class="relative flex items-center">
                        <div class="absolute left-1 top-1 bottom-1 flex items-center px-3 bg-gray-100 border-r border-gray-200 rounded-l-[10px] pointer-events-none">
                            <svg class="w-4 h-4 text-green-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-[14px] font-bold text-gray-700">+62</span>
                        </div>
                        
                        <input type="number" id="wa_input_display" required placeholder="81234567890" 
                               class="w-full pl-24 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-1 focus:ring-green-500 focus:border-green-500 font-medium text-[14px] transition-colors @error('whatsapp_number') border-red-500 @enderror">
                        
                        <input type="hidden" name="whatsapp_number" id="wa_input_hidden" value="{{ old('whatsapp_number') }}">
                    </div>
                    @error('whatsapp_number') <p class="text-[12px] text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 mt-2">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[12px] font-normal text-blue-800 leading-relaxed">
                        Dengan membuka lapak, kamu setuju untuk berjualan dengan jujur. Fitur <span class="font-medium">Notifikasi Bot WA</span> akan segera aktif di nomormu.
                    </p>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-semibold text-[14px] md:text-[15px] py-3.5 rounded-xl shadow-sm hover:bg-red-700 transition-all active:scale-[0.98] mt-2">
                    Daftar & Buka Lapak 🚀
                </button>
                
                <div class="text-center mt-4">
                    <a href="/marketplace" class="text-[13px] font-medium text-gray-500 hover:text-gray-900 transition-colors">Batal, kembali ke Smecone Mart</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('storeForm');
        const displayInput = document.getElementById('wa_input_display');
        const hiddenInput = document.getElementById('wa_input_hidden');

        // Jika form disubmit, gabungkan '62' dengan angka yang diketik (buang angka 0 di depan jika user iseng ngetik 0)
        form.addEventListener('submit', function(e) {
            let userVal = displayInput.value.trim();
            
            // Hapus karakter non-angka (berjaga-jaga)
            userVal = userVal.replace(/\D/g, '');
            
            // Jika user ngetik angka 0 di awal (misal 0812...), hilangkan angka 0 tersebut
            if(userVal.startsWith('0')) {
                userVal = userVal.substring(1);
            }
            
            // Gabungkan 62 dan set ke hidden input untuk dikirim ke backend
            hiddenInput.value = '62' + userVal;
        });

        // (Opsional) Mengisi kembali nilai display jika ada validasi error (old value) dari Laravel
        const oldVal = hiddenInput.value;
        if(oldVal && oldVal.startsWith('62')) {
            displayInput.value = oldVal.substring(2); // tampilkan sisa angkanya saja di form
        }
    });
</script>
@endsection