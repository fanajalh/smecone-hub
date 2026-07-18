@extends('layouts.app')
@section('title', '| Keranjang Belanja')

@section('content')
<style>
    .tap-effect:active { transform: scale(0.97); transition: transform 0.1s; }
    [x-cloak] { display: none !important; }
    /* Checkbox kustom merah */
    .custom-checkbox { accent-color: #E21F26; width: 1.25rem; height: 1.25rem; cursor: pointer; }
    /* Area aman untuk layar iPhone (notch bawah) */
    .pb-safe { padding-bottom: env(safe-area-inset-bottom, 1rem); }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div x-data="cartPage()" class="bg-gray-50 min-h-screen pb-40 md:pb-24 font-sans text-gray-800">

    {{-- ===== HEADER ===== --}}
    <div class="bg-[#E21F26] w-full pt-8 lg:pt-40 pb-20 md:pb-24 rounded-b-[2.5rem] relative overflow-hidden shadow-lg">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/4"></div>
        <div class="relative max-w-3xl mx-auto px-5 z-10">
            <a href="/marketplace" class="inline-flex items-center gap-2 text-white/90 hover:text-white text-sm font-semibold mb-4 transition-colors p-1 -ml-1 tap-effect">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Lanjut Belanja
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight drop-shadow-sm">Keranjang 🛒</h1>
                    <p class="text-red-100 text-sm md:text-base font-medium mt-1" x-text="cartCount + ' Barang di Keranjang'"></p>
                </div>
                <button @click="clearCart()" x-show="cartItems.length > 0" x-cloak class="text-xs font-bold text-red-100 bg-red-800/40 hover:bg-red-800/60 px-3 py-1.5 rounded-xl backdrop-blur-sm transition tap-effect">
                    Kosongkan
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="max-w-3xl mx-auto px-4 -mt-12 relative z-10">
        
        {{-- Loading Skeleton --}}
        <div x-show="loading" class="flex flex-col gap-4">
            @foreach(range(1,3) as $i)
            <div class="bg-white rounded-[1.5rem] p-4 flex gap-4 animate-pulse shadow-sm border border-gray-100">
                <div class="w-6 h-6 bg-gray-200 rounded shrink-0 self-center"></div>
                <div class="w-24 h-24 bg-gray-200 rounded-2xl shrink-0"></div>
                <div class="flex-grow py-1">
                    <div class="h-4 bg-gray-200 rounded-full w-3/4 mb-3"></div>
                    <div class="h-4 bg-gray-200 rounded-full w-1/2 mb-5"></div>
                    <div class="h-8 bg-gray-100 rounded-xl w-1/3"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        <div x-show="!loading && cartItems.length === 0" x-cloak class="bg-white rounded-[2rem] p-10 text-center shadow-sm border border-gray-100 mt-4">
            <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-[3px] border-dashed border-gray-200">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Keranjang Kosong</h3>
            <p class="text-gray-500 text-sm font-medium mb-8 max-w-[250px] mx-auto">Yuk cari barang menarik di Smecone Mart sekarang!</p>
            <a href="/marketplace" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3.5 bg-gray-900 text-white font-bold rounded-2xl shadow-md hover:bg-gray-800 transition-all tap-effect text-[15px]">
                Mulai Belanja
            </a>
        </div>

        {{-- Cart Items Wrapper --}}
        <div x-show="!loading && cartItems.length > 0" x-cloak class="flex flex-col gap-3">
            
            <template x-for="item in cartItems" :key="item.id">
                <div class="bg-white rounded-[1.5rem] p-4 flex gap-3 shadow-[0_2px_10px_rgba(0,0,0,0.02)] border border-gray-100 hover:border-red-100 transition-all group">
                    
                    {{-- Checkbox Selector (FIXED BUG) --}}
                    <div class="flex items-center justify-center shrink-0 w-6">
                        <input type="checkbox" 
                               :checked="selectedItems.some(id => id == item.id)"
                               @change="$event.target.checked ? selectedItems.push(item.id) : selectedItems = selectedItems.filter(id => id != item.id)"
                               class="custom-checkbox rounded border-gray-300">
                    </div>

                    {{-- Foto Produk --}}
                    <a :href="'/marketplace/' + item.product_id" class="shrink-0 relative block">
                        <div class="w-[88px] h-[88px] md:w-24 md:h-24 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100">
                            <img :src="item.image || 'https://via.placeholder.com/96'" class="w-full h-full object-cover">
                        </div>
                        <span x-show="item.is_sold" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] rounded-2xl flex items-center justify-center z-10">
                            <span class="bg-gray-900 text-white text-[9px] font-black px-2 py-1 rounded uppercase tracking-widest shadow-md">Habis</span>
                        </span>
                    </a>

                    {{-- Detail Produk --}}
                    <div class="flex-grow min-w-0 flex flex-col justify-between py-0.5">
                        
                        {{-- Nama & Hapus --}}
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a :href="'/marketplace/' + item.product_id" class="block">
                                    <h3 class="text-[14px] md:text-[15px] font-bold text-gray-900 line-clamp-2 leading-tight group-hover:text-[#E21F26] transition-colors" x-text="item.name"></h3>
                                </a>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
                                    <span class="text-[11px] md:text-[12px] text-gray-500 font-medium truncate" x-text="item.seller || 'Penjual Smecone'"></span>
                                </div>
                            </div>
                            <button @click="removeItem(item)" class="shrink-0 text-gray-300 hover:text-red-500 transition-colors p-1 tap-effect" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>

                        {{-- Harga & Kuantitas --}}
                        <div class="flex items-end justify-between mt-3">
                            <div class="text-[15px] md:text-[16px] font-black text-[#E21F26]" x-text="'Rp' + Number(item.price).toLocaleString('id-ID')"></div>
                            
                            {{-- Qty Control --}}
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm h-8" :class="item.is_sold ? 'opacity-50 pointer-events-none' : ''">
                                <button @click="updateQty(item, item.qty - 1)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-50 active:bg-gray-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 12H4"></path></svg>
                                </button>
                                <div class="w-9 h-full flex items-center justify-center text-[13px] font-bold border-x border-gray-200 bg-gray-50" x-text="item.qty"></div>
                                <button @click="updateQty(item, item.qty + 1)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-50 active:bg-gray-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ===== STICKY CHECKOUT BAR ===== --}}
    <div x-show="!loading && cartItems.length > 0" x-cloak 
         class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-[0_-8px_30px_rgba(0,0,0,0.04)] z-50 md:max-w-3xl md:mx-auto md:bottom-4 md:rounded-2xl pb-safe md:pb-0 transition-transform duration-300">
        <div class="flex items-center justify-between px-4 h-16 md:h-20">
            
            {{-- Master Checkbox --}}
            <label class="flex items-center gap-2.5 cursor-pointer py-2 tap-effect">
                <input type="checkbox" :checked="isAllSelected" @change="toggleAll($event)" class="custom-checkbox rounded border-gray-300">
                <span class="text-[13px] font-bold text-gray-700">Semua</span>
            </label>

            <div class="flex items-center gap-4">
                {{-- Total Price --}}
                <div class="text-right flex flex-col justify-center">
                    <span class="text-[11px] font-medium text-gray-500">Total Belanja</span>
                    <span class="text-[17px] font-black text-[#E21F26] leading-none mt-0.5" x-text="'Rp ' + selectedTotal.toLocaleString('id-ID')"></span>
                </div>

                {{-- Checkout Button --}}
                <button @click="checkoutSelected()" 
                        class="h-10 md:h-11 px-6 bg-[#E21F26] text-white rounded-xl font-bold text-[14px] shadow-[0_4px_12px_rgba(226,31,38,0.25)] hover:bg-red-700 active:scale-95 transition flex items-center justify-center min-w-[120px]"
                        :class="selectedCount === 0 ? 'opacity-50 grayscale' : ''">
                    Checkout (<span x-text="selectedCount"></span>)
                </button>
                
            </div>
        </div>
    </div>

    {{-- Toast Notifikasi --}}
    <div x-show="toast.show" x-cloak
         class="fixed top-10 left-1/2 -translate-x-1/2 z-[200] bg-gray-900/95 backdrop-blur-md text-white px-5 py-3.5 rounded-2xl shadow-xl text-[13px] font-bold flex items-center gap-3 whitespace-nowrap"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span x-text="toast.message"></span>
    </div>

</div>

<script>
function cartPage() {
    return {
        loading: true,
        cartItems: [],
        selectedItems: [],
        toast: { show: false, message: '' },

        init() {
            this.fetchCart();
        },

        getCsrf() { return document.querySelector('meta[name="csrf-token"]').content; },

        get cartCount() {
            return this.cartItems.reduce((sum, item) => sum + item.qty, 0);
        },

        get cartTotal() {
            return this.cartItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        // FIXED: Memakai .some(id => id == i.id) agar kebal dari Bug Type Mismatch
        get selectedTotal() {
            return this.cartItems
                .filter(i => this.selectedItems.some(id => id == i.id))
                .reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        get selectedCount() {
            return this.cartItems
                .filter(i => this.selectedItems.some(id => id == i.id))
                .reduce((sum, item) => sum + item.qty, 0);
        },

        toggleAll(event) {
            if (event.target.checked) {
                this.selectedItems = this.cartItems.filter(i => !i.is_sold).map(i => i.id);
            } else {
                this.selectedItems = [];
            }
        },

        get isAllSelected() {
            const availableItems = this.cartItems.filter(i => !i.is_sold);
            return availableItems.length > 0 && this.selectedItems.length === availableItems.length;
        },

        async fetchCart() {
            this.loading = true;
            try {
                const url = '/cart?t=' + new Date().getTime();
                const res = await fetch(url, { 
                    headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache' } 
                });
                const data = await res.json();
                this.cartItems = data.items || [];
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async updateQty(item, newQty) {
            if (newQty < 1 || item.is_sold) return;
            try {
                const res = await fetch('/cart/' + item.id + '/qty', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ qty: newQty })
                });
                if(!res.ok) {
                    const data = await res.json();
                    this.showToast(data.message || 'Gagal mengubah jumlah');
                    return;
                }
                item.qty = newQty;
            } catch(e) { console.error(e); }
        },

        async removeItem(item) {
            const result = await Swal.fire({
                title: 'Hapus Barang?',
                text: "Barang ini akan dihapus dari keranjang belanja",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[28px] shadow-2xl border border-gray-100 p-6',
                    title: 'font-black text-2xl tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-sm font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-red-200 active:scale-95 transition-transform mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-200 active:scale-95 transition-transform mx-2'
                }
            });
            if (!result.isConfirmed) return;

            try {
                await fetch('/cart/' + item.id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' }
                });
                this.cartItems = this.cartItems.filter(i => i.id !== item.id);
                // FIXED: Memakai != agar Type Teks dan Angka tetap terdeteksi
                this.selectedItems = this.selectedItems.filter(id => id != item.id);
                this.showToast('Barang dihapus');
            } catch(e) { console.error(e); }
        },

        async clearCart() {
            const result = await Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: "Semua barang di keranjang belanja Anda akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Kosongkan!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[28px] shadow-2xl border border-gray-100 p-6',
                    title: 'font-black text-2xl tracking-tight text-gray-900 mt-2',
                    htmlContainer: 'text-sm font-medium text-gray-500 mt-2',
                    confirmButton: 'bg-red-600 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-red-200 active:scale-95 transition-transform mx-2',
                    cancelButton: 'bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-200 active:scale-95 transition-transform mx-2'
                }
            });
            if (!result.isConfirmed) return;

            try {
                await fetch('/cart', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.getCsrf(), 'Accept': 'application/json' }
                });
                this.cartItems = [];
                this.selectedItems = [];
                this.showToast('Keranjang berhasil dikosongkan');
            } catch(e) { console.error(e); }
        },

        checkoutSelected() {
            if(this.selectedItems.length === 0) {
                this.showToast('Pilih minimal 1 barang dulu ya!');
                return;
            }
            if(this.selectedItems.length > 1) {
                this.showToast('Maaf, saat ini checkout hanya bisa satu per satu jenis barang dari keranjang.');
                return;
            }
            
            const selectedCartId = this.selectedItems[0];
            const cartItem = this.cartItems.find(i => i.id == selectedCartId);

            let checkoutUrl = '/marketplace/' + cartItem.product_id + '/checkout?qty=' + cartItem.qty;
            if (cartItem.variant) {
                checkoutUrl += '&variant=' + encodeURIComponent(cartItem.variant);
            }
            window.location.href = checkoutUrl;
        },

        showToast(message) {
            this.toast = { show: true, message };
            setTimeout(() => { this.toast.show = false; }, 3000);
        }
    };
}
</script>
@endsection