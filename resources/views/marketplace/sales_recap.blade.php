@extends('layouts.app')
@section('title', '| Rekap Penjualan')

@section('content')
<style>
    .tap-effect:active { transform: scale(0.98); transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1); }
    [x-cloak] { display: none !important; }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Ticket specific styles */
    .ticket-gradient { background: linear-gradient(135deg, #ffffff 0%, #fcfdfd 100%); }
    .receipt-bg { background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 12px 12px; }
</style>

<div class="bg-[#F8FAFC] min-h-screen pb-24 font-sans text-gray-800 selection:bg-emerald-100 selection:text-emerald-900">
    
    {{-- HEADER --}}
    <div class="bg-white px-5 pt-4 pb-5 rounded-b-[2rem] shadow-sm mb-8 md:max-w-5xl md:mx-auto md:rounded-[2rem] md:mt-6 animate-fade-in-up border border-gray-100">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3.5">
                <a href="/marketplace/lapak-saya" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 transition-colors tap-effect shrink-0 border border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-[19px] md:text-[24px] font-black text-gray-900 tracking-tight leading-tight uppercase">Rekap Penjualan</h2>
                    <p class="text-[12px] md:text-[14px] text-gray-500 font-medium mt-0.5 font-mono">DASHBOARD & LAPORAN</p>
                </div>
            </div>
            <a href="{{ route('marketplace.recap.export') }}" class="bg-gray-900 hover:bg-black text-white font-bold px-4 py-2.5 rounded-xl text-[12px] md:text-[13px] transition-all flex items-center gap-2 tap-effect shadow-md shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span class="hidden md:inline">Print CSV</span>
                <span class="md:hidden">Print</span>
            </a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 md:px-6">

        {{-- GRAND TOTAL CARDS (Kupon Style) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4 mb-10 animate-fade-in-up" style="animation-delay: 0.1s;">
            {{-- Pendapatan Kupon --}}
            <div class="bg-white p-5 rounded-2xl border-2 border-emerald-50 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform"><svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg></div>
                <div class="text-emerald-600 text-[10px] md:text-[11px] font-black mb-1 uppercase tracking-[0.15em]">Grand Total Rp</div>
                <div class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}</div>
            </div>
            {{-- Unit Kupon --}}
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-gray-400 text-[10px] md:text-[11px] font-black mb-1 uppercase tracking-[0.15em]">Total Item Keluar</div>
                <div class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">{{ $grandTotalQty }} <span class="text-[14px] font-bold text-gray-400">PCS</span></div>
            </div>
            {{-- Transaksi Kupon --}}
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-gray-400 text-[10px] md:text-[11px] font-black mb-1 uppercase tracking-[0.15em]">Tiket Lunas</div>
                <div class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">{{ $grandTotalTransactions }} <span class="text-[14px] font-bold text-gray-400">TRX</span></div>
            </div>
        </div>

        {{-- ======================== TICKET LIST ======================== --}}
        <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.2s;">
            @forelse($recap as $index => $item)
            
            <div x-data="{ expanded: false }" class="relative group">
                
                {{-- MAIN TICKET CARD --}}
                <button @click="expanded = !expanded" class="w-full flex items-stretch bg-white rounded-[1.5rem] shadow-[0_4px_15px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.06)] border border-gray-100 transition-all duration-300 text-left relative z-10 focus:outline-none overflow-hidden ticket-gradient">
                    
                    {{-- Left Section: Event/Product Details --}}
                    <div class="flex-1 p-4 md:p-6 flex flex-col md:flex-row md:items-center gap-4 md:gap-6 relative">
                        
                        {{-- Rank Badge (Stempel) --}}
                        <div class="absolute top-0 left-0 bg-gray-900 text-white text-[10px] font-black px-3 py-1 rounded-br-xl uppercase tracking-wider shadow-sm z-10">
                            Rank #{{ $index + 1 }}
                        </div>

                        {{-- Product Image --}}
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl bg-gray-50 shrink-0 border border-gray-200 overflow-hidden flex items-center justify-center mt-3 md:mt-0 shadow-inner">
                            @if($item['product']->image)
                                <img src="{{ asset('storage/' . $item['product']->image) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="text-[10px] md:text-[11px] text-gray-400 font-mono tracking-widest mb-1">{{ strtoupper($item['product']->category) }}</div>
                            <h3 class="text-[16px] md:text-[20px] font-black text-gray-900 truncate pr-2 leading-tight">{{ $item['product']->item_name }}</h3>
                            
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2 md:mt-3">
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Revenue</span>
                                    <span class="text-[14px] md:text-[16px] font-black text-emerald-600">Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}</span>
                                </div>
                                <div class="hidden md:block w-px h-6 bg-gray-200"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Lunas</span>
                                    <span class="text-[13px] md:text-[15px] font-bold text-gray-700">{{ $item['paid_transactions'] }} Trx</span>
                                </div>
                                <div class="hidden md:block w-px h-6 bg-gray-200"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Stok Sisa</span>
                                    <span class="text-[13px] md:text-[15px] font-bold text-gray-700">{{ $item['product']->stock ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Perforation / Ticket Divider --}}
                    <div class="relative w-0 flex flex-col justify-between items-center border-l-[3px] border-dashed border-gray-200 bg-white z-20">
                        {{-- Top Cutout (Notch) --}}
                        <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-[#F8FAFC] rounded-full border border-gray-100 shadow-[inset_0_-2px_4px_rgba(0,0,0,0.02)]"></div>
                        {{-- Bottom Cutout (Notch) --}}
                        <div class="absolute bottom-0 left-0 -translate-x-1/2 translate-y-1/2 w-8 h-8 bg-[#F8FAFC] rounded-full border border-gray-100 shadow-[inset_0_2px_4px_rgba(0,0,0,0.02)]"></div>
                    </div>

                    {{-- Right Section: Ticket Stub --}}
                    <div class="w-24 md:w-44 p-4 md:p-6 bg-gray-50/50 flex flex-col items-center justify-center relative overflow-hidden transition-colors" :class="expanded ? 'bg-emerald-50/30' : ''">
                        
                        {{-- Fake Barcode Graphic (Desktop Only) --}}
                        <div class="hidden md:flex gap-[3px] opacity-20 mb-4 justify-center w-full">
                            <div class="w-1 h-6 bg-gray-900"></div><div class="w-0.5 h-6 bg-gray-900"></div><div class="w-1 h-6 bg-gray-900"></div><div class="w-2 h-6 bg-gray-900"></div><div class="w-0.5 h-6 bg-gray-900"></div><div class="w-1.5 h-6 bg-gray-900"></div><div class="w-1 h-6 bg-gray-900"></div><div class="w-0.5 h-6 bg-gray-900"></div>
                        </div>

                        <div class="text-[10px] md:text-[11px] text-gray-400 font-black uppercase tracking-[0.2em] mb-0.5">Terjual</div>
                        <div class="text-2xl md:text-4xl font-black text-gray-900 tracking-tighter">{{ $item['total_qty'] }}</div>
                        
                        {{-- Expand Chevron --}}
                        <div class="mt-3 md:mt-4 w-7 h-7 md:w-8 md:h-8 rounded-full bg-white shadow-sm border border-gray-200 flex items-center justify-center text-gray-400 transition-all duration-300 z-10" :class="expanded ? 'rotate-180 bg-gray-900 text-white border-gray-900' : ''">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </button>

                {{-- ======================== RECEIPT ACCORDION (Struk Penjualan) ======================== --}}
                <div x-show="expanded" x-collapse x-cloak class="relative z-0">
                    {{-- Fake zig-zag / tuck effect using negative margin --}}
                    <div class="bg-white border-x border-b border-gray-200 rounded-b-2xl mx-4 md:mx-8 pt-8 pb-4 shadow-[0_10px_20px_rgba(0,0,0,0.03)] -mt-4 receipt-bg relative">
                        
                        @if($item['transactions']->count() > 0)
                            <div class="px-5 md:px-8">
                                <h4 class="text-[12px] font-mono font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-dashed border-gray-300 pb-2">
                                    History Transaksi ({{ $item['transactions']->count() }})
                                </h4>
                                
                                <div class="space-y-0">
                                    @foreach($item['transactions'] as $trx)
                                    
                                    @php
                                        $statuses = [
                                            'PAID'       => ['style' => 'text-emerald-600', 'label' => 'LUNAS'],
                                            'SELESAI'    => ['style' => 'text-blue-600', 'label' => 'DONE'],
                                            'DIPROSES'   => ['style' => 'text-yellow-600', 'label' => 'PROSES'],
                                            'PENDING'    => ['style' => 'text-orange-500', 'label' => 'PENDING'],
                                            'DIBATALKAN' => ['style' => 'text-red-500', 'label' => 'BATAL']
                                        ];
                                        $statusData = $statuses[$trx->status] ?? ['style' => 'text-gray-500', 'label' => $trx->status];
                                    @endphp

                                    {{-- Receipt Item Line --}}
                                    <div class="flex flex-col md:flex-row md:items-center justify-between py-3 border-b border-dashed border-gray-200 hover:bg-gray-50/50 transition-colors group px-2 -mx-2 rounded-lg">
                                        
                                        {{-- Left: ID & Buyer --}}
                                        <div class="flex items-center gap-3">
                                            <div class="text-[11px] font-mono text-gray-400 w-16">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</div>
                                            <div>
                                                <div class="text-[13px] md:text-[14px] font-bold text-gray-800">{{ $trx->user->name ?? 'Anonim' }}</div>
                                                <div class="text-[10px] md:text-[11px] text-gray-500 font-mono mt-0.5">
                                                    {{ $trx->created_at->format('d/m/y H:i') }} 
                                                    @if($trx->variant_selected) | <span class="font-bold">{{ $trx->variant_selected }}</span> @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Right: Qty, Price, Status, Actions --}}
                                        <div class="flex items-center justify-between md:justify-end gap-5 mt-2 md:mt-0 pl-16 md:pl-0">
                                            
                                            <div class="text-left md:text-right">
                                                <div class="text-[10px] text-gray-500 font-mono">{{ $trx->qty }}x</div>
                                                <div class="text-[13px] md:text-[14px] font-black text-gray-900">Rp {{ number_format($trx->amount, 0, ',', '.') }}</div>
                                            </div>

                                            <div class="w-16 text-right">
                                                <span class="text-[10px] font-black tracking-wider {{ $statusData['style'] }}">{{ $statusData['label'] }}</span>
                                            </div>

                                            {{-- Mini Actions --}}
                                            <div class="flex items-center gap-1 shrink-0 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                                @if(in_array($trx->status, ['PAID', 'DIPROSES']))
                                                    <form action="{{ route('marketplace.transaction.status', $trx->id) }}" method="POST" class="inline">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="status" value="{{ $trx->status == 'PAID' ? 'DIPROSES' : 'SELESAI' }}">
                                                        <button type="submit" class="w-7 h-7 flex items-center justify-center bg-gray-100 hover:bg-gray-900 text-gray-600 hover:text-white rounded transition-colors tooltip" title="{{ $trx->status == 'PAID' ? 'Proses' : 'Selesaikan' }}">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($trx->whatsapp_number)
                                                    @php $cleanWa = preg_replace('/^0/', '62', $trx->whatsapp_number); @endphp
                                                    <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="w-7 h-7 flex items-center justify-center bg-green-50 hover:bg-green-500 text-green-600 hover:text-white rounded transition-colors" title="WA">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                                                    </a>
                                                @endif

                                                @if(in_array($trx->status, ['DIBATALKAN', 'SELESAI', 'PENDING']))
                                                    <form action="{{ route('marketplace.transaction.destroy', $trx->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus riwayat ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-7 h-7 flex items-center justify-center bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded transition-colors" title="Hapus">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <div class="text-[12px] font-mono text-gray-400">** BLANK RECEIPT **</div>
                                <p class="text-[11px] mt-1 text-gray-400">Belum ada transaksi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm ticket-gradient">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-300">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <h3 class="text-gray-900 font-black text-lg tracking-widest uppercase mb-1">Tiket Kosong</h3>
                <p class="text-[13px] text-gray-500 font-mono">Belum ada data penjualan tercatat.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection