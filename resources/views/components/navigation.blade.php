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
        </div>
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