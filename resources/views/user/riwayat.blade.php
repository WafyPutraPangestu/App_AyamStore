<x-layout>
  {{-- Judul Halaman --}}
  <h1 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-6 sm:mb-8">Riwayat Pesanan Anda</h1>

  {{-- Cek jika ada riwayat --}}
  @if($riwayat->count() > 0)
      <div class="space-y-6"> {{-- Beri jarak antar kartu --}}
          @foreach($riwayat as $pembayaran)
              {{-- Kartu Pesanan --}}
              <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out overflow-hidden border border-gray-200">
                  {{-- Header Kartu --}}
                  <div class="p-4 sm:p-6 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                      <div>
                          <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Order ID: <span class="text-gray-800">{{ $pembayaran->order->id }}</span></h3>
                          <p class="text-xs text-gray-500 mt-1">Tanggal Pesan: {{ $pembayaran->order->created_at->translatedFormat('l, d F Y H:i') }}</p> {{-- Format tanggal lebih baik --}}
                          <p class="text-xs text-gray-500">Nama User: {{ $pembayaran->order->user->name }}</p>
                      </div>
                      <div class="text-left sm:text-right">
                          {{-- Status Pembayaran (Pindahkan ke sini) --}}
                          <span @class([
                              'inline-block px-3 py-1 text-xs font-semibold rounded-full leading-tight',
                              'bg-green-100 text-green-800' => strtolower($pembayaran->status) == 'paid' || strtolower($pembayaran->status) == 'success' || strtolower($pembayaran->status) == 'settlement', // Sesuaikan dengan status Anda
                              'bg-yellow-100 text-yellow-800' => strtolower($pembayaran->status) == 'pending',
                              'bg-red-100 text-red-800' => strtolower($pembayaran->status) == 'failed' || strtolower($pembayaran->status) == 'expire',
                              'bg-blue-100 text-blue-800' => strtolower($pembayaran->status) == 'shipping', // Contoh status lain
                              'bg-gray-100 text-gray-800' => !in_array(strtolower($pembayaran->status), ['paid', 'success', 'settlement', 'pending', 'failed', 'expire', 'shipping']) // Default
                          ])>
                              {{ ucfirst($pembayaran->status) }}
                          </span>
                           <p class="text-sm text-gray-500 mt-1">Tanggal Bayar: {{ $pembayaran->created_at ? $pembayaran->created_at->translatedFormat('d M Y, H:i') : '-' }}</p> {{-- Tampilkan jika ada --}}
                      </div>
                  </div>

                  {{-- Daftar Item --}}
                  <div class="p-4 sm:p-6">
                      <ul class="space-y-4">
                          @foreach($pembayaran->order->items as $item)
                              <li class="flex items-start space-x-4">
                                  {{-- Gambar Produk (Opsional tapi bagus) --}}
                                  <div class="flex-shrink-0">
                                      @if($item->produk->gambar_url) {{-- Asumsi ada field gambar_url di model Produk --}}
                                        <img src="{{ $item->produk->gambar_url }}" alt="{{ $item->produk->nama_produk }}" class="w-16 h-16 object-cover rounded-md border border-gray-200">
                                      @else
                                        {{-- Placeholder jika tidak ada gambar --}}
                                        <div class="w-16 h-16 bg-gray-100 rounded-md border border-gray-200 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                      @endif
                                  </div>

                                  {{-- Detail Item --}}
                                  <div class="flex-grow">
                                      <p class="text-sm font-medium text-gray-900">{{ $item->produk->nama_produk }}</p>
                                      <p class="text-xs text-gray-600">Jumlah: {{ $item->quantity }}</p>
                                  </div>

                                  {{-- Harga Item --}}
                                  <div class="text-right flex-shrink-0">
                                      <p class="text-sm font-medium text-gray-800">Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}</p>
                                      <p class="text-xs text-gray-500">(Rp {{ number_format($item->produk->harga, 0, ',', '.') }}/item)</p>
                                  </div>
                              </li>
                          @endforeach
                      </ul>
                  </div>

                  {{-- Footer Kartu (Total Harga & Mungkin Tombol Aksi) --}}
                  <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200 flex justify-end items-center">
                      {{-- <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 ease-in-out">Lihat Detail Pesanan</a> --}}
                      <div class="text-right">
                          <p class="text-sm text-gray-600">Total Pesanan:</p>
                          <p class="text-lg font-bold text-indigo-700">Rp {{ number_format($pembayaran->order->total_harga, 0, ',', '.') }}</p>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>

      {{-- Paginasi --}}
      <div class="mt-8">
           {{-- Pastikan Anda telah mempublish view pagination dan menyesuaikannya dengan Tailwind --}}
           {{-- Jika belum, jalankan: php artisan vendor:publish --tag=laravel-pagination --}}
           {{-- Kemudian edit file di resources/views/vendor/pagination/tailwind.blade.php --}}
           {{ $riwayat->links() }}
      </div>

  @else
      {{-- Tampilan jika tidak ada riwayat --}}
      <div class="text-center py-12 px-6 bg-white rounded-lg shadow border border-gray-200">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">Riwayat Pesanan Kosong</h3>
          <p class="mt-1 text-sm text-gray-500">Anda belum melakukan pemesanan apapun.</p>
          <div class="mt-6">
              <a href="/" {{-- Arahkan ke halaman belanja --}}
                 class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                  <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Mulai Belanja
              </a>
          </div>
      </div>
  @endif

</x-layout>