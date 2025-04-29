<x-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Login Notification with Alpine.js -->
        @auth
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 5000)" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-4"
             class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded shadow-md relative">
            <button @click="show = false" class="absolute top-2 right-2 text-green-500 hover:text-green-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="font-medium">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span>!</p>
            </div>
        </div>
        @endauth

        <!-- Hero Section -->
        <div class="grid md:grid-cols-2 gap-8 items-center mb-16">
            <div class="space-y-6 flex flex-col justify-center" 
                 x-data="{show: false}" 
                 x-init="setTimeout(() => show = true, 300)"
                 x-bind:class="{'opacity-0': !show, 'opacity-100 transform translate-x-0': show}"
                 class="transition duration-700 ease-out transform translate-x-[-20px]">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight">PT Penjualan Ayam Sejahtera</h1>
                <p class="text-lg text-gray-600">Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt libero neque consequatur corrupti ab eius voluptas aut nulla praesentium eum obcaecati cumque, inventore nihil quasi minima dicta reprehenderit ratione perferendis.</p>
                <div>
                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-300 transform hover:-translate-y-1">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
            <div class="flex justify-center" 
                 x-data="{show: false}" 
                 x-init="setTimeout(() => show = true, 500)"
                 x-bind:class="{'opacity-0': !show, 'opacity-100 transform translate-x-0': show}"
                 class="transition duration-700 ease-out transform translate-x-[20px]">
                <img src="{{ asset('storage/images/czRiOqPWea3JoDSWCaubemXpe0v5iFAtHNx2z0qi.jpg') }}" 
                     alt="Ayam Sejahtera" 
                     class="rounded-lg shadow-xl hover:shadow-2xl transition duration-300 max-w-full h-auto" />
            </div>
        </div>

        <!-- Best Seller Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center relative overflow-hidden">
                <span class="relative z-10 bg-white px-6">Best Seller</span>
                <div class="absolute top-1/2 left-0 w-full h-px bg-gray-300"></div>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
                @for ($i = 1; $i <= 8; $i++)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300"
                     x-data="{show: false}" 
                     x-init="setTimeout(() => show = true, 300 + ({{ $i }} * 100))"
                     x-bind:class="{'opacity-0': !show, 'opacity-100 transform translate-y-0': show}"
                     class="transition duration-500 ease-out transform translate-y-[20px]">
                    <div class="overflow-hidden">
                        <img src="{{ asset('storage/images/czRiOqPWea3JoDSWCaubemXpe0v5iFAtHNx2z0qi.jpg') }}" 
                             alt="Ayam Product {{ $i }}" 
                             class="w-full h-48 object-cover transform group-hover:scale-110 transition duration-500" />
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-lg mb-2">Ayam Spesial {{ $i }}</h3>
                        <p class="text-gray-600 text-sm mb-3">Ayam berkualitas tinggi dengan bumbu rahasia</p>
                        <p class="font-bold text-yellow-600 mb-3">Rp. {{ number_format(rand(30000, 100000), 0, ',', '.') }}</p>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition duration-300">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Profile Section -->
        @auth
        <div class="mb-16 bg-gray-50 rounded-2xl p-8 shadow-md" id="profile">
            <h2 class="text-3xl font-bold mb-8 text-center">Profil Saya</h2>
            
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-yellow-400 shadow-lg">
                            <!-- Placeholder avatar -->
                            <div class="bg-yellow-100 w-full h-full flex items-center justify-center text-6xl text-yellow-500 font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-yellow-500 text-white p-2 rounded-full shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->name }}</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow">
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="font-semibold text-lg">{{ auth()->user()->role }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300 mr-2">
                            Edit Profil
                        </button>
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-300">
                            Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endauth
        
        <!-- Riwayat Pembelian Section -->
        @auth
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center relative overflow-hidden">
                <span class="relative z-10 bg-white px-6">Riwayat Pembelian</span>
                <div class="absolute top-1/2 left-0 w-full h-px bg-gray-300"></div>
            </h2>
            
            <div x-data="{ activeTab: 'semua' }" class="space-y-6">
                <!-- Tabs -->
                <div class="flex flex-wrap justify-center space-x-2 mb-6">
                    <button @click="activeTab = 'semua'" 
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'semua', 'bg-gray-200 text-gray-700': activeTab !== 'semua' }"
                            class="px-4 py-2 rounded-full font-medium transition duration-300">
                        Semua
                    </button>
                    <button @click="activeTab = 'diproses'" 
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'diproses', 'bg-gray-200 text-gray-700': activeTab !== 'diproses' }"
                            class="px-4 py-2 rounded-full font-medium transition duration-300">
                        Diproses
                    </button>
                    <button @click="activeTab = 'dikirim'" 
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'dikirim', 'bg-gray-200 text-gray-700': activeTab !== 'dikirim' }"
                            class="px-4 py-2 rounded-full font-medium transition duration-300">
                        Dikirim
                    </button>
                    <button @click="activeTab = 'selesai'" 
                            :class="{ 'bg-yellow-500 text-white': activeTab === 'selesai', 'bg-gray-200 text-gray-700': activeTab !== 'selesai' }"
                            class="px-4 py-2 rounded-full font-medium transition duration-300">
                        Selesai
                    </button>
                </div>
                
                <!-- Tab Content -->
                <div>
                    <!-- Empty State - For Demo -->
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <div class="flex justify-center mb-4">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada pesanan</h3>
                        <p class="text-gray-500 mb-6">Anda belum melakukan pembelian apa pun.</p>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                            Mulai Belanja
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endauth
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-4">PT Penjualan Ayam Sejahtera</h3>
                    <p class="text-gray-400">Menyediakan produk ayam berkualitas tinggi dan layanan terbaik untuk kebutuhan kuliner Anda. Sejak 2010.</p>
                    <div class="flex space-x-4 pt-2">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Beranda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Produk</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Testimoni</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Kontak</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kategori Produk</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Ayam Potong</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Ayam Utuh</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Daging Premium</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Olahan Ayam</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Bumbu Spesial</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-400">Jl. Ayam Sejahtera No. 123, Jakarta Selatan, Indonesia</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-400">+62 812 3456 7890</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-400">info@ayamsejahtera.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} PT Penjualan Ayam Sejahtera. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</x-layout>