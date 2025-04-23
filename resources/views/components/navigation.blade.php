<header class="bg-white shadow">
    <nav class="flex justify-between items-center container mx-auto px-5 py-2.5">
        <div class="">
            <h1 class="text-2xl font-bold">LOGO</h1>
        </div>
        <div>
            @guest
                
            <x-nav-link href="/" :active="request()->is('/')" >
                <span>Home</span>
            </x-nav-link>
            <x-nav-link href="#" :active="request()->routeIs('/')">About</x-nav-link>
            <x-nav-link href="#" :active="request()->routeIs('/')">Contact</x-nav-link>
            @endguest
            @can('admin')
            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->is('admin/dashboard')" >
                <span>Dashboard</span>
            </x-nav-link>
            <x-nav-link href="{{ route('admin.input') }}" :active="request()->is('admin/input')" >
                <span>Input Produk</span>
            </x-nav-link>
            <x-nav-link href="{{ route('admin.dataProduk') }}" :active="request()->is('admin/dataProduk')" >
                <span>Data Produk</span>
            </x-nav-link>
            <x-nav-link href="{{ route('admin.manajemen') }}" :active="request()->is('admin/manajemen')" >
                <span>Manajemen</span>
            </x-nav-link>
            @endcan
            @can('user')
            <x-nav-link href="{{ route('user.dashboard') }}" :active="request()->is('user/dashboard')" >
                <span>Dashboard</span>
            </x-nav-link>
            <x-nav-link href="{{ route('user.katalog') }}" :active="request()->is('user/katalog')" >
                <span>Katalog Produk</span>
            </x-nav-link>
               
            @endcan
        </div>
        <div class="relative">
            <div class="">
                @can('user')
                    <a href="{{ route('user.keranjang') }}" class="absolute top-0 right-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" class="w-5 h-5 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h11.1a1 1 0 00.9-1.5L17 13M7 13L5.4 5M17 13l1.6-3.2M6 21a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                    </a>
                @endcan
            <div>
            @auth
                <x-form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-blue-600 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 rounded-lg px-5 py-2 text-sm font-medium transition-all duration-300 ease-in-out overflow-hidden">
                        Logout
                    </button>
                </x-form>
            @endauth
            @guest
                <x-nav-link href="{{ route('auth.login') }}" :active="request()->routeIs('auth/login')">Login</x-nav-link>
                <x-nav-link href="{{ route('auth.register') }}" :active="request()->routeIs('/')">Register</x-nav-link>
            @endguest
        </div>
    </nav>
</header>