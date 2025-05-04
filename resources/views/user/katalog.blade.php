<x-layout> 
    <x-notifications /> 
    <div class="min-h-screen ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold mb-8 text-center sm:text-left text-gray-800">Katalog Produk</h1>
            <div x-data="{
                searchTerm: '{{ old('search', request('search', '')) }}', // Ambil dari old input atau request, default string kosong
                sortOption: '{{ old('sort', request('sort', '')) }}',     // Ambil dari old input atau request, default string kosong
                init() {
                    // Perhatikan perubahan searchTerm setelah debounce
                    let firstRun = true; // Flag untuk mencegah trigger saat inisialisasi
                    this.$watch('searchTerm', (newValue, oldValue) => {
                        // Hanya trigger jika nilai benar-benar berubah dan bukan saat load awal
                        if (!firstRun && newValue !== oldValue) {
                            console.log('Search term changed via watch:', newValue);
                            this.updateUrl();
                        }
                        firstRun = false; // Set flag setelah run pertama selesai
                    });
                     // Set flag firstRun ke false setelah inisialisasi selesai untuk watch searchTerm
                     this.$nextTick(() => { firstRun = false; });
                },
                updateUrl() {
                    // Tunggu sebentar agar model terupdate jika dipicu oleh enter/change
                    this.$nextTick(() => {
                        console.log('Updating URL with:', this.searchTerm, this.sortOption);
                        let url = '{{ route('user.katalog') }}';
                        const params = new URLSearchParams(); // Gunakan URLSearchParams untuk handling lebih mudah
        
                        const cleanSearchTerm = this.searchTerm.trim();
                        const cleanSortOption = this.sortOption; // sortOption sudah bersih dari select
        
                        if (cleanSearchTerm !== '') {
                            params.append('search', cleanSearchTerm);
                        }
                        if (cleanSortOption !== '') {
                            params.append('sort', cleanSortOption);
                        }
        
                        const queryString = params.toString();
                        if (queryString) {
                            url += '?' + queryString;
                        }
        
                        console.log('Navigating to:', url);
                        window.location.href = url; // Reload halaman dengan parameter baru
                    });
                }
            }"
            class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4"
        >
            {{-- Input Pencarian dengan Ikon --}}
            <div class="relative w-full sm:w-auto">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </span>
                <input
                    type="text"
                    placeholder="Cari produk..."
                    x-model.debounce.500ms="searchTerm" {{-- Update model setelah 500ms tidak aktif --}}
                    @keydown.enter.prevent="updateUrl" {{-- Trigger pencarian saat Enter ditekan --}}
                    {{-- @input="console.log('Input event:', $event.target.value)" --}} {{-- Debugging --}}
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
            </div>
        
            {{-- Dropdown Urutkan --}}
            <select
                x-model="sortOption"
                @change="updateUrl" {{-- Trigger update saat pilihan berubah --}}
                class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 w-full sm:w-auto py-2 text-sm"
            >
                <option value="">Urutkan Berdasarkan</option>
                <option value="harga_asc" >Harga Termurah</option> {{-- :selected dihapus, x-model menangani ini --}}
                <option value="harga_desc">Harga Termahal</option>
                <option value="nama_asc"  >Nama A-Z</option>
                <option value="nama_desc" >Nama Z-A</option>
            </select>
        </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($katalog as $item)
                    <div
                        x-data="{}"
                        {{-- Hanya bisa diklik jika stok > 0 --}}
                        @if($item->stok > 0)
                            @click="$dispatch('open-modal', { product: {{ json_encode($item) }} })"
                            class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-pointer" 
                        @else
                            class="group relative bg-white rounded-2xl shadow-lg opacity-60 cursor-not-allowed overflow-hidden" {{-- Style untuk item habis + overflow --}}
                        @endif
                    >
                        {{-- Container Gambar dengan Aspect Ratio --}}
                        <div class="overflow-hidden relative aspect-[4/3] bg-gray-100"> {{-- Rasio 4:3, bisa diganti (misal aspect-square), bg fallback --}}
                            <img
                                src="{{ $item->gambar ? asset('storage/images/'. $item->gambar) : asset('images/default-placeholder.jpg') }}" {{-- Tambahkan fallback di sini juga --}}
                                alt="{{ $item->nama_produk }}"
                                class="w-full h-full object-cover transition-transform duration-300 {{ $item->stok > 0 ? 'group-hover:scale-105' : '' }}"
                                loading="lazy" {{-- Tambahkan lazy loading untuk performa --}}
                            >
                            {{-- Badge Stok Habis / Terbatas --}}
                            @if($item->stok <= 0)
                                <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full z-10 shadow">
                                    Stok Habis
                                </span>
                            @elseif ($item->stok <= 5)
                                <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full z-10 shadow">
                                    Stok Terbatas
                                </span>
                            @endif
                        </div>

                        {{-- Konten Kartu --}}
                        <div class="p-5"> {{-- Sedikit kurangi padding --}}
                            <h3
                                class="text-lg font-bold text-gray-800 mb-1.5 truncate transition-colors duration-300 {{ $item->stok > 0 ? 'group-hover:text-indigo-600' : '' }}"
                                title="{{ $item->nama_produk }}"
                            >
                                {{ $item->nama_produk }}
                            </h3>
                            <p class="text-base font-semibold text-indigo-600 mb-2">
                                {{-- Pastikan $item->harga adalah number/string angka --}}
                                Rp {{ number_format( (float) $item->harga, 0, ',', '.') }}
                            </p>
                            {{-- Tampilkan Stok Tersedia jika > 0 --}}
                            @if ($item->stok > 0)
                                <p class="text-sm text-gray-500">Stok: {{ $item->stok }}</p>
                            @else
                                <p class="text-sm text-red-600 font-medium">Stok Habis</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full text-center py-12 text-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Belum ada produk di katalog saat ini.
                    </p>
                @endforelse
            </div>
        </div>
        {{-- Modal Detail Produk & Add to Cart --}}
        <div
            x-data="{
                showModal: false,
                selectedProduct: null,
                quantity: 1,
                imageBasePath: '{{ rtrim(asset('storage/images'), '/') }}/', // Pastikan tidak ada double slash
                defaultImagePath: '{{ asset('images/placeholder-ayam.png') }}', // <-- GANTI INI dengan path gambar default Anda di folder public
                get availableStock() {
                    // Pastikan selectedProduct ada dan stok adalah angka
                    return this.selectedProduct && !isNaN(parseInt(this.selectedProduct.stok)) ? parseInt(this.selectedProduct.stok) : 0;
                },
                incrementQuantity() {
                    if (this.quantity < this.availableStock) {
                        this.quantity++;
                    }
                },
                decrementQuantity() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },
                formatCurrency(amount) {
                    // Coba bersihkan string sebelum parsing, handle null/undefined
                    const cleanAmount = String(amount || '0').replace(/[^0-9,-]+/g,'').replace(',','.');
                    const numberAmount = parseFloat(cleanAmount);
                    if (isNaN(numberAmount)) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(numberAmount);
                },
                openModal(product) {
                    this.selectedProduct = product;
                    const stock = this.availableStock; // Gunakan getter
                    this.quantity = stock > 0 ? 1 : 0; // Set quantity 0 jika stok habis
                    this.showModal = true;
                    // Optional: lock scroll background
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.showModal = false;
                    this.selectedProduct = null; // Reset produk terpilih
                     // Optional: unlock scroll background
                    document.body.style.overflow = '';
                }
            }"
            x-show="showModal"
            @open-modal.window="openModal($event.detail.product)"
            @keydown.escape.window="closeModal()"
            x-cloak {{-- Atribut untuk menyembunyikan elemen sampai Alpine siap --}}
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            {{-- Backdrop --}}
            <div
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm"
                @click="closeModal()"
                aria-hidden="true"
            ></div>

            {{-- Modal Panel --}}
            <div
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative mx-auto my-8 max-w-lg w-full p-4" {{-- Max-w-lg agar sedikit lebih lebar --}}
                @click.stop
            >
                <div class="bg-white rounded-2xl shadow-2xl relative flex flex-col overflow-hidden">
                    {{-- Konten Modal (Info Produk & Form) --}}
                    <template x-if="selectedProduct">
                        <div> {{-- Wrapper agar template punya satu root element --}}
                            {{-- Tombol Close Modal --}}
                            <button @click="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 z-20 p-1 bg-white/50 rounded-full">
                                <span class="sr-only">Tutup Modal</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <div class="p-6 sm:p-8"> {{-- Padding lebih besar di modal --}}
                                {{-- Info Produk --}}
                                <div class="flex flex-col sm:flex-row items-start gap-5 mb-6">
                                    <img
                                        :src="selectedProduct.gambar ? imageBasePath + selectedProduct.gambar : defaultImagePath" {{-- Menggunakan defaultImagePath --}}
                                        :alt="selectedProduct.nama_produk"
                                        class="w-full sm:w-36 h-auto sm:h-36 object-cover rounded-lg flex-shrink-0 border border-gray-200 bg-gray-100" {{-- Style gambar modal --}}
                                        loading="lazy"
                                    >
                                    <div class="flex-grow">
                                        <h2 id="modal-title" class="text-2xl font-bold text-gray-900" x-text="selectedProduct.nama_produk || 'Nama Produk Tidak Tersedia'"></h2>
                                        <p class="text-xl font-semibold text-indigo-600 mt-1" x-text="formatCurrency(selectedProduct.harga || 0)"></p>
                                        <p class="text-sm font-medium mt-3"
                                           :class="availableStock > 0 ? 'text-green-600' : 'text-red-600'">
                                            Stok: <span x-text="availableStock > 0 ? availableStock : 'Habis'"></span>
                                        </p>
                                        {{-- Tampilkan Deskripsi jika ada --}}
                                        <p class="text-sm text-gray-600 mt-3" x-text="selectedProduct.deskripsi || 'Tidak ada deskripsi untuk produk ini.'"></p>
                                    </div>
                                </div>

                                {{-- Form Pemesanan --}}
                                <form method="POST" action="{{ route('user.katalog') }}" class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="produk_id" :value="selectedProduct ? selectedProduct.id : ''">
                                    <input type="hidden" name="action" value="add_to_cart">

                                    {{-- Input Quantity (Hanya jika stok ada) --}}
                                    <template x-if="availableStock > 0">
                                        <div>
                                            <label for="quantity-input" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah</label>
                                            <div class="flex items-center justify-start max-w-[150px]"> {{-- Batasi lebar input group --}}
                                                <button
                                                    type="button"
                                                    @click="decrementQuantity()"
                                                    :disabled="quantity <= 1"
                                                    class="w-10 h-10 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-600 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                    aria-label="Kurangi jumlah"
                                                > - </button>
                                                <input
                                                    id="quantity-input"
                                                    type="number"
                                                    name="quantity"
                                                    x-model.number="quantity"
                                                    class="w-16 h-10 text-center border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                    min="1"
                                                    :max="availableStock"
                                                    readonly {{-- Tetap readonly, diubah via tombol --}}
                                                >
                                                <button
                                                    type="button"
                                                    @click="incrementQuantity()"
                                                    :disabled="quantity >= availableStock"
                                                    class="w-10 h-10 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-600 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                    aria-label="Tambah jumlah"
                                                > + </button>
                                            </div>
                                            
                                        </div>
                                    </template>

                                    {{-- Tombol Submit --}}
                                    <div class="pt-2"> {{-- Beri sedikit jarak atas --}}
                                        <button
                                            type="submit"
                                            :disabled="availableStock <= 0 || quantity <= 0 || quantity > availableStock"
                                            class="w-full flex items-center justify-center bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold text-base hover:bg-emerald-700 transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                                        >
                                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                              <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            </svg>
                                            <span x-text="availableStock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div> {{-- End p-6 sm:p-8 --}}
                        </div> {{-- End Wrapper Template --}}
                    </template>

                    {{-- Tampilan jika produk tidak terpilih (fallback) --}}
                    <template x-if="!selectedProduct && showModal">
                         <div class="p-8 text-center text-gray-500">
                             <p>Gagal memuat detail produk atau produk tidak ditemukan.</p>
                         </div>
                    </template>
                </div> {{-- End bg-white --}}
            </div> {{-- End Modal Panel --}}
        </div> {{-- Akhir dari Modal --}}

    </div> {{-- Akhir dari div min-h-screen --}}

    {{-- Tidak perlu include Alpine.js jika sudah ada di layout utama <x-layout> --}}
    <script src="//unpkg.com/alpinejs" defer></script>
</x-layout>