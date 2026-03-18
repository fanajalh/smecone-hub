@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 md:p-6 max-w-5xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Penjualan</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($sales as $sale)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">ID Transaksi</span>
                    <p class="font-mono font-bold text-gray-700">#TRX-{{ $sale->id }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sale->status == 'PAID' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $sale->status }}
                </span>
            </div>

            <div class="mb-4 flex-grow">
                <h3 class="font-bold text-lg text-gray-800 leading-tight mb-1">{{ $sale->marketplaceItem->title }}</h3>
                <p class="text-emerald-600 font-black text-xl">Rp {{ number_format($sale->amount, 0, ',', '.') }}</p>
            </div>

            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                <p class="text-xs text-gray-500 mb-1">Pembeli:</p>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex-shrink-0"></div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-700">{{ $sale->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $sale->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($sale->whatsapp_number)
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $sale->whatsapp_number) }}" target="_blank" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2.5 rounded-lg flex justify-center items-center transition">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.181-2.592-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.277.042-.615.081-2.028-.452-1.714-.65-2.822-2.398-2.909-2.515-.087-.116-.694-.925-.694-1.765 0-.84.44-1.266.598-1.428.158-.162.347-.203.463-.203.115 0 .23.003.332.008.106.005.25-.043.391.297.144.347.491 1.2.535 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.174.087.289.13.332.202.043.073.043.42-.101.825z"/></svg>
                Chat Pembeli
            </a>
            @else
            <button disabled class="w-full bg-gray-100 text-gray-400 text-sm font-bold py-2.5 rounded-lg cursor-not-allowed">
                No WA Tidak Tersedia
            </button>
            @endif
        </div>
        @empty
        <div class="col-span-full bg-white p-8 text-center rounded-xl border border-gray-100">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <p class="text-gray-500 font-bold">Belum ada barang yang terjual.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection