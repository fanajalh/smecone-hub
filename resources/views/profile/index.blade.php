{{-- Sesuaikan 'layouts.app' dengan nama layout utama dashboard kamu --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-6xl mx-auto space-y-10">
        
        {{-- Header Halaman --}}
        <div class="border-b border-gray-200 pb-6 sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Pengaturan Akun
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Kelola profil publik SMECONE HUB dan preferensi keamanan Anda.
                </p>
            </div>
            <div class="mt-4 flex sm:ml-4 sm:mt-0">
                <span class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                    <svg class="mr-1.5 h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    SMECONE Member
                </span>
            </div>
        </div>

        {{-- Toast Notifikasi Sukses --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 shadow-sm ring-1 ring-green-500/20 border-l-4 border-green-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Form Wrapper --}}
        <form action="{{ url('/profile/update') }}" method="POST" class="space-y-12">
            @csrf

            {{-- Bagian 1: Profil Personal --}}
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                
                {{-- Keterangan Sisi Kiri --}}
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Profil Publik</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        Informasi ini akan ditampilkan di dashboard dan saat Anda berkolaborasi dalam repositori.
                    </p>
                </div>

                {{-- Card Form Sisi Kanan --}}
                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-2xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                            {{-- Elemen Avatar UI --}}
                            <div class="col-span-full flex items-center gap-x-8">
                                <div class="h-24 w-24 flex-none rounded-2xl bg-gradient-to-br from-red-500 to-red-700 shadow-inner flex items-center justify-center text-3xl font-bold text-white uppercase tracking-wider">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <button type="button" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                        Ubah Avatar
                                    </button>
                                    <p class="mt-2 text-xs leading-5 text-gray-500">Hanya tampilan UI (JPG, GIF, atau PNG. Maks 1MB).</p>
                                </div>
                            </div>

                            {{-- Input Nama --}}
                            <div class="col-span-full">
                                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                                <div class="mt-2">
                                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                        class="block w-full rounded-lg border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 transition-shadow" required>
                                </div>
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Input Email --}}
                            <div class="col-span-full">
                                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Alamat Email</label>
                                <div class="mt-2">
                                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                        class="block w-full rounded-lg border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 transition-shadow" required>
                                </div>
                                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Keamanan --}}
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 pb-12 md:grid-cols-3">
                
                {{-- Keterangan Sisi Kiri --}}
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Keamanan Akun</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman. Biarkan kosong jika tidak ingin mengubah.
                    </p>
                </div>

                {{-- Card Form Sisi Kanan --}}
                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-2xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            
                            {{-- Input Password Baru --}}
                            <div class="col-span-full">
                                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Kata Sandi Baru</label>
                                <div class="mt-2">
                                    <input type="password" name="password" id="password" placeholder="••••••••"
                                        class="block w-full rounded-lg border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 transition-shadow">
                                </div>
                                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Input Konfirmasi Password --}}
                            <div class="col-span-full">
                                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi Kata Sandi</label>
                                <div class="mt-2">
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                        class="block w-full rounded-lg border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 transition-shadow">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Global (Floating di bawah) --}}
            <div class="mt-6 flex items-center justify-end gap-x-6 border-t border-gray-200 pt-8">
                <button type="reset" class="text-sm font-semibold leading-6 text-gray-700 hover:text-red-600 transition-colors">
                    Batalkan
                </button>
                <button type="submit" 
                    class="rounded-lg bg-red-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-all transform active:scale-95">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection