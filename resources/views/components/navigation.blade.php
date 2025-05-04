{{-- resources/views/layouts/navigation.blade.php --}}

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

{{-- Header dengan Alpine.js untuk menu mobile dan notif --}}
<header x-data="{ mobileMenuOpen: false, cartItemCount: {{ $cartItemCount }}, scrolled: false }"
        @scroll.window="scrolled = (window.scrollY > 10)"
        :class="{ 'border-b border-gray-200 bg-white shadow-sm': scrolled, 'bg-white': !scrolled }"
        class="sticky top-0 z-50 transition-all duration-300">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex-shrink-0 flex items-center">
                @can('user')
                <a href="{{ route('user.profile') }}" title="Profile" 
                class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                 <span class="sr-only">Profile</span>
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                 </svg>
             </a>
                @endcan
                <a href="/" class="flex items-center text-lg sm:text-xl font-semibold text-indigo-600 hover:text-indigo-800 transition-colors duration-200">    
                </a>
            </div>
            <div class="hidden md:flex md:items-center md:justify-center md:space-x-6 flex-1">
                {{-- Guest Links --}}
                @guest
                    <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                    <x-nav-link href="/shop" :active="request()->is('shop')">Shop</x-nav-link>
                    <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
                @endguest

                {{-- Admin Links --}}
                @can('admin')
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('admin.input') }}" :active="request()->routeIs('admin.input*')">Input Data Produk</x-nav-link>
                    <x-nav-link href="{{ route('admin.dataProduk') }}" :active="request()->routeIs('admin.dataProduk*')">Produk</x-nav-link>
                @endcan

                @can('kurir')
                    <x-nav-link href="{{ route('kurir.tugas') }}" :active="request()->routeIs('kurir.tugas*')">Tugas Kurir</x-nav-link>
                    <x-nav-link href="{{ route('kurir.manajemen') }}" :active="request()->routeIs('kurir.manajemen*')">Manajemen Tugas</x-nav-link>
                    <x-nav-link href="{{ route('kurir.riwayat') }}" :active="request()->routeIs('kurir.riwayat*')">Riwayat</x-nav-link>
                @endcan

                {{-- User Links --}}
                @can('user')
                    <x-nav-link href="{{ route('user.katalog') }}" :active="request()->routeIs('user.katalog*')">Katalog</x-nav-link>
                    <x-nav-link href="{{ route('user.riwayat') }}" :active="request()->routeIs('user.riwayat*')">Riwayat Pembelian</x-nav-link>
                    <x-nav-link href="{{ route('user.pengiriman') }}" :active="request()->routeIs('user.pengiriman*')">Tracking Pengiriman</x-nav-link>
                @endcan
            </div>

            {{-- Aksi (Kanan - Desktop) --}}
            <div class="hidden md:flex md:items-center md:space-x-4">
                {{-- Profile link untuk semua user yang sudah login --}}
                @can('kurir')
                    {{-- Icon Profile --}}
                    <a href="{{ route('kurir.profile') }}" title="Profile" 
                       class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                        <span class="sr-only">Profile</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </a>
                @endcan
                
                @can('user')
                    {{-- Icon Keranjang dengan Badge Animasi --}}
                    <a href="{{ route('user.keranjang') }}" title="Keranjang Belanja"
                       class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                        <span class="sr-only">Keranjang Belanja</span>
                        {{-- Icon keranjang --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>

                        {{-- Badge --}}
                        <span x-show="cartItemCount > 0"
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0 scale-50"
                              x-transition:enter-end="opacity-100 scale-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100 scale-100"
                              x-transition:leave-end="opacity-0 scale-50"
                              x-text="cartItemCount"
                              class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs font-medium text-white">
                               <template x-if="false">{{ $cartItemCount }}</template>
                        </span>
                    </a>
                @endcan

                {{-- Tombol/Link Auth --}}
                @auth
                     <x-form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-gray-600 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors duration-200 cursor-pointer">
                            {{-- Icon Logout --}}
                            <span class="sr-only">Logout</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </x-form>
                @endauth

                @guest
                    {{-- Icon Login --}}
                    <a href="{{ route('auth.login') }}" title="Login" class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                         <span class="sr-only">Login</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                         </svg>
                    </a>
                    {{-- Tombol Register --}}
                    <a href="{{ route('auth.register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Register
                    </a>
                @endguest
            </div>

            {{-- Tombol Hamburger (Mobile) --}}
            <div class="flex items-center md:hidden">
                 @can('user')
                    {{-- Icon Keranjang Mobile --}}
                    <a href="{{ route('user.keranjang') }}" title="Keranjang Belanja"
                       class="relative mr-2 p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                         <span class="sr-only">Keranjang Belanja</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                         </svg>
                         {{-- Badge Mobile --}}
                         <span x-show="cartItemCount > 0"
                               x-transition:enter="transition ease-out duration-200"
                               x-transition:enter-start="opacity-0 scale-50"
                               x-transition:enter-end="opacity-100 scale-100"
                               x-transition:leave="transition ease-in duration-150"
                               x-transition:leave-start="opacity-100 scale-100"
                               x-transition:leave-end="opacity-0 scale-50"
                               x-text="cartItemCount"
                               class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs font-medium text-white">
                                <template x-if="false"><span>{{$cartItemCount}}</span></template>
                         </span>
                    </a>
                @endcan

                {{-- Icon Profile Mobile jika sudah login --}}
                @auth
                    <a href="{{ route('kurir.profile') }}" title="Profile"
                       class="relative mr-2 p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors duration-200">
                         <span class="sr-only">Profile</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                         </svg>
                    </a>
                @endauth

                {{-- Tombol Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()">
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

    {{-- Panel Menu Mobile --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden absolute top-full inset-x-0 shadow-lg bg-white"
         id="mobile-menu"
         @click.away="mobileMenuOpen = false"
         style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            {{-- Style Link Mobile Baru --}}
            @php
            $mobileLinkBase = 'block px-3 py-2 rounded-md text-base font-medium transition-colors duration-150 ease-in-out';
            $mobileLinkActive = 'bg-indigo-50 text-indigo-600';
            $mobileLinkInactive = 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600';
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
            @endcan

            @can('kurir')
            <a href="{{ route('kurir.tugas') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('kurir.tugas*') ? $mobileLinkActive : $mobileLinkInactive }}">Tugas Kurir</a>
            <a href="{{ route('kurir.manajemen') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('kurir.manajemen*') ? $mobileLinkActive : $mobileLinkInactive }}">Manajemen Kurir</a>
            {{-- Profile link untuk kurir mobile --}}
            <a href="{{ route('kurir.profile') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('kurir.profile*') ? $mobileLinkActive : $mobileLinkInactive }}">Profile</a>
            @endcan

            {{-- User Links --}}
            @can('user')
                <a href="{{ route('user.katalog') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.katalog*') ? $mobileLinkActive : $mobileLinkInactive }}">Katalog</a>
                <a href="{{ route('user.riwayat') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.riwayat*') ? $mobileLinkActive : $mobileLinkInactive }}">Pesanan</a>
                <a href="{{ route('user.dashboard') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.dashboard*') ? $mobileLinkActive : $mobileLinkInactive }}">Akun</a>
                 {{-- Link Keranjang Mobile dengan count --}}
                 <a href="{{ route('user.keranjang') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.keranjang') ? $mobileLinkActive : $mobileLinkInactive }} flex justify-between items-center">
                    <span>Keranjang</span>
                    <span x-show="cartItemCount > 0" x-text="cartItemCount" class="bg-indigo-100 text-indigo-600 text-xs font-medium px-2 py-0.5 rounded-full"></span>
                     <template x-if="false"><span class="bg-indigo-100 text-indigo-600 text-xs font-medium px-2 py-0.5 rounded-full">{{$cartItemCount}}</span></template>
                </a>
                {{-- Profile link untuk user mobile --}}
                <a href="{{ route('user.profile') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('user.profile*') ? $mobileLinkActive : $mobileLinkInactive }}">Profile</a>
            @endcan

        </div>
         {{-- Auth Links Mobile --}}
        <div class="pt-4 pb-3 border-t border-gray-200">
             @auth
                <div class="px-2">
                    <x-form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-red-500 transition-colors duration-150">
                            Logout
                        </button>
                    </x-form>
                </div>
            @endauth
            @guest
                <div class="px-2 space-y-1">
                    <a href="{{ route('auth.login') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('auth.login') ? $mobileLinkActive : $mobileLinkInactive }}">Login</a>
                    <a href="{{ route('auth.register') }}" class="{{ $mobileLinkBase }} {{ request()->routeIs('auth.register') ? $mobileLinkActive : $mobileLinkInactive }} bg-indigo-600 text-white hover:bg-indigo-700 text-center">Register</a>
                </div>
            @endguest
        </div>
    </div>
</header>

{{-- Pastikan untuk menambahkan Alpine.js di bagian bawah body --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>