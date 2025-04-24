<x-layout>
  <div class="container mx-auto p-6 animate-fadeIn">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

      <x-page-header 
          title="Data Produk Ayam" 
          buttonLink="{{ route('admin.input') }}" 
          buttonText="Tambah Produk" 
      />
      <x-table-container>
          <x-search-bar id="search" placeholder="Cari produk..." />
          <x-data-table>
              <x-slot:thead>
                  <th class="px-4 py-3 font-semibold text-center w-12">#</th>
                  <th class="px-4 py-3 font-semibold text-left">Produk</th>
                  
                  <th class="px-4 py-3 font-semibold text-right">Harga</th>
                  <th class="px-4 py-3 font-semibold text-center">Stok</th>
                  <th class="px-4 py-3 font-semibold text-left">Deskripsi</th>
                  <th class="px-4 py-3 font-semibold text-center">Gambar</th>
                  <th class="px-4 py-3 font-semibold text-center">Aksi</th>
              </x-slot:thead>
              
              @forelse ($produk as $item)
              <tr class="hover:bg-blue-500/20 text-gray-700 border-b border-gray-100 animate-fadeIn">
                  <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                  <td class="px-4 py-3 font-medium">{{ $item->nama_produk }}</td>
                  <td class="px-4 py-3 text-right">RP {{ number_format($item->harga,0, ',', '.')}}</td>
                  <td class="px-4 py-3 text-center">
                      <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-800 text-sm">
                          {{ $item->stok }} pcs
                      </span>
                  </td>
                  <td class="px-4 py-3 max-w-[200px] truncate" title="{{ $item->deskripsi }}">
                      {{ $item->deskripsi }}
                  </td>
                  <td class="px-4 py-3">
                      <x-product-image :image="$item->gambar" :alt="$item->nama" />
                  </td>
                  <td class="px-4 py-3">
                      <x-action-buttons 
                          :editRoute="route('admin.edit', $item->id)"
                          :deleteRoute="route('admin.destroy', $item->id)"
                          confirmMessage="Apakah Anda yakin ingin menghapus produk ini?"
                      />
                  </td>
              </tr>
              @empty
              <tr>
                  <td colspan="9">
                      <x-empty-state 
                          title="Belum ada data produk"
                          message="Mulai tambahkan produk pertama Anda"
                      />
                  </td>
              </tr>
              @endforelse
          </x-data-table>
      </x-table-container>

      <!-- Pagination -->
      <x-pagination-wrapper>
          {{ $produk->links() }}
      </x-pagination-wrapper>
  </div>
  
  @push('scripts')
      @vite('resources/js/product-search.js')
  @endpush
  
  @push('styles')
      @vite('resources/css/animations.css')
  @endpush
</x-layout>