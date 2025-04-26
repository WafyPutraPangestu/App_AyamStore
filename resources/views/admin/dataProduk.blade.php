<x-layout>
    {{-- Menggunakan notifikasi modern yang sudah kita buat sebelumnya --}}
    {{-- Pastikan komponen atau struktur HTML Alpine.js notifikasi Anda ada di layout atau di sini --}}
    {{-- Contoh struktur notifikasi Alpine.js: --}}
    @if (session('success'))
      <div
          x-data="{ show: true }"
          x-show="show"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-y-2"
          x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100 translate-y-0"
          x-transition:leave-end="opacity-0 translate-y-2"
          x-init="setTimeout(() => show = false, 3000)"
          class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-green-400 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg text-base md:bottom-8 md:right-8"
          style="display: none;"
      >
          <div class="flex items-center space-x-3">
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ session('success') }}</span>
          </div>
      </div>
    @endif
  
    {{-- Asumsikan Anda memiliki notifikasi error serupa --}}
    @if (session('error'))
      <div
          x-data="{ show: true }"
          x-show="show"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-y-2"
          x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100 translate-y-0"
          x-transition:leave-end="opacity-0 translate-y-2"
          x-init="setTimeout(() => show = false, 3000)"
          class="fixed bottom-6 right-6 z-50 bg-gradient-to-br from-red-400 to-red-600 text-white px-6 py-3 rounded-xl shadow-lg text-base md:bottom-8 md:right-8"
          style="display: none;"
      >
          <div class="flex items-center space-x-3">
              {{-- Ikon Error (Ganti SVG sesuai kebutuhan) --}}
               <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ session('error') }}</span>
          </div>
      </div>
    @endif
  
  
    <div class="container mx-auto p-4 md:p-6 lg:p-8 animate-fadeIn"> {{-- Padding responsif --}}
  
        {{-- Komponen Page Header: Sesuaikan styling di dalam komponennya --}}
        {{-- Rekomendasi: Padding vertikal yang baik, mungkin border bawah tipis, judul font semi-bold/bold --}}
        <x-page-header
            title="Data Produk Ayam"
            buttonLink="{{ route('admin.input') }}"
            buttonText="Tambah Produk"
        />
  
        {{-- Komponen Table Container: Beri styling card --}}
        {{-- Rekomendasi: Background putih (atau sesuai tema), sudut membulat (rounded), bayangan (shadow) --}}
        <x-table-container class="bg-white shadow-md rounded-lg overflow-hidden mt-6"> {{-- Tambahkan kelas card style --}}
  
            {{-- Search Bar: Sesuaikan styling di dalam komponennya --}}
            {{-- Rekomendasi: Input field dengan border halus, sudut membulat, ikon pencarian --}}
            <x-search-bar id="search" placeholder="Cari produk..." class="p-4 border-b border-gray-200" /> {{-- Tambahkan padding dan border bawah --}}
  
            {{-- Data Table --}}
            <x-data-table class="min-w-full leading-normal"> {{-- Pastikan lebar minimum dan leading --}}
                <x-slot:thead>
                    <tr> {{-- Tambahkan tr untuk row thead --}}
                        <th class="px-4 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12 rounded-tl-lg"> {{-- Background header, teks uppercase, ukuran font kecil, rounded corner kiri atas --}}
                            #
                        </th>
                        <th class="px-4 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Background header, teks uppercase, ukuran font kecil --}}
                            Produk
                        </th>
                        <th class="px-4 py-3 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Background header, teks uppercase, ukuran font kecil --}}
                            Harga
                        </th>
                        <th class="px-4 py-3 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Background header, teks uppercase, ukuran font kecil --}}
                            Stok
                        </th>
                        <th class="px-4 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Background header, teks uppercase, ukuran font kecil --}}
                            Deskripsi
                        </th>
                        <th class="px-4 py-3 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"> {{-- Background header, teks uppercase, ukuran font kecil --}}
                            Gambar
                        </th>
                        <th class="px-4 py-3 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tr-lg"> {{-- Background header, teks uppercase, ukuran font kecil, rounded corner kanan atas --}}
                            Aksi
                        </th>
                    </tr>
                </x-slot:thead>
  
                <tbody> {{-- Tambahkan tbody --}}
                    @forelse ($produk as $item)
                        <tr class="hover:bg-blue-50 text-gray-800 border-b border-gray-200 animate-fadeIn"> {{-- Hover lebih lembut, warna teks sedikit lebih gelap, border bawah sedikit lebih menonjol --}}
                            <td class="px-4 py-3 text-center text-sm"> {{-- Ukuran teks cell --}}
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 font-medium text-sm"> {{-- Ukuran teks cell --}}
                                {{ $item->nama_produk }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm"> {{-- Ukuran teks cell --}}
                                RP {{ number_format($item->harga,0, ',', '.')}}
                            </td>
                            <td class="px-4 py-3 text-center text-sm"> {{-- Ukuran teks cell --}}
                                {{-- Styling badge stok sudah cukup modern --}}
                                <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold"> {{-- Ubah text-sm badge menjadi text-xs agar proporsional --}}
                                    {{ $item->stok }} pcs
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-[200px] truncate text-gray-600 text-sm" title="{{ $item->deskripsi }}"> {{-- Warna teks deskripsi sedikit lebih redup, ukuran teks cell --}}
                                {{ $item->deskripsi }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{-- Komponen Product Image: Sesuaikan styling di dalam komponennya --}}
                                {{-- Rekomendasi: Thumbnail kecil (misal w-12 h-12 object-cover), mungkin rounded corners --}}
                                <x-product-image :image="$item->gambar" :alt="$item->nama" class="w-12 h-12 object-cover rounded-md" /> {{-- Contoh styling thumbnail --}}
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{-- Komponen Action Buttons: Sesuaikan styling di dalam komponennya --}}
                                {{-- Rekomendasi: Tombol ikon kecil, spacing antar tombol, hover state --}}
                                <x-action-buttons
                                    :editRoute="route('admin.edit', $item->id)"
                                    :deleteRoute="route('admin.destroy', $item->id)"
                                    confirmMessage="Apakah Anda yakin ingin menghapus produk ini?"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"> {{-- Sesuaikan colspan dengan jumlah kolom --}}
                                {{-- Komponen Empty State: Sesuaikan styling di dalam komponennya --}}
                                {{-- Rekomendasi: Teks terpusat, warna teks abu-abu, margin vertikal cukup --}}
                                <x-empty-state
                                    title="Belum ada data produk"
                                    message="Mulai tambahkan produk pertama Anda"
                                    class="py-8 text-center text-gray-500" {{-- Contoh styling empty state --}}
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody> {{-- Tutup tbody --}}
            </x-data-table>
        </x-table-container>
  
        {{-- Komponen Pagination Wrapper: Beri margin top --}}
        {{-- Rekomendasi: Spacing antar tombol pagination, warna aktif/hover yang jelas --}}
        <x-pagination-wrapper class="mt-6"> {{-- Tambahkan margin top --}}
            {{ $produk->links() }}
        </x-pagination-wrapper>
    </div>
  
    @push('scripts')
        @vite('resources/js/product-search.js')
        {{-- Pastikan file app.js yang berisi logika notifikasi Alpine.js juga di-include --}}
        {{-- @vite('resources/js/app.js') atau sesuai cara Anda meng-include global JS --}}
    @endpush
  
    @push('styles')
        @vite('resources/css/animations.css')
        {{-- Pastikan file app.css yang berisi styling notifikasi kustom (jika ada backdrop-filter) dan styling Tailwind di-include --}}
        {{-- @vite('resources/css/app.css') atau sesuai cara Anda meng-include global CSS --}}
    @endpush
  </x-layout>