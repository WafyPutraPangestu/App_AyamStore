{{-- resources/views/layouts/navigation.blade.php (atau file header Anda) --}}

{{-- Pastikan logic $cartItemCount sudah DIPERBAIKI dan BEKERJA dari Langkah 0 --}}
@php
    $cartItemCount = 0;
    if (Auth::check() && Auth::user()->can('user')) {
        // kumpulkan semua cart milik user, lalu hitung total item
        $carts = Auth::user()->keranjangs()->with('items')->get();
        $cartItemCount = $carts->sum(function($cart) {
            return $cart->items->count();
        });
    }
@endphp
<template x-if="cartItemCount > 0">{{ $cartItemCount }}</template>
 {{-- Debugging untuk memastikan $cartItemCount berfungsi --}}
{{-- <div class="text-center mt-4">Cart Item Count: {{ $cartItem

{{-- Header dengan Alpine.js untuk menu mobile dan notif --}}
{{-- Tambahkan @scroll.window dan :class untuk efek saat scroll (opsional) --}}
<header x-data="{ mobileMenuOpen: false, cartItemCount: {{ $cartItemCount }}, scrolled: false }"
        @scroll.window="scrolled = (window.scrollY > 10)" {{-- Deteksi scroll --}}
        :class="{ 'border-b border-gray-200/80 bg-white/95 backdrop-blur-sm': scrolled, 'bg-white': !scrolled }" {{-- Efek saat scroll --}}
        class="sticky top-0 z-50 transition-all duration-300 ease-out"> {{-- Transisi untuk bg/border --}}
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Tingkatkan tinggi header untuk lebih banyak ruang --}}
        <div class="flex justify-between items-center h-18"> {{-- Tinggi ditambah (misal h-18) --}}

            {{-- Logo (Kiri) --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-lg sm:text-xl font-semibold text-gray-800 hover:opacity-75 transition-opacity duration-200">
                    LOGO ANDA
                </a>
            </div>

            {{-- Menu Navigasi Utama (Tengah - Desktop) --}}
            {{-- Beri jarak lebih antar link --}}
            <div class="hidden md:flex md:items-center md:justify-center md:space-x-5 lg:space-x-7 flex-1"> {{-- flex-1 agar menu mengisi ruang & justify-center --}}
                {{-- Guest Links --}}
                @guest
                    <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                    <x-nav-link href="/shop" :active="request()->is('shop')">Shop</x-nav-link> {{-- Contoh link e-commerce --}}
                    <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
                @endguest

                {{-- Admin Links --}}
                @can('admin')
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('admin.input') }}" :active="request()->routeIs('admin.input*')">Input Data Produk</x-nav-link>

                    <x-nav-link href="{{ route('admin.dataProduk') }}" :active="request()->routeIs('admin.dataProduk*')">Produk</x-nav-link>
                    {{-- Tambahkan link admin lain jika perlu --}}
                @endcan

                {{-- User Links --}}
                @can('user')
                    <x-nav-link href="{{ route('user.katalog') }}" :active="request()->routeIs('user.katalog*')">Katalog</x-nav-link>
                    <x-nav-link href="{{ route('user.riwayat') }}" :active="request()->routeIs('user.riwayat*')">Riwayat Transaksi</x-nav-link>
                    <x-nav-link href="{{ route('user.dashboard') }}" :active="request()->routeIs('user.dashboard*')">Akun</x-nav-link> {{-- Contoh: Dashboard jadi Akun --}}
                @endcan
            </div>

            {{-- Aksi (Kanan - Desktop) --}}
            {{-- Kelompokkan ikon di kanan --}}
            <div class="hidden md:flex md:items-center md:space-x-4">

                @can('user')
                    {{-- Icon Keranjang dengan Badge Animasi (Pastikan $cartItemCount valid!) --}}
                    <a href="{{ route('user.keranjang') }}" title="Keranjang Belanja"
                       class="relative p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors duration-200 ease-out">
                        <span class="sr-only">Keranjang Belanja</span>
                        {{-- Icon keranjang (bisa ganti icon jika mau) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>

                        {{-- Badge (Harus berfungsi setelah Langkah 0) --}}
                        <span x-show="cartItemCount > 0"
                              x-transition:enter="transition ease-out duration-200 transform"
                              x-transition:enter-start="opacity-0 scale-50"
                              x-transition:enter-end="opacity-100 scale-100"
                              x-transition:leave="transition ease-in duration-150 transform"
                              x-transition:leave-start="opacity-100 scale-100"
                              x-transition:leave-end="opacity-0 scale-50"
                              x-text="cartItemCount"
                              class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs font-medium text-white shadow"> {{-- Warna badge bisa disesuaikan --}}
                               {{-- Fallback non-JS (opsional) --}}
                               <template x-if="false">{{ $cartItemCount }}</template>
                        </span>
                    </a>
                @endcan

                {{-- Tombol/Link Auth (Ganti dengan icon jika mau) --}}
                @auth
                    {{-- Contoh: Dropdown Akun (Membutuhkan Alpine tambahan atau library dropdown) --}}
                    {{-- <x-dropdown-akun /> --}}
                    {{-- Atau Tombol Logout Sederhana --}}
                     <x-form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors duration-200 ease-out">
                            <span class="sr-only">Logout</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </x-form>
                @endauth

                @guest
                    {{-- Icon Login --}}
                    <a href="{{ route('auth.login') }}" title="Login" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors duration-200 ease-out">
                         <span class="sr-only">Login</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                         </svg>
                    </a>
                    {{-- Tombol Register --}}
                    <a href="{{ route('auth.register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Register
                    </a>
                @endguest
            </div>

            {{-- Tombol Hamburger (Mobile) --}}
            <div class="flex items-center md:hidden">
                 @can('user')
                    {{-- Icon Keranjang Mobile (Wajib berfungsi!) --}}
                    <a href="{{ route('user.keranjang') }}" title="Keranjang Belanja"
                       class="relative mr-2 p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors duration-200 ease-out">
                         <span class="sr-only">Keranjang Belanja</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                         </svg>
                         {{-- Badge Mobile --}}
                         <span x-show="cartItemCount > 0"
                               x-transition:enter="transition ease-out duration-200 transform"
                               x-transition:enter-start="opacity-0 scale-50"
                               x-transition:enter-end="opacity-100 scale-100"
                               x-transition:leave="transition ease-in duration-150 transform"
                               x-transition:leave-start="opacity-100 scale-100"
                               x-transition:leave-end="opacity-0 scale-50"
                               x-text="cartItemCount"
                               class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs font-medium text-white shadow">
                                <template x-if="false">{{ $cartItemCount }}</template>
                         </span>
                    </a>
                @endcan

                {{-- Tombol Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition duration-150 ease-in-out" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()">
                    <span class="sr-only">Buka menu</span>
                    <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    {{-- Panel Menu Mobile (Lebih Bersih) --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden absolute top-full inset-x-0 shadow-lg bg-white ring-1 ring-black ring-opacity-5"
         id="mobile-menu"
         @click.away="mobileMenuOpen = false"
         style="display: none;" {{-- Hindari FOUC --}}
         >
        <div class="px-2 pt-2 pb-3 space-y-1">
            {{-- Style Link Mobile Baru --}}
            @php
            $mobileLinkBase = 'block px-3 py-2 rounded-md text-base font-medium transition-colors duration-150 ease-in-out';
            $mobileLinkActive = 'bg-gray-100 text-gray-900';
            $mobileLinkInactive = 'text-gray-500 hover:bg-gray-50 hover:text-gray-900';
            @endphp

            {{-- Guest Links --}}
            @guest
                <a href="/" class="{{ $mobileLinkBase }} {{ request()->is('/') ? $mobileLinkActive : $mobileLinkInactive }}">Home</a>
                <a href="/shop" class="{{ $mobileLinkBase }} {{ request()->is('shop') ? $mobileLinkActive : $mobileLinkInactive }}">Shop</a>
                <a href="/about" class="{{ $mobileLinkBase }} {{ request()->is('about') ? $mobileLinkActive : $mobileLinkInactive }}">About</a>
            @endguest

            {{-- Admin Links --}}
             @can('admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('admin.dashboard*') ? $mobileLinkActive : $mobileLinkInactive }}">Dashboard</a>
                <a href="{{ route('admin.dataProduk') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('admin.dataProduk*') ? $mobileLinkActive : $mobileLinkInactive }}">Produk</a>
                 {{-- Tambahkan link admin lain jika perlu --}}
            @endcan

            {{-- User Links --}}
            @can('user')
                <a href="{{ route('user.katalog') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.katalog*') ? $mobileLinkActive : $mobileLinkInactive }}">Katalog</a>
                <a href="{{ route('user.riwayat') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.riwayat*') ? $mobileLinkActive : $mobileLinkInactive }}">Pesanan</a>
                <a href="{{ route('user.dashboard') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.dashboard*') ? $mobileLinkActive : $mobileLinkInactive }}">Akun</a>
                 {{-- Link Keranjang Mobile dengan count (jika berfungsi) --}}
                 <a href="{{ route('user.keranjang') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.keranjang') ? $mobileLinkActive : $mobileLinkInactive }} flex justify-between items-center">
                    <span>Keranjang</span>
                    <span x-show="cartItemCount > 0" x-text="cartItemCount" class="bg-indigo-100 text-indigo-600 text-xs font-medium px-2 py-0.5 rounded-full"></span>
                     <template x-if="false"><span class="bg-indigo-100 text-indigo-600 text-xs font-medium px-2 py-0.5 rounded-full">{{$cartItemCount}}</span></template> {{-- Fallback --}}
                </a>
            @endcan

        </div>
         {{-- Auth Links Mobile --}}
        <div class="pt-4 pb-3 border-t border-gray-200">
             @auth
                <div class="px-2">
                    <x-form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150 ease-in-out">
                            Logout
                        </button>
                    </x-form>
                </div>
            @endauth
            @guest
                <div class="px-2 space-y-1">
                    <a href="{{ route('auth.login') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('auth.login') ? $mobileLinkActive : $mobileLinkInactive }}">Login</a>
                    <a href="{{ route('auth.register') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('auth.register') ? $mobileLinkActive : $mobileLinkInactive }} bg-gray-800 text-white hover:bg-gray-700 text-center">Register</a> {{-- Tombol Register Mobile --}}
                </div>
            @endguest
        </div>
    </div>
</header>

{{-- Pastikan untuk menambahkan Alpine.js di bagian bawah body --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
{{-- Atau jika menggunakan CDN --}}
{{-- <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}