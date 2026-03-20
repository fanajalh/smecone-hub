@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-5 sm:p-8 bg-white sm:shadow-2xl rounded-2xl mt-4 sm:mt-10 border border-gray-100 relative text-center pb-24 sm:pb-8">
    
    @if($transaction->status === 'PAID')
        <!-- Header Success Icon -->
        <div class="mb-6 mt-2">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-500/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-2 tracking-tight">Pembayaran Berhasil!</h2>
            <p class="text-sm text-gray-500 px-4">Terima kasih, pembayaran Anda telah berhasil diverifikasi.</p>
        </div>

        <!-- Digital Receipt Card -->
        <div class="relative bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 mb-10 max-w-[340px] mx-auto overflow-hidden">
            
            <!-- Receipt Header -->
            <div class="bg-gray-50/80 p-5 border-b border-dashed border-gray-300 text-center relative">
                <h3 class="font-extrabold text-gray-800 text-sm tracking-widest uppercase">E-Receipt</h3>
                <p class="text-[10px] text-gray-500 font-mono mt-1">Ref ID: TRX-{{ $transaction->id }}</p>
                
                <!-- Decor cutouts -->
                <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-white rounded-full border border-gray-200 shadow-inner z-10"></div>
                <div class="absolute -bottom-2 -right-2 w-4 h-4 bg-white rounded-full border border-gray-200 shadow-inner z-10"></div>
            </div>
            
            <!-- Items Area -->
            <div class="p-6">
                <div class="flex items-center pb-5 border-b border-dashed border-gray-200">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-12 h-12 object-cover rounded shadow-sm shrink-0">
                    @endif
                    <div class="ml-4 flex-1 text-left">
                        <h4 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2">{{ $item->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1 font-medium">1 x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <!-- Payment Breakdown -->
                <div class="py-5 space-y-3 text-sm">
                    <div class="flex justify-between items-center text-gray-600">
                        <span class="font-medium text-xs text-gray-500">Penyedia Layanan</span>
                        <span class="font-bold text-gray-800 text-xs">SMECONE HUB</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 border-t border-dashed border-gray-100 pt-3">
                        <span class="font-medium text-xs text-gray-500">Status Transaksi</span>
                        <span class="font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full text-[10px] uppercase tracking-widest">LUNAS</span>
                    </div>
                </div>
                
                <!-- Total -->
                <div class="pt-2">
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="font-bold text-gray-700 text-[10px] uppercase tracking-widest">Total Bayar</span>
                        <span class="text-xl font-black text-red-600 tracking-tight">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 sm:static bg-white sm:bg-transparent border-t sm:border-t-0 border-gray-100 p-4 sm:p-0 z-50 sm:z-auto shadow-[0_-4px_10px_rgba(0,0,0,0.05)] sm:shadow-none flex justify-center">
            <a href="{{ route('marketplace.index') }}" class="flex items-center justify-center w-full max-w-[340px] shadow-lg shadow-red-600/20 bg-red-600 text-white py-3.5 sm:py-4 rounded-xl font-bold text-sm sm:text-lg hover:bg-red-700 hover:shadow-xl sm:hover:-translate-y-0.5 transition-all duration-200">
                Selesai Belanja
            </a>
        </div>

    @else
        <!-- QRIS Standee Standard Card CSS Wrapper -->
        <div class="relative w-full max-w-[340px] mx-auto bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden font-sans mb-8">
            
            <!-- Background Pattern (Subtle dots) -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1.5px, transparent 1.5px); background-size: 15px 15px; z-index: 0;"></div>

            <!-- Red Triangle Accent Left -->
            <div class="absolute left-0 top-[20%] w-0 h-0 
                        border-t-[50px] border-t-transparent 
                        border-l-[60px] border-l-[#ED1C24] 
                        border-b-[60px] border-b-transparent z-0"></div>

            <!-- Red Polygon Accent Bottom Right -->
            <div class="absolute right-0 bottom-0 w-[200px] h-[75px] bg-[#ED1C24] z-0" style="clip-path: polygon(100% 0, 100% 100%, 0 100%);"></div>

            <!-- Header: Logos -->
            <div class="relative z-10 px-5 pt-5 flex justify-between items-start w-full">
                <!-- QRIS Official Logo Styling -->
                <div class="flex items-center">
                    <svg viewBox="0 0 140 30" fill="none" class="h-6 w-auto" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0" y="5" width="6" height="6" fill="#000"/>
                        <rect x="10" y="5" width="6" height="6" fill="#000"/>
                        <rect x="0" y="15" width="6" height="6" fill="#000"/>
                        <rect x="10" y="15" width="2" height="2" fill="#000"/>
                        <rect x="14" y="19" width="3" height="2" fill="#000"/>
                        <rect x="14" y="15" width="2" height="2" fill="#000"/>
                        <rect x="10" y="19" width="2" height="2" fill="#000"/>
                        <text x="22" y="22" font-family="Arial, sans-serif" font-weight="900" font-size="28" fill="#000" font-style="italic" letter-spacing="-2">QRIS</text>
                        <!-- Vertical line -->
                        <rect x="83" y="2" width="1" height="24" fill="#000"/>
                    </svg>
                    <div class="ml-1.5 flex flex-col justify-center h-6 -ml-4">
                        <span class="text-[5.5px] font-bold leading-tight text-black tracking-tight" style="line-height: 1;">QR Code Standar</span>
                        <span class="text-[5.5px] font-bold leading-tight text-black tracking-tight" style="line-height: 1;">Pembayaran Nasional</span>
                    </div>
                </div>
                
                <!-- GPN Logo -->
                <div class="flex flex-col items-center mt-1">
                    <svg class="h-5 w-auto" viewBox="0 0 40 30">
                        <path d="M20,5 Q25,12 35,5 Q30,12 30,25 Q20,15 15,25 Q20,10 5,5 Q15,12 20,5" fill="#ED1C24"/>
                        <circle cx="21" cy="11" r="5" fill="#004A99"/>
                    </svg>
                    <span class="text-[8px] font-extrabold text-[#004A99] tracking-tighter mt-1">GPN</span>
                </div>
            </div>

            <!-- Merchant Info Area -->
            <div class="relative z-10 text-center mt-3 px-6 w-full">
                <h3 class="font-bold text-black text-sm uppercase leading-tight line-clamp-1">SMECONE HUB</h3>
                <p class="text-[10px] text-black font-semibold mt-1">NMID: ID1828192023120</p>
                <p class="text-[10px] text-black font-medium mt-0.5 mb-2">TID: TRX-{{ $transaction->id }}</p>
                
                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-100 mb-6 text-left flex items-start shadow-sm">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-12 h-12 object-cover rounded-md shadow-sm shrink-0">
                    @endif
                    <div class="ml-3 flex-1 flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Membayar Untuk Detail Pesanan</span>
                        <h3 class="font-bold text-gray-700 text-sm leading-tight line-clamp-2">{{ $item->title }}</h3>
                    </div>
                    <div class="bg-red-50 text-red-700 border border-red-200 py-1.5 px-3 rounded-lg inline-block shadow-sm">
                    <span class="text-[9px] font-bold block leading-tight text-red-500 uppercase tracking-wider">Total Tagihan</span>
                    <span class="text-lg font-black leading-tight tracking-tight">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
                </div>
            </div>

            <!-- The QR Code Image -->
            <div class="relative z-10 mt-3 mb-2 flex justify-center w-full">
                <div class="bg-white p-2 rounded-lg border-2 border-black inline-block relative shadow-sm">
                    <div class="absolute inset-0 bg-red-600/5 opacity-0 hover:opacity-100 transition-opacity pointer-events-none"></div>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($transaction->invoice_url) }}" 
                        alt="QRIS Barcode" class="w-48 h-48 sm:w-52 sm:h-52 relative z-10 transition-transform hover:scale-[1.02] duration-300">
                </div>
            </div>

            <!-- Text Below QR Code -->
            <div class="relative z-10 text-center mb-6 w-full">
                <h4 class="font-extrabold text-black text-sm tracking-wide">SATU QRIS UNTUK SEMUA</h4>
                <p class="text-[9px] text-[#555] font-semibold mt-0.5">Cek aplikasi penyelenggara<br>di www.aspi-qris.id</p>
            </div>

            <!-- Footer Meta & Icons -->
            <div class="relative z-10 px-4 pb-2 flex justify-between items-end w-full">
                <div class="text-[7px] text-black flex flex-col font-medium opacity-80 pb-1">
                    <span>Dicetak oleh: SA Smecone Hub</span>
                    <span class="mt-0.5">Versi cetak: 1.0.0</span>
                </div>
                
                <!-- White icons mapped over the red polygon -->
                <div class="flex flex-col items-end pt-5 w-1/2">
                    <span class="text-[8px] text-white font-bold mb-1 mr-1">Cara Bayar dengan QRIS:</span>
                    <div class="flex gap-2">
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 bg-white rounded-full flex items-center justify-center mb-0.5">
                                <svg class="w-2.5 h-2.5 text-[#ED1C24]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <span class="text-[6px] text-white font-bold tracking-tight">E-Wallet</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 bg-white rounded-full flex items-center justify-center mb-0.5">
                                <svg class="w-2.5 h-2.5 text-[#ED1C24]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[6px] text-white font-bold tracking-tight">Mobile</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center gap-2 mb-6 text-amber-600 bg-amber-50 py-2.5 px-4 rounded-lg">
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm font-medium animate-pulse">Menunggu pembayaran... (Auto-refresh 5 mnt)</p>
        </div>

        <div class="fixed bottom-0 left-0 right-0 sm:static bg-white sm:bg-transparent border-t sm:border-t-0 border-gray-100 p-4 sm:p-0 z-50 sm:z-auto shadow-[0_-4px_10px_rgba(0,0,0,0.05)] sm:shadow-none flex gap-3">
            <a href="{{ route('marketplace.index') }}" class="w-1/2 bg-gray-100 text-gray-700 py-3.5 sm:py-4 rounded-xl font-bold hover:bg-gray-200 hover:shadow-sm hover:-translate-y-0.5 transition-all text-sm sm:text-base flex items-center justify-center">
                Batalkan
            </a>
            <button onclick="window.location.reload()" class="w-1/2 bg-red-600 text-white py-3.5 sm:py-4 rounded-xl font-bold hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 text-sm sm:text-base">
                Cek Status
            </button>
        </div>

        <script>
            setTimeout(function() { 
                window.location.reload(); 
            }, 300000); 
        </script>
    @endif
</div>
@endsection