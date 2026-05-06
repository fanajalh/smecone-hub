@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-5 sm:p-8 bg-white shadow-lg sm:shadow-2xl rounded-2xl mt-4 sm:mt-10 border border-gray-100 pb-28 sm:pb-8 relative">
    <h2 class="text-2xl sm:text-3xl font-extrabold mb-6 sm:mb-8 text-center text-gray-800">Checkout</h2>
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Gagal Memproses!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="flex items-start sm:items-center mb-6 sm:mb-8 p-4 sm:p-5 border border-gray-100 rounded-xl bg-gray-50/80 shadow-sm transition hover:shadow-md">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-lg shadow-sm shrink-0">
        @endif
        <div class="ml-4 sm:ml-5 flex-1 mt-1 sm:mt-0">
            <span class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider">Ringkasan Pesanan</span>
            <div class="flex flex-col mb-1">
                <h3 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight mt-1">{{ $item->item_name ?? $item->title }}</h3>
                @if(!empty($variant))
                    <span class="text-[12px] font-black text-white bg-blue-500 rounded-lg px-2 py-0.5 mt-1 self-start">Variasi: {{ $variant }}</span>
                @endif
            </div>
            <p class="text-red-600 font-extrabold text-lg sm:text-xl mt-1">Rp {{ number_format($item->price, 0, ',', '.') }} <span class="text-sm text-gray-500 font-medium">x {{ $qty }}</span></p>
        </div>
    </div>

    <form action="{{ route('marketplace.checkout.direct', $item->id) }}" method="POST">
        @csrf
        <input type="hidden" name="qty" value="{{ $qty }}">
        <input type="hidden" name="variant" value="{{ $variant }}">
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp Anda</label>
            <div class="flex">
                <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 font-bold">
                    +62
                </span>
                <input type="number" id="whatsapp_number" name="whatsapp_number" required placeholder="81234567890" 
                       oninput="let v = this.value; if (v.startsWith('0') || v.startsWith('62')) { this.value = v.replace(/^0+|^62+/, ''); }"
                       class="w-full px-4 py-3 rounded-r-lg border border-gray-300 focus:border-red-500 focus:ring-red-500 focus:ring-2 outline-none transition duration-200 text-base">
            </div>
            <p class="text-xs text-gray-500 mt-2">Nomor ini diperlukan untuk mengirimkan rincian pesanan.</p>
        </div>

        @if($item->format === 'Digital')
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Email Tujuan (Untuk Produk Digital)</label>
            <input type="email" name="target_email" required value="{{ auth()->user()->email }}" placeholder="contoh@email.com" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-red-500 focus:ring-2 outline-none transition duration-200 text-base">
            <p class="text-xs text-gray-500 mt-2">Link produk digital atau file akan dikirimkan ke email ini setelah pembayaran lunas.</p>
        </div>
        @endif
        
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-800">Pilih Pembayaran</h3>
                <span class="bg-red-100 text-red-700 text-[10px] sm:text-xs font-bold px-2.5 py-1 rounded-full">Aman & Instan</span>
            </div>
            
            <div class="space-y-3">
                <!-- QRIS -->
                <label class="relative flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-red-50 hover:border-red-300 transition-all duration-200 group has-[:checked]:border-red-500 has-[:checked]:bg-red-50/60 has-[:checked]:ring-1 has-[:checked]:ring-red-500">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 bg-white border border-gray-100 rounded-lg flex items-center justify-center shadow-sm shrink-0">
                            <svg class="h-6 w-auto" viewBox="0 0 100 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="5" y="5" width="6" height="6" fill="#E30A16"/>
                                <rect x="15" y="5" width="6" height="6" fill="#E30A16"/>
                                <rect x="5" y="15" width="6" height="6" fill="#E30A16"/>
                                <rect x="15" y="15" width="2" height="2" fill="#E30A16"/>
                                <rect x="19" y="19" width="2" height="2" fill="#E30A16"/>
                                <rect x="19" y="15" width="2" height="2" fill="#E30A16"/>
                                <rect x="15" y="19" width="2" height="2" fill="#E30A16"/>
                                <text x="26" y="21" font-family="Arial, sans-serif" font-weight="900" font-size="20" fill="#E30A16" font-style="italic">QRIS</text>
                            </svg>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm sm:text-base group-hover:text-red-700 transition-colors">QRIS</span>
                            <span class="block text-xs text-gray-500 mt-0.5">M-Banking & E-Wallet</span>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="QRIS" class="w-5 h-5 text-red-600 focus:ring-red-500 border-gray-300" required checked>
                </label>

                <!-- DANA -->
                <label class="relative flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 group has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/60 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 bg-white border border-gray-100 rounded-lg flex items-center justify-center shadow-sm shrink-0">
                            <svg class="h-4 w-auto" viewBox="0 0 80 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <text x="5" y="18" font-family="Arial, sans-serif" font-weight="900" font-size="22" fill="#118EEA" letter-spacing="-1">DANA</text>
                            </svg>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm sm:text-base group-hover:text-blue-700 transition-colors">DANA</span>
                            <span class="block text-xs text-emerald-600 font-bold mt-0.5">Bebas biaya admin</span>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="DANA" class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300" required>
                </label>

                <!-- GOPAY -->
                <label class="relative flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/60 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 bg-white border border-gray-100 rounded-lg flex items-center justify-center shadow-sm shrink-0">
                            <svg class="h-5 w-auto" viewBox="0 0 80 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <text x="5" y="18" font-family="Arial, sans-serif" font-weight="900" font-size="20" fill="#00AED6" letter-spacing="-0.5">go</text>
                                <text x="28" y="18" font-family="Arial, sans-serif" font-weight="900" font-size="20" fill="#1C1C1C" letter-spacing="-0.5">pay</text>
                            </svg>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm sm:text-base group-hover:text-emerald-700 transition-colors">GoPay</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Bayar instan</span>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="GOPAY" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-gray-300" required>
                </label>

                <!-- COD (Bayar Langsung / Ketemu) -->
                <label class="relative flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-amber-50 hover:border-amber-300 transition-all duration-200 group has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/60 has-[:checked]:ring-1 has-[:checked]:ring-amber-500">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 bg-white border border-gray-100 rounded-lg flex items-center justify-center shadow-sm shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm sm:text-base group-hover:text-amber-700 transition-colors">COD (Bayar Langsung)</span>
                            <span class="block text-xs text-amber-600 font-bold mt-0.5">Ketemuan & bayar tunai</span>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="COD" class="w-5 h-5 text-amber-600 focus:ring-amber-500 border-gray-300" required>
                </label>
            </div>
        </div>

        <!-- Mobile Sticky Footer / Standard Desktop Button -->
        <div class="fixed bottom-0 left-0 right-0 sm:static bg-white sm:bg-transparent border-t sm:border-t-0 border-gray-100 p-4 sm:p-0 z-50 sm:z-auto shadow-[0_-4px_10px_rgba(0,0,0,0.05)] sm:shadow-none">
            <button type="submit" class="w-full bg-red-600 text-white py-3.5 sm:py-4 rounded-xl font-bold text-sm sm:text-lg hover:bg-red-700 hover:shadow-lg sm:hover:-translate-y-0.5 transition-all duration-200 flex justify-between items-center sm:block px-6 sm:px-0">
                <span class="sm:hidden text-left flex flex-col">
                    <span class="text-[10px] text-red-200 font-medium">Total Pembayaran</span>
                    <span class="text-base sm:text-lg">Rp {{ number_format($item->price * $qty, 0, ',', '.') }}</span>
                </span>
                <span class="sm:inline-block">Bayar Sekarang <span class="hidden sm:inline-block ml-1">&rarr;</span></span>
    </button>
    </form>
</div>
@endsection