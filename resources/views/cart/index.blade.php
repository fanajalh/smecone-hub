@extends('layouts.app')
@section('title', '| Keranjang Belanja')

@section('content')
<style>
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s; }
    [x-cloak] { display: none !important; }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div x-data="cartPage()" class="bg-[#f8f9fa] min-h-screen pb-28 md:pb-12">

    {{-- ===== HEADER ===== --}}
    <div class="bg-[#E21F26] w-full pt-10 pb-28 rounded-b-[2.5rem] relative overflow-hidden shadow-md">
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-10 w-32 h-32 bg-black/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative max-w-3xl mx-auto px-5 z-10 flex items-center justify-between">
            <div class="text-white">
                <a href="/marketplace" class="flex items-center gap-2 text-white/80 hover:text-white text-sm font-bold mb-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali Belanja
                </a>
                <h1 class="text-2xl font-extrabold drop-shadow-sm">Keranjang Belanja 🛒</h1>
                <p class="text-red-100 text-sm font-medium mt-1" x-text="cartCount + ' item dalam keranjangmu'"></p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="max-w-3xl mx-auto px-4 -mt-20 relative z-10">
        
        {{-- Loading state --}}
        <div x-show="loading" class="flex flex-col gap-4">
            @foreach(range(1,3) as $i)
            <div class="bg-white rounded-3xl p-4 flex gap-4 animate-pulse">
                <div class="w-24 h-24 bg-gray-200 rounded-2xl shrink-0"></div>
                <div class="flex-grow">
                    <div class="h-4 bg-gray-200 rounded-full w-3/4 mb-3"></div>
                    <div class="h-4 bg-gray-200 rounded-full w-1/2 mb-5"></div>
                    <div class="h-8 bg-gray-100 rounded-xl w-1/3"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        <div x-show="!loading && cartItems.length === 0" x-cloak class="bg-white rounded-3xl p-10 text-center shadow-sm border border-gray-100 mt-4">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border-2 border-dashed border-gray-200">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Keranjang Kosong</h3>
            <p class="text-gray-500 text-sm font-medium mb-6 max-w-xs mx-auto">Belum ada barang yang kamu tambahkan. Yuk tambah dulu!</p>
            <a href="/marketplace" class="inline-flex items-center gap-2 bg-[#E21F26] text-white font-bold px-8 py-3.5 rounded-2xl shadow-[0_4px_15px_rgba(226,31,38,0.25)] hover:bg-red-700 hover:-translate-y-0.5 transition-all tap-effect text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Eksplor Marketplace
            </a>
        </div>

        {{-- Cart Items --}}
        <div x-show="!loading && cartItems.length > 0" x-cloak class="flex flex-col gap-4">

            {{-- Item Cards --}}
            <template x-for="item in cartItems" :key="item.id">
                <div class="bg-white rounded-3xl p-4 flex gap-4 shadow-sm border border-gray-100 hover:border-red-100 hover:shadow-md transition-all duration-200">
                    
                    {{-- Gambar --}}
                    <a :href="'/marketplace/' + item.product_id" class="shrink-0">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden bg-gray-100">
                            <img :src="item.image || 'https://via.placeholder.com/96'" :alt="item.name" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </div>
                    </a>

                    {{-- Detail --}}
                    <div class="flex-grow min-w-0 flex flex-col">
                        <div class="flex items-start justify-between gap-2">
                            <a :href="'/marketplace/' + item.product_id">
                                <h3 class="text-[14px] md:text-[15px] font-bold text-gray-900 line-clamp-2 leading-tight hover:text-[#E21F26] transition-colors" x-text="item.name"></h3>
                            </a>
                            <button @click="removeItem(item)" class="shrink-0 w-8 h-8 rounded-xl bg-gray-50 hover:bg-red-50 flex items-center justify-center text-gray-400 hover:text-[#E21F26] transition-colors tap-effect">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="flex items-center gap-2 mt-1 mb-3">
                            <div class="w-4 h-4 rounded-full bg-gray-100 flex items-center justify-center text-[8px] font-bold text-gray-600" x-text="item.seller ? item.seller.charAt(0) : '?'"></div>
                            <span class="text-[11px] text-gray-500 font-medium" x-text="item.seller || 'Penjual'"></span>
                            <span x-show="item.is_sold" class="text-[9px] font-black bg-red-50 text-red-500 px-1.5 py-0.5 rounded-md uppercase">Habis</span>
                        </div>

                        <div class="mt-auto flex items-center justify-between">
                            {{-- Qty Control --}}
                            <div class="flex items-center bg-gray-50 border border-gray-100 rounded-xl p-1 gap-1">
                                <button @click="updateQty(item, item.qty - 1)" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-600 hover:text-[#E21F26] hover:bg-red-50 transition-colors tap-effect">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                                </button>
                                <span class="w-8 text-center text-sm font-extrabold text-gray-900" x-text="item.qty"></span>
                                <button @click="updateQty(item, item.qty + 1)" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-600 hover:text-[#E21F26] hover:bg-red-50 transition-colors tap-effect">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>

                            {{-- Subtotal --}}
                            <div class="text-right">
                                <p class="text-[11px] text-gray-400 font-medium" x-text="'Rp ' + Number(item.price).toLocaleString('id-ID') + ' × ' + item.qty"></p>
                                <p class="text-[16px] md:text-[18px] font-black text-[#E21F26]" x-text="'Rp ' + (item.price * item.qty).toLocaleString('id-ID')"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Order Summary Card --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mt-2">
                <h3 class="text-base font-extrabold text-gray-900 mb-5">Ringkasan Pesanan</h3>
                
                <div class="flex flex-col gap-3 mb-5">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 font-medium">Total Item</span>
                        <span class="text-sm font-bold text-gray-800" x-text="cartCount + ' item'"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 font-medium">Subtotal Produk</span>
                        <span class="text-sm font-bold text-gray-800" x-text="'Rp ' + Number(cartTotal).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="border-t border-dashed border-gray-100 pt-3 flex justify-between items-center">
                        <span class="text-base font-extrabold text-gray-900">Total Belanja</span>
                        <span class="text-xl font-black text-[#E21F26]" x-text="'Rp ' + Number(cartTotal).toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <div class="bg-red-50 rounded-2xl p-3.5 mb-5 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#E21F26] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-red-700 font-medium leading-relaxed">Checkout dilakukan per-item. Klik tombol <strong>"Beli Sekarang"</strong> di halaman detail produk untuk melanjutkan pembayaran.</p>
                </div>

                <a href="/marketplace" class="w-full flex items-center justify-center gap-2 bg-[#E21F26] text-white font-extrabold py-4 rounded-2xl text-sm shadow-[0_4px_15px_rgba(226,31,38,0.25)] hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all tap-effect">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Lanjut Belanja
                </a>
                <button @click="clearCart()" class="w-full mt-3 text-sm font-bold text-gray-400 hover:text-[#E21F26] py-3 rounded-2xl hover:bg-red-50 transition-colors tap-effect">
                    Kosongkan Keranjang
                </button>
            </div>
            
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toast.show" x-cloak
         class="fixed bottom-24 md:bottom-8 left-1/2 -translate-x-1/2 z-[200] bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-xl text-sm font-bold flex items-center gap-3 whitespace-nowrap"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <svg class="w-5 h-5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        <span x-text="toast.message"></span>
    </div>

</div>

<script>
function cartPage() {
    return {
        loading: true,
        cartItems: [],
        cartCount: 0,
        cartTotal: 0,
        toast: { show: false, message: '' },

        init() {
            this.fetchCart();
        },

        getCsrf() {
            return document.querySelector('meta[name="csrf-token"]').content;
        },

        async fetchCart() {
            this.loading = true;
            try {
                const res = await fetch('/cart', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                this.cartItems = data.items || [];
                this.cartCount = data.count || 0;
                this.cartTotal = data.total || 0;
            } catch(e) {
                console.error('Gagal load cart:', e);
                this.cartItems = [];
            } finally {
                this.loading = false;
            }
        },

        async updateQty(item, newQty) {
            if (newQty < 1) { this.removeItem(item); return; }
            try {
                await fetch('/cart/' + item.id + '/qty', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ qty: newQty })
                });
                item.qty = newQty;
                this.recalc();
            } catch(e) { console.error(e); }
        },

        async removeItem(item) {
            try {
                const res = await fetch('/cart/' + item.id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.cartItems = this.cartItems.filter(i => i.id !== item.id);
                this.cartCount = data.count;
                this.recalc();
                this.showToast('Item dihapus dari keranjang');
            } catch(e) { console.error(e); }
        },

        async clearCart() {
            if (!confirm('Yakin ingin mengosongkan semua item?')) return;
            try {
                await fetch('/cart', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' }
                });
                this.cartItems = [];
                this.cartCount = 0;
                this.cartTotal = 0;
                this.showToast('Keranjang dikosongkan');
            } catch(e) { console.error(e); }
        },

        recalc() {
            this.cartTotal = this.cartItems.reduce((s, i) => s + i.price * i.qty, 0);
            this.cartCount = this.cartItems.reduce((s, i) => s + i.qty, 0);
        },

        showToast(message) {
            this.toast = { show: true, message };
            setTimeout(() => { this.toast.show = false; }, 2500);
        }
    };
}
</script>
@endsection
