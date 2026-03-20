@extends('layouts.app')

@section('content')

@php
    $method = isset($paymentMethod) ? $paymentMethod : 'E-WALLET';
    
    // Tentukan tema dan LOGO berdasarkan payment method
    if ($method === 'GOPAY') {
        $themeColor = 'bg-[#00AED6]';
        $themeText = 'text-[#00AED6]';
        $themeRing = 'ring-[#00AED6]/30';
        $themeShadow = 'shadow-[#00AED6]/20';
        $themeBgLight = 'bg-[#00AED6]/5';
        // Logo Asli GoPay
        $logoSvg = '<div class="flex items-center gap-1.5"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 24c6.351 0 11.5-5.149 11.5-11.5S17.851 1 11.5 1 0 6.149 0 12.5 5.149 24 11.5 24zm-2.875-6.5a1.625 1.625 0 11-3.25 0V7.5a1.625 1.625 0 113.25 0v10zm7.25 0a1.625 1.625 0 11-3.25 0V7.5a1.625 1.625 0 113.25 0v10z"/></svg><span class="font-bold text-white text-2xl tracking-tighter">gopay</span></div>';
    } else {
        // Default DANA
        $themeColor = 'bg-[#118EE9]';
        $themeText = 'text-[#118EE9]';
        $themeRing = 'ring-[#118EE9]/30';
        $themeShadow = 'shadow-[#118EE9]/20';
        $themeBgLight = 'bg-[#118EE9]/5';
        // Logo Asli DANA
        $logoSvg = '<svg class="h-6" viewBox="0 0 100 28" fill="white"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-weight="900" font-style="italic" font-size="30" letter-spacing="-1">DANA</text></svg>';
    }
@endphp

<div class="bg-gray-100 min-h-screen pb-28 pt-4 sm:pt-10 font-sans flex items-start justify-center px-4">
    <div class="w-full max-w-[400px]">
        
        @if($transaction->status === 'PAID')
            <div class="{{ $themeColor }} rounded-t-3xl px-6 pt-10 pb-20 relative text-center overflow-hidden shadow-lg">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner relative z-10 border border-white/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-white mb-1 relative z-10 tracking-tight">Pembayaran Berhasil</h2>
                <p class="text-sm text-white/90 relative z-10 font-medium">Terima kasih telah berbelanja!</p>
            </div>

            <div class="relative bg-white -mt-12 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] z-20 overflow-hidden">
                
                <div class="absolute -left-3 top-[100px] w-6 h-6 bg-gray-100 rounded-full shadow-inner border-r border-gray-200/50"></div>
                <div class="absolute -right-3 top-[100px] w-6 h-6 bg-gray-100 rounded-full shadow-inner border-l border-gray-200/50"></div>

                <div class="p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-black text-gray-800 tracking-widest">SMECONE HUB</h3>
                        <p class="text-xs text-gray-500 font-mono mt-1">E-RECEIPT #TRX-{{ $transaction->id }}</p>
                    </div>

                    <div class="space-y-3 mb-6 border-b border-dashed border-gray-300 pb-6">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-medium">Waktu Transaksi</span>
                            <span class="font-bold text-gray-800">{{ $transaction->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-medium">Metode Pembayaran</span>
                            <span class="font-bold {{ $themeText }} bg-gray-50 px-2 py-1 rounded">{{ $method }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-medium">Status Pembayaran</span>
                            <span class="font-black text-emerald-600 tracking-wider">LUNAS</span>
                        </div>
                    </div>

                    <div class="mb-6 border-b border-dashed border-gray-300 pb-6">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">Rincian Pesanan</span>
                        <div class="flex items-start gap-4">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-12 h-12 object-cover rounded-lg shadow-sm shrink-0 border border-gray-100">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm leading-tight">{{ $item->title }}</h4>
                                <div class="flex justify-between items-end mt-2">
                                    <span class="text-xs text-gray-500 font-medium">1 x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="font-bold text-sm text-gray-800">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="font-bold text-gray-600 text-xs uppercase tracking-widest">Total Bayar</span>
                        <span class="text-2xl font-black text-gray-900 tracking-tight">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-6 flex flex-col items-center justify-center opacity-40">
                        <svg class="h-8 w-48" viewBox="0 0 100 20" preserveAspectRatio="none">
                            <rect x="0" y="0" width="2" height="20" fill="#000"/><rect x="4" y="0" width="1" height="20" fill="#000"/><rect x="7" y="0" width="3" height="20" fill="#000"/><rect x="12" y="0" width="1" height="20" fill="#000"/><rect x="15" y="0" width="4" height="20" fill="#000"/><rect x="21" y="0" width="2" height="20" fill="#000"/><rect x="25" y="0" width="1" height="20" fill="#000"/><rect x="28" y="0" width="3" height="20" fill="#000"/><rect x="33" y="0" width="2" height="20" fill="#000"/><rect x="37" y="0" width="1" height="20" fill="#000"/><rect x="40" y="0" width="4" height="20" fill="#000"/><rect x="46" y="0" width="2" height="20" fill="#000"/><rect x="50" y="0" width="3" height="20" fill="#000"/><rect x="55" y="0" width="1" height="20" fill="#000"/><rect x="58" y="0" width="2" height="20" fill="#000"/><rect x="62" y="0" width="4" height="20" fill="#000"/><rect x="68" y="0" width="1" height="20" fill="#000"/><rect x="71" y="0" width="2" height="20" fill="#000"/><rect x="75" y="0" width="3" height="20" fill="#000"/><rect x="80" y="0" width="1" height="20" fill="#000"/><rect x="83" y="0" width="4" height="20" fill="#000"/><rect x="89" y="0" width="2" height="20" fill="#000"/><rect x="93" y="0" width="1" height="20" fill="#000"/><rect x="96" y="0" width="4" height="20" fill="#000"/>
                        </svg>
                        <p class="text-[9px] font-mono tracking-widest mt-1 text-gray-500">{{ $transaction->id }}{{ rand(1000,9999) }}{{ date('Ymd') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 mb-8">
                <a href="{{ route('marketplace.index') }}" class="flex items-center justify-center w-full {{ $themeColor }} text-white py-4 rounded-xl font-bold text-sm hover:brightness-110 shadow-lg {{ $themeShadow }} transition-all">
                    Selesai Berbelanja
                </a>
            </div>

        @else
            <div class="{{ $themeColor }} rounded-t-3xl px-6 pt-10 pb-20 relative text-center overflow-hidden shadow-lg">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>
                
                <h2 class="text-[10px] font-bold text-white/90 mb-3 uppercase tracking-widest relative z-10">Bayar Melalui</h2>
                <div class="bg-white/20 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white/30 shadow-sm relative z-10 flex items-center justify-center min-w-[120px] mx-auto w-max">
                    {!! $logoSvg !!}
                </div>
            </div>

            <div class="relative bg-white -mt-12 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] z-20 overflow-hidden">
                
                <div class="absolute -left-3 top-[80px] w-6 h-6 bg-gray-100 rounded-full shadow-inner border-r border-gray-200/50"></div>
                <div class="absolute -right-3 top-[80px] w-6 h-6 bg-gray-100 rounded-full shadow-inner border-l border-gray-200/50"></div>

                <div class="p-6">
                    <div class="text-center mb-6 border-b border-dashed border-gray-300 pb-6">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">Selesaikan Dalam</p>
                        <div class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-full border border-red-100 shadow-sm">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="font-bold text-sm">Menunggu Pembayaran...</span>
                        </div>
                    </div>

                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">ID: TRX-{{ $transaction->id }}</span>

                    <div class="bg-white border-2 border-dashed {{ explode(' ', $themeRing)[0] ?? 'border-gray-200' }} rounded-xl p-5 text-center mb-6 relative overflow-hidden">
                        <div class="absolute inset-0 {{ $themeBgLight }} pointer-events-none"></div>
                        <p class="text-[11px] font-bold {{ $themeText }} uppercase tracking-widest mb-1.5 relative z-10">Total Tagihan</p>
                        <h3 class="text-3xl font-black text-gray-800 tracking-tight relative z-10 flex items-start justify-center">
                            <span class="text-sm font-bold text-gray-400 mt-1 mr-1">Rp</span>{{ number_format($item->price, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="mb-6 bg-gray-50/50 p-4 rounded-xl border border-gray-100/80">
                        <h4 class="font-bold text-gray-800 text-xs mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $themeText }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Instruksi Pembayaran
                        </h4>
                        <ol class="space-y-2 text-xs text-gray-600 list-decimal list-inside">
                            <li>Klik tombol <strong class="text-gray-800">Buka Aplikasi {{ $method }}</strong>.</li>
                            <li>Aplikasi akan terbuka otomatis di HP Anda.</li>
                            <li>Periksa tagihan & konfirmasi dengan PIN.</li>
                        </ol>
                    </div>

                    <div class="mt-4 flex flex-col items-center justify-center opacity-40">
                        <svg class="h-8 w-48" viewBox="0 0 100 20" preserveAspectRatio="none">
                            <rect x="0" y="0" width="2" height="20" fill="#000"/><rect x="4" y="0" width="1" height="20" fill="#000"/><rect x="7" y="0" width="3" height="20" fill="#000"/><rect x="12" y="0" width="1" height="20" fill="#000"/><rect x="15" y="0" width="4" height="20" fill="#000"/><rect x="21" y="0" width="2" height="20" fill="#000"/><rect x="25" y="0" width="1" height="20" fill="#000"/><rect x="28" y="0" width="3" height="20" fill="#000"/><rect x="33" y="0" width="2" height="20" fill="#000"/><rect x="37" y="0" width="1" height="20" fill="#000"/><rect x="40" y="0" width="4" height="20" fill="#000"/><rect x="46" y="0" width="2" height="20" fill="#000"/><rect x="50" y="0" width="3" height="20" fill="#000"/><rect x="55" y="0" width="1" height="20" fill="#000"/><rect x="58" y="0" width="2" height="20" fill="#000"/><rect x="62" y="0" width="4" height="20" fill="#000"/><rect x="68" y="0" width="1" height="20" fill="#000"/><rect x="71" y="0" width="2" height="20" fill="#000"/><rect x="75" y="0" width="3" height="20" fill="#000"/><rect x="80" y="0" width="1" height="20" fill="#000"/><rect x="83" y="0" width="4" height="20" fill="#000"/><rect x="89" y="0" width="2" height="20" fill="#000"/><rect x="93" y="0" width="1" height="20" fill="#000"/><rect x="96" y="0" width="4" height="20" fill="#000"/>
                        </svg>
                        <p class="text-[9px] font-mono tracking-widest mt-1 text-gray-500">{{ $transaction->id }}{{ rand(1000,9999) }}{{ date('Ymd') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 mb-8 px-1 space-y-3 relative z-30">
                @if($transaction->invoice_url && str_starts_with($transaction->invoice_url, 'http'))
                <a href="{{ $transaction->invoice_url }}" class="w-full {{ $themeColor }} text-white py-3.5 rounded-xl font-bold text-sm hover:brightness-110 shadow-lg {{ $themeShadow }} transition-all flex items-center justify-center gap-2">
                    Buka Aplikasi {{ $method }}
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                @endif
                
                <div class="flex gap-3">
                    <a href="{{ route('marketplace.index') }}" class="flex-1 bg-white shadow-sm text-gray-600 border border-gray-200/60 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all text-xs flex items-center justify-center">
                        Batal
                    </a>
                    <button onclick="window.location.reload()" class="flex-1 bg-white shadow-sm border border-gray-200/60 text-gray-800 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all text-xs flex items-center justify-center gap-2">
                        Refresh Status
                    </button>
                </div>
            </div>

            <script>
                setTimeout(function() { 
                    window.location.reload(); 
                }, 30000); 
            </script>
        @endif
        
    </div>
</div>
@endsection