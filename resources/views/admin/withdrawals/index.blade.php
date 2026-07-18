@extends('layouts.admin')

@section('title', 'Persetujuan Penarikan Dana')

@section('content')
<div class="max-w-6xl mx-auto px-4 lg:px-8 py-8 relative z-20">
    <div class="mb-6">
        <a href="/admin/dashboard" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-red-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dasbor
        </a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Acc Penarikan Dana</h1>
            <p class="text-gray-500 mt-1">Kelola permohonan penarikan dana dari lapak penjual.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50/50 backdrop-blur-sm border border-green-200 rounded-2xl flex items-center gap-3">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg shadow-green-500/20">
                <ion-icon name="checkmark"></ion-icon>
            </div>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50/50 backdrop-blur-sm border border-red-200 rounded-2xl flex items-center gap-3">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-lg shadow-red-500/20">
                <ion-icon name="close"></ion-icon>
            </div>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl border border-gray-100 rounded-3xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold border-b border-gray-100">Tanggal</th>
                        <th class="px-6 py-4 font-bold border-b border-gray-100">Penjual</th>
                        <th class="px-6 py-4 font-bold border-b border-gray-100">Nominal</th>
                        <th class="px-6 py-4 font-bold border-b border-gray-100">Tujuan Rekening</th>
                        <th class="px-6 py-4 font-bold border-b border-gray-100">Status</th>
                        <th class="px-6 py-4 font-bold border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $w->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $w->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $w->user->nis ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-[#E21F26]">Rp {{ number_format($w->amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold text-gray-800">{{ $w->bank_name }}</div>
                                <div class="text-gray-600">{{ $w->account_number }}</div>
                                <div class="text-xs text-gray-400">a.n. {{ $w->account_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($w->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold border border-yellow-200">Pending</span>
                                @elseif($w->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-200">Disetujui</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold border border-red-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($w->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dana sudah ditransfer ke rekening tersebut?');">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-xl text-xs font-bold hover:bg-green-600 transition-colors shadow-md shadow-green-500/20">
                                                Acc
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan ini? Saldo akan dikembalikan ke penjual.');">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-red-50 hover:text-red-600 hover:border-red-200 border border-transparent transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <ion-icon name="wallet-outline" class="text-4xl text-gray-300 mb-2"></ion-icon>
                                <p>Belum ada permohonan penarikan dana.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
