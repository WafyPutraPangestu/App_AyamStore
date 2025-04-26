<header x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16"> {{-- Tinggi standar navigasi --}}

            {{-- Logo (Kiri) - Tidak berubah --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-xl sm:text-2xl font-bold text-indigo-700 hover:text-indigo-800 transition duration-150 ease-in-out">
                    LOGO ANDA
                </a>
            </div>

            {{-- Menu Navigasi Utama (Tengah - Desktop) - Tidak berubah --}}
            <div class="hidden md:flex md:ml-6 md:space-x-2 lg:space-x-4">
                @guest
                    <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                    <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
                    <x-nav-link href="/contact" :active="request()->is('contact')">Contact</x-nav-link>
                @endguest

                @can('admin')
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('admin.input') }}" :active="request()->routeIs('admin.input')">Input Produk</x-nav-link>
                    <x-nav-link href="{{ route('admin.dataProduk') }}" :active="request()->routeIs('admin.dataProduk')">Data Produk</x-nav-link>
                    <x-nav-link href="{{ route('admin.manajemen') }}" :active="request()->routeIs('admin.manajemen')">Manajemen</x-nav-link>
                @endcan

                @can('user')
                    <x-nav-link href="{{ route('user.dashboard') }}" :active="request()->routeIs('user.dashboard')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('user.katalog') }}" :active="request()->routeIs('user.katalog')">Katalog Produk</x-nav-link>
                     {{-- The cart link with badge will be placed separately in the actions div --}}
                    <x-nav-link href="{{ route('user.riwayat') }}" :active="request()->routeIs('user.riwayat')">Riwayat</x-nav-link>
                @endcan
            </div>

            {{-- Aksi & Auth (Kanan - Desktop) - **DIPERBARUI** --}}
            <div class="hidden md:flex md:items-center md:ml-6 md:space-x-5"> {{-- Sedikit menambah space-x jika perlu --}}

                {{-- Logic to get cart item count --}}
                @php
                    $cartItemCount = 0; // Default value
                    // Check if user is logged in AND has the 'user' capability/role (optional @can check)
                    if (Auth::check() && Auth::user()->can('user')) {
                        // Assuming User model has a 'keranjang' relation pointing to the user's single Keranjang model
                        // And Keranjang model has an 'items' relation pointing to KeranjangsItem models
                        $userCart = Auth::user()->keranjang; // Use the relation
                        if ($userCart) {
                            // Sum the quantity of all items in the cart
                            $cartItemCount = $userCart->items->sum('quantity');
                        }
                        // Alternative if User model doesn't have keranjang relation directly:
                        // $keranjang = \App\Models\Keranjang::where('user_id', Auth::id())->first();
                        // if ($keranjang) {
                        //     $cartItemCount = $keranjang->items->sum('quantity');
                        // }
                    }
                @endphp

                @can('user')
                    {{-- START: Cart Icon with Badge (Desktop) --}}
                    <a href="{{ route('user.keranjang') }}" title="Keranjang Belanja"
                       class="relative p-2 rounded-full text-gray-500 hover:text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 ease-in-out transform hover:scale-110">
                        <span class="sr-only">Keranjang Belanja</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h11.1a1 1 0 00.9-1.5L17 13M7 13L5.4 5M17 13l1.6-3.2M6 21a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                        {{-- Badge Jumlah Item (Desktop) --}}
                        @if($cartItemCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-semibold text-white shadow-sm">{{ $cartItemCount }}</span>
                        @endif
                    </a>
                    {{-- END: Cart Icon with Badge (Desktop) --}}
                @endcan

                {{-- Tombol/Link Auth (Tidak berubah stylingnya, hanya jaraknya disesuaikan oleh space-x-5 di atas) --}}
                @auth
                    <x-form method="POST" action="{{ route('auth.logout') }}" class="flex items-center">
                        @csrf
                        <button type="submit"
                                class="group inline-flex items-center relative px-4 py-2 text-sm font-medium transition-all duration-300 ease-in-out overflow-hidden rounded-md text-gray-600 hover:text-indigo-700 hover:bg-indigo-50">
                            Logout
                            <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-indigo-600 transition-all duration-300 group-hover:w-full"></span>
                        </button>
                    </x-form>
                @endauth

                @guest
                    <x-nav-link href="{{ route('auth.login') }}" :active="request()->routeIs('auth.login')">Login</x-nav-link>
                    <x-nav-link href="{{ route('auth.register') }}" :active="request()->routeIs('auth.register')">Register</x-nav-link>
                @endguest
            </div>

            {{-- Tombol Hamburger (Mobile) - **BAGIAN KERANJANG DIPERBARUI** --}}
            <div class="-mr-2 flex items-center md:hidden">
                 {{-- Re-use the $cartItemCount variable calculated above --}}
                @can('user')
                     {{-- START: Cart Icon with Badge (Mobile) --}}
                    {{-- Terapkan style yang sama untuk ikon keranjang di mobile --}}
                    <a href="{{ route('user.keranjang') }}" title="Keranjang Belanja"
                       class="relative mr-2 p-2 rounded-full text-gray-500 hover:text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 ease-in-out transform hover:scale-110">
                        <span class="sr-only">Keranjang Belanja</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h11.1a1 1 0 00.9-1.5L17 13M7 13L5.4 5M17 13l1.6-3.2M6 21a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                        {{-- Badge Jumlah Item Mobile --}}
                        {{-- Re-use the $cartItemCount variable calculated above --}}
                        @if($cartItemCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-semibold text-white shadow-sm">{{ $cartItemCount }}</span>
                        @endif
                    </a>
                     {{-- END: Cart Icon with Badge (Mobile) --}}
                @endcan

                {{-- Tombol Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()">
                    <span class="sr-only">Buka menu utama</span>
                    <svg class="block h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="hidden h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    {{-- Panel Menu Mobile - Tidak berubah --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden absolute top-16 inset-x-0 p-2 transition transform origin-top-right bg-white shadow-lg ring-1 ring-black ring-opacity-5" id="mobile-menu" @click.away="mobileMenuOpen = false">
       {{-- ... Konten menu mobile Anda ... --}}
       <div class="pt-2 pb-3 space-y-1">
            @guest
                <x-nav-link href="/" :active="request()->is('/')" class="block !px-3 !py-2 !text-base">Home</x-nav-link> {{-- Paksa block & sesuaikan padding/ukuran teks --}}
                <x-nav-link href="/about" :active="request()->is('about')" class="block !px-3 !py-2 !text-base">About</x-nav-link>
                <x-nav-link href="/contact" :active="request()->is('contact')" class="block !px-3 !py-2 !text-base">Contact</x-nav-link>
            @endguest

            @can('admin')
                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" class="block !px-3 !py-2 !text-base">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('admin.input') }}" :active="request()->routeIs('admin.input')" class="block !px-3 !py-2 !text-base">Input Produk</x-nav-link>
                <x-nav-link href="{{ route('admin.dataProduk') }}" :active="request()->routeIs('admin.dataProduk')" class="block !px-3 !py-2 !text-base">Data Produk</x-nav-link>
                <x-nav-link href="{{ route('admin.manajemen') }}" :active="request()->routeIs('admin.manajemen')" class="block !px-3 !py-2 !text-base">Manajemen</x-nav-link>
            @endcan

            @can('user')
                <x-nav-link href="{{ route('user.dashboard') }}" :active="request()->routeIs('user.dashboard')" class="block !px-3 !py-2 !text-base">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('user.katalog') }}" :active="request()->routeIs('user.katalog')" class="block !px-3 !py-2 !text-base">Katalog Produk</x-nav-link>
                 {{-- Add Cart link in mobile menu as well --}}
                 <x-nav-link href="{{ route('user.keranjang') }}" :active="request()->routeIs('user.keranjang')" class="block !px-3 !py-2 !text-base">
                     Keranjang Belanja
                     @if($cartItemCount > 0)
                          {{-- Display count inline or as badge here --}}
                         ({{ $cartItemCount }})
                     @endif
                 </x-nav-link>
                <x-nav-link href="{{ route('user.riwayat') }}" :active="request()->routeIs('user.riwayat')" class="block !px-3 !py-2 !text-base">Riwayat</x-nav-link>
            @endcan
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            @auth
                <x-form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-indigo-700 hover:bg-indigo-50">
                        Logout
                    </button>
                </x-form>
            @endauth
            @guest
                <x-nav-link href="{{ route('auth.login') }}" :active="request()->routeIs('auth.login')" class="block !px-3 !py-2 !text-base">Login</x-nav-link>
                <x-nav-link href="{{ route('auth.register') }}" :active="request()->routeIs('auth.register')" class="block !px-3 !py-2 !text-base">Register</x-nav-link>
            @endguest
        </div>
    </div>
</header>