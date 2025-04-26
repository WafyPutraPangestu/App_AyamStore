<x-layout>
    {{-- Notifikasi Alpine.js (Sudah Cukup Modern) --}}
    @if (session('success'))
      <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-green-400 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg text-base md:bottom-8 md:right-8" style="display: none;">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
      </div>
    @endif
    @if (session('error'))
     <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-red-400 to-red-600 text-white px-6 py-3 rounded-xl shadow-lg text-base md:bottom-8 md:right-8" style="display: none;">
       <div class="flex items-center space-x-3">
           <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
           <span>{{ session('error') }}</span>
       </div>
     </div>
    @endif


    {{-- Container utama dengan padding yang lebih konsisten --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Page Header (Sudah diupdate di komponennya) --}}
        <x-page-header
            title="Data Produk Ayam"
            buttonLink="{{ route('admin.input') }}"
            buttonText="Tambah Produk"
        />

        {{-- Table Container (Sudah cukup baik) --}}
        <x-table-container class="bg-white shadow-lg rounded-lg overflow-hidden mt-6 border border-gray-200">

            {{-- Search Bar (Sudah diupdate di komponennya) --}}
            <x-search-bar id="search" placeholder="Cari produk..." />

            {{-- Data Table --}}
            <div class="overflow-x-auto"> {{-- Tambahkan overflow-x-auto untuk tabel responsif --}}
                <x-data-table class="min-w-full divide-y divide-gray-200"> {{-- Gunakan divide untuk garis antar baris --}}
                    <x-slot:thead>
                        {{-- Header lebih bersih --}}
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </x-slot:thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($produk as $index => $item)
                            {{-- Tambahkan x-data, x-init, x-show, x-transition pada <tr> --}}
                            <tr x-data="{ show: false }"
                                x-init="() => { setTimeout(() => show = true, {{ $index * 50 }}) }" {{-- Delay stagger --}}
                                x-show="show"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="hover:bg-gray-50 text-gray-900" {{-- Hover lebih standar --}}
                                style="display: none;" {{-- Mulai tersembunyi --}}
                            >
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ ($produk->currentPage() - 1) * $produk->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $item->nama_produk }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    {{-- Badge Stok --}}
                                    <span @class([
                                        'px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $item->stok > 10,
                                        'bg-amber-100 text-amber-800' => $item->stok > 0 && $item->stok <= 10,
                                        'bg-red-100 text-red-800' => $item->stok <= 0,
                                    ])>
                                        {{ $item->stok }} {{ $item->stok > 0 ? 'pcs' : 'Habis' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs text-sm text-gray-500 truncate" title="{{ $item->deskripsi }}">{{ $item->deskripsi }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    {{-- Gambar Produk (pastikan komponennya ada) --}}
                                     <x-product-image :image="$item->gambar" :alt="$item->nama_produk" class="w-10 h-10 object-cover rounded-md inline-block" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    {{-- Tombol Aksi (pastikan komponennya ada dan styled) --}}
                                    <x-action-buttons
                                        :editRoute="route('admin.edit', $item->id)"
                                        :deleteRoute="route('admin.destroy', $item->id)"
                                        confirmMessage="Apakah Anda yakin ingin menghapus produk {{ $item->nama_produk }}?"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                     {{-- Empty State (pastikan komponennya ada) --}}
                                     <x-empty-state
                                        title="Belum Ada Data Produk"
                                        message="Klik tombol 'Tambah Produk' untuk memulai."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-data-table>
            </div> {{-- End overflow-x-auto --}}

        </x-table-container>

        {{-- Pagination (Sudah cukup baik, pastikan view paginationnya modern) --}}
        <x-pagination-wrapper class="mt-6">
            {{ $produk->links() }}
        </x-pagination-wrapper>
    </div>

    @push('scripts')
        @vite('resources/js/product-search.js') {{-- Pastikan ini ada dan berfungsi --}}
        {{-- Pastikan Alpine.js sudah di-load, biasanya di layout utama atau app.js --}}
    @endpush

    {{-- Hapus push styles jika animasi sudah didefinisikan di app.css --}}
    {{-- @push('styles')
        @vite('resources/css/animations.css')
    @endpush --}}
</x-layout>