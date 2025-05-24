<x-layout>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">

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
             class="bg-indigo-50 border-l-4 border-indigo-500 text-indigo-800 p-4 mb-10 rounded-md shadow-md relative">
            <button @click="show = false" class="absolute top-2 right-2 text-indigo-500 hover:text-indigo-700 focus:outline-none">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span>!</p>
            </div>
        </div>
        @endauth

        <div class="grid md:grid-cols-2 gap-12 items-center mb-16 md:mb-20 min-h-[60vh]">
            <div class="space-y-6"
                 x-data="{show: false}"
                 x-init="setTimeout(() => show = true, 300)"
                 :class="{'opacity-0 -translate-y-5': !show, 'opacity-100 translate-y-0': show}"
                 class="transition duration-700 ease-out transform">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight">
                    PT Penjualan Ayam <span class="text-indigo-600">Sejahtera</span>
                </h1>
                <p class="text-lg lg:text-xl text-slate-600 leading-relaxed">
                    Kami menyediakan produk ayam segar berkualitas tinggi langsung dari peternakan terbaik, diolah secara higienis untuk kebutuhan kuliner Anda.
                </p>
                <div>
                    <a href="#produk" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Lihat Produk
                    </a>
                </div>
            </div>
            <div x-data="{show: false}"
                 x-init="setTimeout(() => show = true, 500)"
                 :class="{'opacity-0 translate-y-5': !show, 'opacity-100 translate-y-0': show}"
                 class="transition duration-700 ease-out transform">
                <img src="{{ asset('storage/images/czRiOqPWea3JoDSWCaubemXpe0v5iFAtHNx2z0qi.jpg') }}"
                     alt="Ayam Sejahtera Modern"
                     class="rounded-2xl shadow-lg w-full h-auto object-cover" />
            </div>
        </div>

        <div id="produk" class="py-16 md:py-20 mb-16 md:mb-20 scroll-mt-20">
            <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 text-center mb-4">Produk <span class="text-indigo-600">Pilihan Kami</span></h2>
            <p class="text-center text-slate-600 text-lg mb-12 lg:mb-16 max-w-2xl mx-auto">Temukan berbagai pilihan ayam segar dan olahan berkualitas untuk melengkapi hidangan istimewa Anda.</p>

            @if($produk->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($produk as $item)
                <div class="group bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out"
                     x-data="{show: false}"
                     x-init="setTimeout(() => show = true, 300 + ({{ $loop->index }} * 150))"
                     :class="{'opacity-0 translate-y-5': !show, 'opacity-100 translate-y-0': show}"
                     class="transition-all duration-500 ease-out transform">
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ asset('storage/images/' . $item->gambar) }}"
                             alt="{{ $item->nama_produk }}"
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ease-in-out" />
                    </div>
                    <div class="p-5 text-left">
                        <h3 class="font-semibold text-lg text-slate-800 mb-1 truncate" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</h3>
                        <p class="text-slate-500 text-sm mb-3 min-h-[40px]">{{ Str::limit($item->deskripsi, 50) }}</p>
                        <p class="font-bold text-indigo-600 text-lg mb-4">Rp. {{ number_format($item->harga, 0, ',', '.') }}</p>
                        <a href="{{ route('user.katalog') }}">
                            <button class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-medium py-2 px-4 rounded-md text-sm transition-colors duration-300 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Tambah</span>
                            </button>
                            </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-slate-500 text-lg">Saat ini belum ada produk yang ditampilkan.</p>
            @endif

            @if($produk->isNotEmpty())
            <div class="text-center mt-12 lg:mt-16">
                <a href="{{ route('user.katalog') }}" 
                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Lihat Semua Produk
                </a>
            </div>
            @endif
        </div>
    </div>

    <footer class="bg-slate-100 text-slate-700 border-t border-slate-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div class="space-y-4 md:col-span-1 lg:col-span-1">
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">PT Penjualan Ayam <span class="text-indigo-600">Sejahtera</span></h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Menyediakan produk ayam berkualitas tinggi dan layanan terbaik untuk kebutuhan kuliner Anda.</p>
                    <div class="flex space-x-4 pt-2">
                        <a href="#" aria-label="Facebook" class="text-slate-400 hover:text-indigo-600 transition duration-300">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.142v3.24h-1.918c-1.504 0-1.795.715-1.795 1.763v2.088h3.587l-.467 3.622h-3.12v9.294h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>
                        <a href="#" aria-label="Twitter" class="text-slate-400 hover:text-indigo-600 transition duration-300">
                           <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg"><path d="M22.46 6c-.77.35-1.6.58-2.46.67.9-.53 1.59-1.37 1.92-2.38-.84.5-1.78.86-2.79 1.07C18.36 4.37 17.09 4 15.72 4c-2.66 0-4.81 2.15-4.81 4.81 0 .38.04.75.13 1.11C7.67 9.69 4.67 8.09 2.72 5.59c-.41.71-.64 1.54-.64 2.42 0 1.67.85 3.14 2.14 3.99-.79-.02-1.53-.24-2.18-.6v.06c0 2.33 1.66 4.28 3.87 4.73-.4.11-.82.17-1.26.17-.31 0-.61-.03-.91-.09.61 1.92 2.39 3.31 4.5 3.35-1.65 1.29-3.73 2.06-5.98 2.06-.39 0-.77-.02-1.15-.07C2.44 19.77 4.99 20.5 7.76 20.5c7.73 0 11.96-6.4 11.96-11.96 0-.18 0-.36-.01-.54.82-.59 1.53-1.33 2.09-2.16z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="text-slate-400 hover:text-indigo-600 transition duration-300">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163m0-1.602C8.726.561 8.325.553 7.059.613c-3.585.165-6.13 2.71-6.296 6.296C.699 8.325.613 8.726.613 12c0 3.275.008 3.675.068 4.941.165 3.585 2.711 6.131 6.296 6.296.057.068.457.06 1.723.06 3.275 0 3.675-.008 4.941-.068 3.585-.165 6.13-2.711 6.296-6.296.06-.057.068-.457.068-1.723s-.008-3.675-.068-4.941c-.165-3.585-2.71-6.13-6.296-6.296C15.675.613 15.275.561 12 .561zm0 5.828c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162S15.403 6.39 12 6.39zm0 10.722c-2.512 0-4.558-2.046-4.558-4.558s2.046-4.558 4.558-4.558 4.558 2.046 4.558 4.558-2.046 4.558-4.558 4.558zm6.406-11.919c-.797 0-1.444.646-1.444 1.444s.646 1.444 1.444 1.444c.797 0 1.444-.646 1.444-1.444s-.646-1.444-1.444-1.444z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="md:col-span-1 lg:col-span-1">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Navigasi</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Beranda</a></li>
                        <li><a href="#produk" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Produk</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Tentang Kami</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div class="md:col-span-1 lg:col-span-1">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Kategori Produk</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Ayam Potong</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Ayam Utuh</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Produk Olahan</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Bumbu & Rempah</a></li>
                    </ul>
                </div>

                <div class="md:col-span-1 lg:col-span-1">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-indigo-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-slate-600">Jl. Ayam Sejahtera No. 123, Jakarta</span>
                        </li>
                        <li class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-indigo-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-slate-600">+62 812 3456 7890</span>
                        </li>
                        <li class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-indigo-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-slate-600">info@ayamsejahtera.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200 mt-8 pt-8 text-center">
                <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} PT Penjualan Ayam Sejahtera. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</x-layout>