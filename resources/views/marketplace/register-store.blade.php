@extends('layouts.app')
@section('title', '| Buka Lapak Baru')

@section('content')
<div class="w-full min-h-[calc(100vh-100px)] flex flex-col md:flex-row bg-white animate-page-in">
    
    {{-- Left Side: Hero / Graphic --}}
    <div class="md:w-5/12 lg:w-1/2 bg-gradient-to-br from-red-600 via-[#E21F26] to-orange-500 p-10 md:p-16 pt-24 lg:pt-32 flex flex-col justify-center relative overflow-hidden min-h-[40vh] md:min-h-0">
        {{-- Decorative Elements --}}
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-64 h-64 bg-orange-400 opacity-20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10">
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/40 text-[11px] font-black px-4 py-2 rounded-lg uppercase tracking-widest inline-flex items-center gap-1.5 mb-6 shadow-sm">
                <ion-icon name="rocket"></ion-icon> Smecone Seller
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-4 drop-shadow-md tracking-tight">Satu Langkah<br>Lagi!</h1>
            <p class="text-red-50 text-[15px] md:text-[17px] font-bold leading-relaxed max-w-md drop-shadow-sm">Sebelum mulai berjualan dan menambah pundi-pundi rupiah, yuk daftarkan nama lapak dan nomor WhatsApp kamu.</p>
            
            <div class="mt-12 text-7xl md:text-[100px] drop-shadow-2xl animate-float">🏪</div>
        </div>
    </div>

    {{-- Right Side: Form --}}
    <div class="md:w-7/12 lg:w-1/2 flex items-center justify-center p-6 md:p-16 pt-10 lg:pt-32 bg-white relative">
        <div class="w-full max-w-lg relative z-10">
            <div class="mb-10">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Detail Lapak</h2>
                <p class="text-[15px] text-gray-500 font-bold mt-2">Lengkapi informasi untuk toko baru kamu.</p>
            </div>

            <form id="storeForm" action="/marketplace/register-store" method="POST" class="space-y-7">
                @csrf
                
                <div>
                    <label class="block text-[12px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nama Lapak / Toko <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <ion-icon name="storefront" class="text-[20px] text-gray-400"></ion-icon>
                        </div>
                        <input type="text" name="store_name" required placeholder="Misal: Kantin Bu Siti, Jasa Tugas..." value="{{ old('store_name') }}"
                               class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-500 font-bold text-[15px] transition-all text-gray-900 @error('store_name') border-red-500 @enderror">
                    </div>
                    @error('store_name') <p class="text-[13px] text-red-500 mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[12px] font-black uppercase tracking-widest text-gray-400 mb-2.5 ml-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <div class="absolute left-1.5 top-1.5 bottom-1.5 flex items-center px-4 bg-white border-r border-gray-200 rounded-[16px] pointer-events-none shadow-sm z-10">
                            <ion-icon name="logo-whatsapp" class="text-green-500 text-[18px] mr-2"></ion-icon>
                            <span class="text-[15px] font-black text-gray-700">+62</span>
                        </div>
                        
                        <input type="number" id="wa_input_display" required placeholder="81234567890" 
                               class="w-full pl-[110px] pr-5 py-4 bg-gray-50 border border-gray-200 rounded-[20px] focus:outline-none focus:bg-white focus:ring-4 focus:ring-green-500/10 focus:border-green-500 font-black text-[15px] transition-all text-gray-900 @error('whatsapp_number') border-red-500 @enderror relative">
                        
                        <input type="hidden" name="whatsapp_number" id="wa_input_hidden" value="{{ old('whatsapp_number') }}">
                    </div>
                    <p class="text-[12px] text-gray-400 font-bold mt-2 ml-1">Nomor ini untuk menerima orderan / COD pembeli.</p>
                    @error('whatsapp_number') <p class="text-[13px] text-red-500 mt-1 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-blue-50/50 border border-blue-100 rounded-[20px] p-5 flex gap-4 mt-4">
                    <ion-icon name="shield-checkmark" class="text-blue-500 text-[24px] shrink-0 mt-0.5"></ion-icon>
                    <p class="text-[13px] font-bold text-blue-800 leading-relaxed">
                        Dengan membuka lapak, kamu setuju untuk berjualan dengan jujur. Fitur <span class="font-black">Notifikasi Bot WA</span> akan segera aktif otomatis.
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white font-black text-[15px] py-4.5 rounded-[20px] shadow-[0_8px_25px_rgba(220,38,38,0.3)] hover:shadow-[0_12px_30px_rgba(220,38,38,0.4)] hover:-translate-y-1 hover:from-red-700 hover:to-red-800 transition-all active:translate-y-0 active:scale-[0.98] flex items-center justify-center gap-2 h-[56px]">
                        Buka Lapak Sekarang <ion-icon name="rocket" class="text-xl"></ion-icon>
                    </button>
                    
                    <div class="text-center mt-6">
                        <a href="/marketplace" class="text-[14px] font-bold text-gray-400 hover:text-red-500 transition-colors inline-flex items-center gap-1.5">
                            <ion-icon name="arrow-back"></ion-icon> Batal, kembali ke Marketplace
                        </a>
                    </div>
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