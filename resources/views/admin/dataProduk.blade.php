<x-layout>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-green-400 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg" style="display: none;">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-red-400 to-red-600 text-white px-6 py-3 rounded-xl shadow-lg" style="display: none;">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="container mx-auto px-4 py-6" x-data="{
        searchTerm: '',
        showDeleteModal: false,
        deleteForm: null,
        productName: '',
        confirmDelete() {
            this.showDeleteModal = true;
        },
        cancelDelete() {
            this.showDeleteModal = false;
            this.deleteForm = null;
            this.productName = '';
        },
        proceedDelete() {
            if (this.deleteForm) {
                this.deleteForm.submit();
            }
            this.showDeleteModal = false;
        }
    }">

        <div x-show="showDeleteModal"
             class="fixed inset-0 z-50 overflow-y-auto"
             :class="{ 'pointer-events-none': !showDeleteModal }"
             x-cloak
             style="display: none;">

            <div @click="cancelDelete()"
                 x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.stop
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center">
                            <div class="mx-auto flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900">
                                    Konfirmasi Hapus Produk
                                </h3>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus produk <span class="font-semibold text-gray-900" x-text="productName"></span>?
                                        Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                        <button @click="proceedDelete()"
                                class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition-all duration-200 sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Ya, Hapus Produk
                        </button>
                        <button @click="cancelDelete()"
                                class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-all duration-200 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Data Produk Ayam</h1>
                <p class="text-gray-600 mt-1">Kelola dan monitor produk ayam Anda</p>
            </div>
            <a href="{{ route('admin.input') }}"
               class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Produk
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Produk</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $produk->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-3 rounded-xl">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Stok Tersedia</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $produk->where('stok', '>', 0)->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-100 to-green-200 p-3 rounded-xl">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Stok Habis</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $produk->where('stok', '<=', 0)->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-100 to-red-200 p-3 rounded-xl">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input x-model="searchTerm" type="text" placeholder="Cari nama produk..."
                       class="pl-10 pr-4 py-3 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Data Produk
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Produk</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Harga</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Deskripsi</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Gambar</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($produk as $index => $item)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200"
                                x-show="searchTerm === '' || '{{ strtolower($item->nama_produk) }}'.includes(searchTerm.toLowerCase())">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->nama_produk }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $item->stok > 10 ? 'bg-green-100 text-green-800' :
                                            ($item->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $item->stok > 0 ? $item->stok . ' pcs' : 'Habis' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <span class="text-sm text-gray-500 truncate block" title="{{ $item->deskripsi }}">{{ $item->deskripsi }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/images/' . $item->gambar) }}" alt="{{ $item->nama_produk }}"
                                             class="w-12 h-12 object-cover rounded-lg mx-auto shadow-sm">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg mx-auto flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.edit', $item->id) }}"
                                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.destroy', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button @click="deleteForm = document.getElementById('delete-form-{{ $item->id }}'); productName = '{{ $item->nama_produk }}'; showDeleteModal = true;"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 rounded-full p-6 mb-4">
                                            <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Produk</h3>
                                        <p class="text-sm text-gray-500 mb-4">Klik tombol 'Tambah Produk' untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>Menampilkan {{ $produk->count() }} produk</span>
                    <span class="text-xs text-gray-500">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        @if(method_exists($produk, 'hasPages') && $produk->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $produk->links() }}
            </div>
        @endif
    </div>
</x-layout>