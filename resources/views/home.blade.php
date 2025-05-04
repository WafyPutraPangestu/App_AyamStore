<x-layout>
    {{-- (1) Pastikan body/layout utama memiliki background putih atau sangat terang --}}
    {{-- Asumsi x-layout sudah memiliki bg-white atau bg-slate-50 --}}

    {{-- Tambahan: Script untuk smooth scroll (Tetap relevan) --}}
    {{-- Pastikan offset (- 70) sesuai dengan tinggi header/navbar jika ada yang sticky --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70, // Sesuaikan offset ini
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

    {{-- Container utama dengan padding standar --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- (Opsional) Login Notification - Nuansa Indigo, shadow lebih halus --}}
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
             {{-- ^ Menggunakan indigo, shadow-md --}}
            <button @click="show = false" class="absolute top-2 right-2 text-indigo-500 hover:text-indigo-700 focus:outline-none">
                 {{-- ^ Tambah focus state --}}
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium">Selamat datang, <span class="font-bold">{{ auth()->user()->name }}</span>!</p>
            </div>
        </div>
        @endauth

        {{-- (2) Hero Section - Lebih clean, tipografi lebih tegas, warna indigo --}}
        <div class="grid md:grid-cols-2 gap-12 items-center py-16 md:py-20 mb-16 md:mb-20 min-h-[60vh]">
             {{-- ^ Tambah min-h untuk kesan lebih lapang --}}
            <div class="space-y-6"
                 x-data="{show: false}"
                 x-init="setTimeout(() => show = true, 300)"
                 :class="{'opacity-0': !show, 'opacity-100 translate-y-0': show}"
                 class="transition duration-700 ease-out transform translate-y-5">

                {{-- Judul lebih bold, warna slate & indigo --}}
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight">
                    PT Penjualan Ayam <span class="text-indigo-600">Sejahtera</span>
                </h1>
                {{-- Teks paragraf sedikit lebih besar, warna slate --}}
                <p class="text-lg lg:text-xl text-slate-600 leading-relaxed">
                    Kami menyediakan produk ayam segar berkualitas tinggi langsung dari peternakan terbaik, diolah secara higienis untuk kebutuhan kuliner Anda.
                </p>
                <div>
                    {{-- Tombol dengan warna indigo, hover & focus state --}}
                    <a href="#produk" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Lihat Produk
                    </a>
                </div>
            </div>
            {{-- Gambar dengan rounded lebih besar, shadow lebih halus --}}
            <div x-data="{show: false}"
                 x-init="setTimeout(() => show = true, 500)"
                 :class="{'opacity-0': !show, 'opacity-100 translate-y-0': show}"
                 class="transition duration-700 ease-out transform translate-y-5">
                <img src="{{ asset('storage/images/czRiOqPWea3JoDSWCaubemXpe0v5iFAtHNx2z0qi.jpg') }}"
                     alt="Ayam Sejahtera Modern"
                     class="rounded-2xl shadow-lg w-full h-auto object-cover" />
                     {{-- ^ rounded-2xl, shadow-lg --}}
            </div>
        </div>

        {{-- (3) Best Seller / Produk Section - Warna indigo, card lebih clean --}}
        <div id="produk" class="py-16 md:py-20 mb-16 md:mb-20 scroll-mt-20"> {{-- Tambah scroll-mt-20 (sesuaikan offset) --}}
            {{-- Judul dengan warna slate & indigo, font-bold --}}
            <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 text-center mb-4">Produk <span class="text-indigo-600">Pilihan Kami</span></h2>
            <p class="text-center text-slate-600 text-lg mb-12 lg:mb-16 max-w-2xl mx-auto">Temukan berbagai pilihan ayam segar dan olahan berkualitas untuk melengkapi hidangan istimewa Anda.</p>

            {{-- Grid produk - card tanpa border, shadow halus, hover lebih interaktif --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @for ($i = 1; $i <= 8; $i++)
                {{-- Card: bg-white, rounded, shadow halus, hover shadow lebih kuat + angkat sedikit --}}
                <div class="group bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition duration-300 ease-in-out"
                     x-data="{show: false}"
                     x-init="setTimeout(() => show = true, 300 + ({{ $i }} * 100))"
                     :class="{'opacity-0': !show, 'opacity-100 translate-y-0': show}"
                     class="transition duration-500 ease-out transform translate-y-5">

                    {{-- Gambar: aspect ratio, hover zoom --}}
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ asset('storage/images/czRiOqPWea3JoDSWCaubemXpe0v5iFAtHNx2z0qi.jpg') }}"
                             alt="Ayam Produk {{ $i }}"
                             class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 ease-in-out" />
                    </div>
                    {{-- Konten Card: padding, text slate --}}
                    <div class="p-5 text-left">
                        <h3 class="font-semibold text-lg text-slate-800 mb-1 truncate" title="Ayam Spesial {{ $i }}">Ayam Spesial {{ $i }}</h3>
                        <p class="text-slate-500 text-sm mb-3">Deskripsi singkat produk</p>
                        {{-- Harga: Warna indigo --}}
                        <p class="font-bold text-indigo-600 text-lg mb-4">Rp. {{ number_format(rand(30000, 100000), 0, ',', '.') }}</p>
                        {{-- Tombol Add to Cart: Warna lembut, ikon, full width, focus state --}}
                        <button class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-medium py-2 px-4 rounded-md text-sm transition duration-300 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Tambah</span>
                        </button>
                    </div>
                </div>
                @endfor
            </div>
            {{-- Tombol "Lihat Semua" --}}
            <div class="text-center mt-12 lg:mt-16">
                <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </div> {{-- End Container --}}

    {{-- (4) Footer - Background lebih netral (slate), text slate, link indigo --}}
    <footer class="bg-slate-100 text-slate-700 border-t border-slate-200">
         {{-- ^ bg-slate-100, border-slate-200 --}}
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                {{-- Kolom 1: Info Perusahaan & Social Media --}}
                <div class="space-y-4 md:col-span-1">
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">PT Penjualan Ayam <span class="text-indigo-600">Sejahtera</span></h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Menyediakan produk ayam berkualitas tinggi dan layanan terbaik untuk kebutuhan kuliner Anda.</p>
                    {{-- Ikon sosial media: warna slate hover indigo --}}
                    <div class="flex space-x-4 pt-2">
                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition duration-300"> <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">...</svg></a> {{-- Isi path SVG --}}
                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition duration-300"> <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">...</svg></a> {{-- Isi path SVG --}}
                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition duration-300"> <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">...</svg></a> {{-- Isi path SVG --}}
                    </div>
                </div>

                {{-- Kolom 2: Navigasi Cepat --}}
                <div class="md:col-span-1">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Navigasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Beranda</a></li>
                        <li><a href="#produk" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Produk</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Tentang Kami</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Hubungi Kami</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Kategori --}}
                <div class="md:col-span-1">
                     <h3 class="text-base font-semibold text-slate-900 mb-4">Kategori Produk</h3>
                     <ul class="space-y-2">
                         <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Ayam Potong</a></li>
                         <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Ayam Utuh</a></li>
                         <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Produk Olahan</a></li>
                         <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition duration-300 text-sm">Bumbu & Rempah</a></li>
                     </ul>
                </div>

                {{-- Kolom 4: Kontak --}}
                <div class="md:col-span-1">
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

            {{-- Copyright --}}
            <div class="border-t border-slate-200 mt-8 pt-8 text-center">
                <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} PT Penjualan Ayam Sejahtera. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</x-layout>