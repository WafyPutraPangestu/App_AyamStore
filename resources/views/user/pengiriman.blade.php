<x-layout>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 py-8 px-4" 
       x-data="{
         orderId: {{ $order->id ?? 'null' }},
         notified: {{ $order->status_pengiriman === 'terkirim' ? 'true' : 'false' }},
         showNotification: {{ $order->status_pengiriman === 'terkirim' ? 'true' : 'false' }},
         showTracking: {{ $order->status_pengiriman !== 'terkirim' ? 'true' : 'false' }},
         statusPengiriman: '{{ $order->status_pengiriman ?? '' }}',
         
         init() {
           if (this.orderId) {
             this.startPolling();
           }
         },
         
         startPolling() {
           setInterval(() => {
             fetch(`{{ url('user/pengiriman') }}/${this.orderId}/status`)
               .then(response => response.json())
               .then(data => {
                 this.statusPengiriman = data.status_pengiriman;
                 
                 if (data.status_pengiriman === 'terkirim' && !this.notified) {
                   this.notified = true;
                   this.showNotification = true;
                   this.showTracking = false;
                   setTimeout(() => location.reload(), 1500);
                 }
               })
               .catch(error => console.error('Error:', error));
           }, 10000);
         },
         
         closeNotification() {
           this.showNotification = false;
         }
       }">
    
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-4xl font-bold text-blue-800 mb-2">Tracking Pengiriman</h1>
      <div class="w-24 h-1 bg-blue-500 mx-auto rounded-full"></div>
    </div>

    @if($order)
      <!-- Notifikasi Terkirim -->
      <div x-show="showNotification" 
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 transform scale-95"
           x-transition:enter-end="opacity-100 transform scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 transform scale-100"
           x-transition:leave-end="opacity-0 transform scale-95"
           class="max-w-2xl mx-auto mb-6 relative"
           role="alert" 
           aria-live="assertive">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl shadow-2xl p-6 text-center relative overflow-hidden">
          <!-- Background decoration -->
          <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
          <div class="absolute bottom-0 left-0 -mb-6 -ml-6 w-32 h-32 bg-white bg-opacity-5 rounded-full"></div>
          
          <div class="relative z-10">
            <div class="flex items-center justify-center mb-4">
              <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
            </div>
            <p class="text-lg mb-2">Produk Anda sudah</p>
            <p class="text-3xl font-bold mb-4">TERKIRIM!</p>
            <button @click="closeNotification()" 
                    class="absolute top-4 right-4 w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white transition-all duration-200"
                    aria-label="Tutup notifikasi">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Tracking Container -->
      <div x-show="showTracking" 
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 transform translate-y-4"
           x-transition:enter-end="opacity-100 transform translate-y-0"
           class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100">
          <!-- Status Header -->
          <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-black p-6 text-center">
            <div class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold"
                 :class="statusPengiriman === 'terkirim' ? 'bg-white bg-opacity-20' : 'bg-white bg-opacity-20'">
              <span x-text="statusPengiriman.toUpperCase()">{{ strtoupper($order->status_pengiriman) }}</span>
            </div>
          </div>

          <!-- Tracking Info -->
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Kurir -->
              <div class="space-y-2">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <label class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Kurir</label>
                </div>
                <p class="text-lg text-gray-800 font-medium">{{ $order->kurir?->user->name ?? 'Belum ada kurir' }}</p>
              </div>

              <!-- Ongkir -->
              <div class="space-y-2">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                  <label class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Ongkir</label>
                </div>
                <p class="text-lg text-gray-800 font-medium">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
              </div>

              <!-- Alamat Pengiriman -->
              <div class="md:col-span-2 space-y-2">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  <label class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Alamat Pengiriman</label>
                </div>
                <p class="text-lg text-gray-800 leading-relaxed">{{ $order->alamat_pengiriman }}</p>
              </div>

              <!-- Total Harga -->
              <div class="md:col-span-2 space-y-2">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                  <label class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Total Harga</label>
                </div>
                <p class="text-2xl text-blue-700 font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Riwayat jika sudah terkirim -->
      @if($order->status_pengiriman === 'terkirim')
        <div class="max-w-2xl mx-auto mt-6">
          <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl overflow-hidden border-2 border-blue-300">
            <!-- Status Header -->
            <div class="bg-white bg-opacity-20 text-black p-6 text-center">
              <div class="inline-flex items-center px-6 py-3 bg-white bg-opacity-30 rounded-full text-lg font-bold">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                TERKIRIM
              </div>
            </div>

            <!-- Riwayat Info -->
            <div class="p-6 text-white">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kurir -->
                <div class="space-y-2">
                  <label class="text-sm font-semibold text-blue-100 uppercase tracking-wide">Kurir</label>
                  <p class="text-lg font-medium">{{ $order->kurir?->user->name ?? 'Belum ada kurir' }}</p>
                </div>

                <!-- Ongkir -->
                <div class="space-y-2">
                  <label class="text-sm font-semibold text-blue-100 uppercase tracking-wide">Ongkir</label>
                  <p class="text-lg font-medium">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</p>
                </div>

                <!-- Alamat Pengiriman -->
                <div class="md:col-span-2 space-y-2">
                  <label class="text-sm font-semibold text-blue-100 uppercase tracking-wide">Alamat Pengiriman</label>
                  <p class="text-lg leading-relaxed">{{ $order->alamat_pengiriman }}</p>
                </div>

                <!-- Total Harga -->
                <div class="md:col-span-2 space-y-2">
                  <label class="text-sm font-semibold text-blue-100 uppercase tracking-wide">Total Harga</label>
                  <p class="text-2xl font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Riwayat Keseluruhan Pengiriman -->
      <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-blue-100">
          <!-- Header Riwayat -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
            <div class="flex items-center space-x-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h2 class="text-xl font-bold">Riwayat Pengiriman</h2>
            </div>
          </div>

          <!-- Timeline Content -->
          <div class="p-6">
            <div class="relative">
              <!-- Timeline Line -->
              <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-blue-200"></div>

              <!-- Timeline Items -->
              <div class="space-y-6">
                <!-- Pesanan Dibuat -->
                <div class="relative flex items-start space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0 pb-4">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-blue-600">Pesanan Dibuat</p>
                      <time class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</time>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Pesanan Anda telah berhasil dibuat dan menunggu konfirmasi</p>
                  </div>
                </div>

                <!-- Pesanan Dikonfirmasi -->
                <div class="relative flex items-start space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0 pb-4">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-blue-600">Pesanan Dikonfirmasi</p>
                      <time class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</time>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Pesanan telah dikonfirmasi dan sedang diproses</p>
                  </div>
                </div>

                <!-- Kurir Ditugaskan -->
                @if($order->kurir)
                <div class="relative flex items-start space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0 pb-4">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-blue-600">Kurir Ditugaskan</p>
                      <time class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</time>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $order->kurir->user->name }} telah ditugaskan sebagai kurir</p>
                  </div>
                </div>
                @endif

                <!-- Sedang Dikirim -->
                @if(in_array($order->status_pengiriman, ['dikirim', 'terkirim']))
                <div class="relative flex items-start space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                    <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0 pb-4">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-blue-600">Sedang Dikirim</p>
                      <time class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</time>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Paket sedang dalam perjalanan menuju alamat tujuan</p>
                  </div>
                </div>
                @endif

                <!-- Paket Terkirim -->
                @if($order->status_pengiriman === 'terkirim')
                <div class="relative flex items-start space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0 pb-4">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-green-600">Paket Terkirim</p>
                      <time class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</time>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Paket telah berhasil dikirim dan diterima</p>
                  </div>
                </div>
                @else
                <!-- Status Saat Ini (jika belum terkirim) -->
                <div class="relative flex items-start space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center border-4 border-white shadow-md animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0 pb-4">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-yellow-600">Status Saat Ini</p>
                      <time class="text-xs text-gray-500">Live</time>
                    </div>
                    <p class="text-sm text-gray-600 mt-1" x-text="`Status: ${statusPengiriman.toUpperCase()}`">Status: {{ strtoupper($order->status_pengiriman) }}</p>
                  </div>
                </div>
                @endif
              </div>
            </div>

            <!-- Estimasi Waktu -->
            @if($order->status_pengiriman !== 'terkirim')
            <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
              <div class="flex items-center space-x-2 mb-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-blue-700">Estimasi Pengiriman</h3>
              </div>
              <p class="text-sm text-blue-600">Paket diperkirakan akan sampai dalam 1-3 hari kerja</p>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Informasi Tambahan -->
      <div class="max-w-2xl mx-auto mt-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kontak Customer Service -->
            <div class="text-center md:text-left">
              <div class="flex items-center justify-center md:justify-start space-x-2 mb-3">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <h3 class="font-semibold text-blue-700">Butuh Bantuan?</h3>
              </div>
              <p class="text-sm text-gray-600">Hubungi customer service kami</p>
              <p class="text-sm font-medium text-blue-600">+62 812-3456-7890</p>
            </div>

            <!-- Tips Pengiriman -->
            <div class="text-center md:text-left">
              <div class="flex items-center justify-center md:justify-start space-x-2 mb-3">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-semibold text-blue-700">Tips</h3>
              </div>
              <p class="text-sm text-gray-600">Pastikan ada yang menerima paket</p>
              <p class="text-sm text-gray-600">di alamat tujuan saat pengiriman</p>
            </div>
          </div>
        </div>
      </div>

    @else
      <!-- No Order Found -->
      <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center border border-blue-100">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
          </div>
          <p class="text-lg text-gray-600">Belum ada pesanan yang ditemukan.</p>
        </div>
      </div>
    @endif
  </div>
</x-layout>