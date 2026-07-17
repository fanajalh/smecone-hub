@extends('layouts.app')
@section('title', '| Tarik Saldo Lapak')

@section('content')
<div class="max-w-2xl mx-auto p-5 sm:p-8 bg-white shadow-lg sm:shadow-2xl rounded-2xl mt-4 sm:mt-10 border border-gray-100 pb-28 sm:pb-8 relative font-sans text-gray-800">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6 sm:mb-8 border-b border-gray-100 pb-5">
        <a href="{{ route('marketplace.lapak') }}" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-600 hover:text-red-600 hover:bg-red-50 transition active:scale-95 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-tight">Tarik Saldo Lapak</h2>
            <p class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5">Transfer pendapatan jualanmu ke rekening bank atau e-wallet</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Gagal!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Info Saldo Card -->
    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-5 sm:p-6 text-white mb-6 sm:mb-8 shadow-md relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 opacity-10 text-8xl">
            <svg class="w-28 h-28" fill="currentColor" viewBox="0 0 24 24"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
        </div>
        <div class="relative z-10">
            <span class="text-emerald-100 text-xs font-bold uppercase tracking-wider block mb-1">Saldo Lapak Tersedia</span>
            <span class="text-3xl sm:text-4xl font-black tracking-tight">Rp {{ number_format(auth()->user()->store_balance, 0, ',', '.') }}</span>
        </div>
    </div>

    <form action="{{ route('marketplace.withdraw') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Nominal Penarikan -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Penarikan (Rp)</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-bold text-base">Rp</span>
                </div>
                <input type="number" name="amount" min="10000" max="{{ auth()->user()->store_balance }}" placeholder="Min: 10.000" 
                       class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-2 outline-none transition text-base font-bold text-gray-900" required>
            </div>
            <p class="text-xs text-gray-500 mt-2">Minimal penarikan dana adalah Rp 10.000.</p>
        </div>

        <!-- Bank / E-Wallet -->
        <div x-data="{ 
            open: false, 
            selectedVal: '', 
            selectedLabel: 'Pilih Bank / E-Wallet',
            options: [
                { value: 'DANA', label: 'DANA', logo: 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg', style: 'height: 18px; max-height: 18px; width: auto; object-fit: contain;' },
                { value: 'GOPAY', label: 'GoPay', logo: 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg', style: 'height: 18px; max-height: 18px; width: auto; object-fit: contain;' },
                { value: 'OVO', label: 'OVO', logo: 'https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg', style: 'height: 18px; max-height: 18px; width: auto; object-fit: contain;' },
                { value: 'BCA', label: 'BCA', logo: 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg', style: 'height: 18px; max-height: 18px; width: auto; object-fit: contain;' },
                { value: 'MANDIRI', label: 'Mandiri', logo: 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg', style: 'height: 18px; max-height: 18px; width: auto; object-fit: contain;' },
                { value: 'BRI', label: 'BRI', logo: 'https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg', style: 'height: 18px; max-height: 18px; width: auto; object-fit: contain;' },
                { value: 'BNI', label: 'BNI', logo: 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Negara_Indonesia_logo_%282004%29.svg', style: 'height: 14px; max-height: 14px; width: auto; object-fit: contain;' }
            ]
        }" class="relative">
            <label class="block text-sm font-bold text-gray-700 mb-2">Bank / E-Wallet Tujuan</label>
            
            <!-- Hidden input to submit form -->
            <input type="hidden" name="bank_name" :value="selectedVal" required>

            <!-- Custom Select Button -->
            <button type="button" @click="open = !open" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-white text-left focus:border-emerald-500 focus:ring-emerald-500 focus:ring-2 outline-none transition text-base font-medium flex items-center justify-between shadow-sm">
                <span class="flex items-center gap-3">
                    <template x-if="selectedVal !== ''">
                        <img :src="options.find(o => o.value === selectedVal)?.logo" :style="options.find(o => o.value === selectedVal)?.style" class="w-auto object-contain">
                    </template>
                    <span :class="selectedVal === '' ? 'text-gray-400' : 'text-gray-900 font-bold'" x-text="selectedLabel"></span>
                </span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Options List -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 style="display: none;"
                 class="absolute z-50 mt-1 w-full rounded-xl bg-white border border-gray-200 shadow-xl max-h-60 overflow-y-auto focus:outline-none">
                <div class="py-1">
                    <template x-for="option in options" :key="option.value">
                        <button type="button" @click="selectedVal = option.value; selectedLabel = option.label; open = false"
                                class="w-full text-left px-4 py-3.5 text-sm hover:bg-emerald-50 hover:text-emerald-900 transition flex items-center justify-between group">
                            <span class="flex items-center gap-3">
                                <img :src="option.logo" :style="option.style" class="w-auto object-contain brightness-95 group-hover:brightness-100">
                                <span class="font-bold text-gray-700 group-hover:text-emerald-950" x-text="option.label"></span>
                            </span>
                            <svg x-show="selectedVal === option.value" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Nomor Rekening / E-Wallet -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Rekening / E-Wallet</label>
            <input type="text" name="account_number" placeholder="Contoh: 08123456789 atau 12345678" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-2 outline-none transition text-base" required>
            <p class="text-xs text-gray-500 mt-2">Masukkan nomor rekening bank atau nomor HP yang terdaftar pada e-wallet.</p>
        </div>

        <!-- Atas Nama Pemilik Rekening -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Atas Nama (Pemilik Rekening)</label>
            <input type="text" name="account_name" placeholder="Sesuai buku tabungan / aplikasi e-wallet" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-2 outline-none transition text-base" required>
            <p class="text-xs text-gray-500 mt-2">Pastikan nama pemilik rekening sesuai agar pencairan dana tidak terkendala.</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('marketplace.lapak') }}" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3.5 rounded-xl font-bold transition text-center text-sm sm:text-base flex items-center justify-center active:scale-95">
                Batal
            </a>
            <button type="submit" class="w-1/2 bg-emerald-500 hover:bg-emerald-600 text-white py-3.5 rounded-xl font-bold transition text-center text-sm sm:text-base flex items-center justify-center shadow-md shadow-emerald-500/10 active:scale-95">
                Ajukan Penarikan
            </button>
        </div>
    </form>
</div>
@endsection
