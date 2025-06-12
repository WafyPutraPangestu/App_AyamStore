<x-layout>
  <div class="min-h-screen bg-gray-50 py-8" x-data="{ activeTab: 'today', expandedOrders: {} }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Header Section -->
          <div class="mb-8">
              <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                      <a href="{{ route('admin.panel') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                          </svg>
                          Kembali ke Panel
                      </a>
                  </div>
              </div>
              
              <div class="mt-4">
                  <h1 class="text-3xl font-bold text-gray-900">Detail Kurir</h1>
                  <p class="text-gray-600 mt-1">Informasi lengkap dan performa kurir</p>
              </div>
          </div>

          <!-- Kurir Info Card -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
              <div class="p-6">
                  <div class="flex items-start justify-between">
                      <div class="flex items-center space-x-4">
                          <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                              {{ strtoupper(substr($kurir->user->name, 0, 2)) }}
                          </div>
                          <div>
                              <h2 class="text-xl font-semibold text-gray-900">{{ $kurir->user->name }}</h2>
                              <p class="text-gray-600">{{ $kurir->user->email }}</p>
                              <p class="text-sm text-gray-500 mt-1">
                                  <span class="inline-flex items-center">
                                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                      </svg>
                                      Login terakhir: {{ $lastLogin }}
                                  </span>
                              </p>
                          </div>
                      </div>
                      
                      <div class="text-right">
                          <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                              @if($kurir->status === 'tersedia') bg-green-100 text-green-800
                              @elseif($kurir->status === 'sedang_mengantar') bg-yellow-100 text-yellow-800
                              @else bg-red-100 text-red-800
                              @endif">
                              <div class="w-2 h-2 rounded-full mr-2
                                  @if($kurir->status === 'tersedia') bg-green-500
                                  @elseif($kurir->status === 'sedang_mengantar') bg-yellow-500
                                  @else bg-red-500
                                  @endif">
                              </div>
                              {{ ucfirst(str_replace('_', ' ', $kurir->status)) }}
                          </div>
                          @if($kurir->kendaraan_info)
                              <p class="text-sm text-gray-600 mt-2">
                                  <span class="inline-flex items-center">
                                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0a2 2 0 01-2-2v-2H8V7z"></path>
                                      </svg>
                                      {{ $kurir->kendaraan_info }}
                                  </span>
                              </p>
                          @endif
                      </div>
                  </div>
              </div>
          </div>

          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                  <div class="flex items-center">
                      <div class="p-2 bg-blue-100 rounded-lg">
                          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-gray-600">Order Hari Ini</p>
                          <p class="text-2xl font-semibold text-gray-900">{{ $totalOrdersToday }}</p>
                      </div>
                  </div>
              </div>

              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                  <div class="flex items-center">
                      <div class="p-2 bg-green-100 rounded-lg">
                          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-gray-600">Selesai Hari Ini</p>
                          <p class="text-2xl font-semibold text-gray-900">{{ $completedOrdersToday }}</p>
                      </div>
                  </div>
              </div>

              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                  <div class="flex items-center">
                      <div class="p-2 bg-purple-100 rounded-lg">
                          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-gray-600">Total Order</p>
                          <p class="text-2xl font-semibold text-gray-900">{{ $ordersAll->count() }}</p>
                      </div>
                  </div>
              </div>

              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                  <div class="flex items-center">
                      <div class="p-2 bg-orange-100 rounded-lg">
                          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <p class="text-sm font-medium text-gray-600">Tingkat Berhasil</p>
                          <p class="text-2xl font-semibold text-gray-900">
                              {{ $ordersAll->count() > 0 ? round(($ordersAll->where('status_pengiriman', 'terkirim')->count() / $ordersAll->count()) * 100) : 0 }}%
                          </p>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Tab Navigation -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <div class="border-b border-gray-200">
                  <nav class="flex space-x-8 px-6" aria-label="Tabs">
                      <button @click="activeTab = 'today'" 
                              :class="activeTab === 'today' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                          Order Hari Ini ({{ $totalOrdersToday }})
                      </button>
                      <button @click="activeTab = 'history'" 
                              :class="activeTab === 'history' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                              class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                          History Keseluruhan ({{ $ordersAll->count() }})
                      </button>
                  </nav>
              </div>

              <!-- Today's Orders Tab -->
              <div x-show="activeTab === 'today'" class="p-6">
                  @if($ordersToday->count() > 0)
                      <div class="space-y-4">
                          @foreach($ordersToday as $order)
                              <div class="bg-gray-50 rounded-lg border border-gray-200">
                                  <!-- Ringkasan Order (Selalu Tampil) -->
                                  <div class="p-4 cursor-pointer hover:bg-gray-100 transition-colors" 
                                       @click="expandedOrders['today-{{ $order->id }}'] = !expandedOrders['today-{{ $order->id }}']">
                                      <div class="flex items-center justify-between">
                                          <div class="flex-1">
                                              <div class="flex items-center space-x-4">
                                                  <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                                  <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                      @if($order->status_pengiriman === 'terkirim') bg-green-100 text-green-800
                                                      @elseif($order->status_pengiriman === 'sedang_diantar') bg-blue-100 text-blue-800
                                                      @elseif($order->status_pengiriman === 'menunggu_pickup') bg-yellow-100 text-yellow-800
                                                      @elseif($order->status_pengiriman === 'gagal_kirim') bg-red-100 text-red-800
                                                      @else bg-gray-100 text-gray-800
                                                      @endif">
                                                      {{ ucfirst(str_replace('_', ' ', $order->status_pengiriman)) }}
                                                  </div>
                                              </div>
                                              <p class="text-sm text-gray-600 mt-1">{{ $order->user->name }} • {{ $order->created_at->format('d M Y H:i') }}</p>
                                              <p class="text-sm text-gray-500">{{ $order->items->count() }} item(s)</p>
                                          </div>
                                          <div class="flex items-center space-x-4">
                                              <div class="text-right">
                                                  <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                              </div>
                                              <div class="transform transition-transform duration-200" 
                                                   :class="expandedOrders['today-{{ $order->id }}'] ? 'rotate-180' : ''">
                                                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                  </svg>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Detail Order (Tampil saat diklik) -->
                                  <div x-show="expandedOrders['today-{{ $order->id }}']" 
                                       x-transition:enter="transition ease-out duration-200"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="border-t border-gray-200 p-4 bg-white">
                                      
                                      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-4">
                                          <div>
                                              <h4 class="font-medium text-gray-900 mb-2">Alamat Pengiriman</h4>
                                              <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border">
                                                  {{ $order->alamat_pengiriman ?: 'Alamat tidak tersedia' }}
                                              </p>
                                          </div>

                                          <div>
                                              <h4 class="font-medium text-gray-900 mb-2">Detail Pembayaran</h4>
                                              <div class="bg-gray-50 p-3 rounded-lg border space-y-1">
                                                  <div class="flex justify-between text-sm">
                                                      <span class="text-gray-600">Subtotal:</span>
                                                      <span class="text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                                  </div>
                                                  <div class="flex justify-between text-sm">
                                                      <span class="text-gray-600">Ongkir:</span>
                                                      <span class="text-gray-900">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                                                  </div>
                                                  <div class="flex justify-between text-sm font-medium border-t pt-1">
                                                      <span class="text-gray-900">Total:</span>
                                                      <span class="text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      @if($order->items->count() > 0)
                                          <div class="mb-4">
                                              <h4 class="font-medium text-gray-900 mb-2">Item Pesanan</h4>
                                              <div class="bg-gray-50 rounded-lg border divide-y">
                                                  @foreach($order->items as $item)
                                                      <div class="p-3 flex items-center justify-between">
                                                          <div class="flex-1">
                                                              <p class="text-sm font-medium text-gray-900">
                                                                  {{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}
                                                              </p>
                                                              <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                                          </div>
                                                          <p class="text-sm font-medium text-gray-900">
                                                              Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}
                                                          </p>
                                                      </div>
                                                  @endforeach
                                              </div>
                                          </div>
                                      @endif

                                      @if($order->bukti_pengiriman)
                                          <div>
                                              <h4 class="font-medium text-gray-900 mb-2">Bukti Pengiriman</h4>
                                              <div class="bg-gray-50 p-3 rounded-lg border">
                                                  <img src="{{ asset('storage/'. $order->bukti_pengiriman) }}" 
                                                       alt="Bukti Pengiriman" 
                                                       class="max-w-xs h-auto rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                       onclick="window.open(this.src, '_blank')">
                                              </div>
                                          </div>
                                      @endif
                                  </div>
                              </div>
                          @endforeach
                      </div>
                  @else
                      <div class="text-center py-12">
                          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8v4a2 2 0 01-2 2H9a2 2 0 01-2-2V5a2 2 0 012-2h8a2 2 0 012 2z"></path>
                          </svg>
                          <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada order hari ini</h3>
                          <p class="mt-1 text-sm text-gray-500">Kurir belum mendapat order pada hari ini.</p>
                      </div>
                  @endif
              </div>

              <!-- History Tab -->
              <div x-show="activeTab === 'history'" class="p-6">
                  @if($ordersAll->count() > 0)
                      <div class="space-y-4">
                          @foreach($ordersAll as $order)
                              <div class="bg-gray-50 rounded-lg border border-gray-200">
                                  <!-- Ringkasan Order (Selalu Tampil) -->
                                  <div class="p-4 cursor-pointer hover:bg-gray-100 transition-colors" 
                                       @click="expandedOrders['history-{{ $order->id }}'] = !expandedOrders['history-{{ $order->id }}']">
                                      <div class="flex items-center justify-between">
                                          <div class="flex-1">
                                              <div class="flex items-center space-x-4">
                                                  <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                                  <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                      @if($order->status_pengiriman === 'terkirim') bg-green-100 text-green-800
                                                      @elseif($order->status_pengiriman === 'sedang_diantar') bg-blue-100 text-blue-800
                                                      @elseif($order->status_pengiriman === 'menunggu_pickup') bg-yellow-100 text-yellow-800
                                                      @elseif($order->status_pengiriman === 'gagal_kirim') bg-red-100 text-red-800
                                                      @else bg-gray-100 text-gray-800
                                                      @endif">
                                                      {{ ucfirst(str_replace('_', ' ', $order->status_pengiriman)) }}
                                                  </div>
                                              </div>
                                              <p class="text-sm text-gray-600 mt-1">{{ $order->user->name }} • {{ $order->created_at->format('d M Y H:i') }}</p>
                                              <p class="text-sm text-gray-500">{{ $order->items->count() }} item(s)</p>
                                          </div>
                                          <div class="flex items-center space-x-4">
                                              <div class="text-right">
                                                  <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                              </div>
                                              <div class="transform transition-transform duration-200" 
                                                   :class="expandedOrders['history-{{ $order->id }}'] ? 'rotate-180' : ''">
                                                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                  </svg>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Detail Order (Tampil saat diklik) -->
                                  <div x-show="expandedOrders['history-{{ $order->id }}']" 
                                       x-transition:enter="transition ease-out duration-200"
                                       x-transition:enter-start="opacity-0 transform scale-95"
                                       x-transition:enter-end="opacity-100 transform scale-100"
                                       x-transition:leave="transition ease-in duration-150"
                                       x-transition:leave-start="opacity-100 transform scale-100"
                                       x-transition:leave-end="opacity-0 transform scale-95"
                                       class="border-t border-gray-200 p-4 bg-white">
                                      
                                      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-4">
                                          <div>
                                              <h4 class="font-medium text-gray-900 mb-2">Alamat Pengiriman</h4>
                                              <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border">
                                                  {{ $order->alamat_pengiriman ?: 'Alamat tidak tersedia' }}
                                              </p>
                                          </div>

                                          <div>
                                              <h4 class="font-medium text-gray-900 mb-2">Detail Pembayaran</h4>
                                              <div class="bg-gray-50 p-3 rounded-lg border space-y-1">
                                                  <div class="flex justify-between text-sm">
                                                      <span class="text-gray-600">Subtotal:</span>
                                                      <span class="text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                                  </div>
                                                  <div class="flex justify-between text-sm">
                                                      <span class="text-gray-600">Ongkir:</span>
                                                      <span class="text-gray-900">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                                                  </div>
                                                  <div class="flex justify-between text-sm font-medium border-t pt-1">
                                                      <span class="text-gray-900">Total:</span>
                                                      <span class="text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      @if($order->items->count() > 0)
                                          <div class="mb-4">
                                              <h4 class="font-medium text-gray-900 mb-2">Item Pesanan</h4>
                                              <div class="bg-gray-50 rounded-lg border divide-y">
                                                  @foreach($order->items as $item)
                                                      <div class="p-3 flex items-center justify-between">
                                                          <div class="flex-1">
                                                              <p class="text-sm font-medium text-gray-900">
                                                                  {{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}
                                                              </p>
                                                              <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                                          </div>
                                                          <p class="text-sm font-medium text-gray-900">
                                                              Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}
                                                          </p>
                                                      </div>
                                                  @endforeach
                                              </div>
                                          </div>
                                      @endif

                                      @if($order->bukti_pengiriman)
                                          <div>
                                              <h4 class="font-medium text-gray-900 mb-2">Bukti Pengiriman</h4>
                                              <div class="bg-gray-50 p-3 rounded-lg border">
                                                  <img src="{{ asset('storage/'. $order->bukti_pengiriman) }}" 
                                                       alt="Bukti Pengiriman" 
                                                       class="max-w-xs h-auto rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                       onclick="window.open(this.src, '_blank')">
                                              </div>
                                          </div>
                                      @endif
                                  </div>
                              </div>
                          @endforeach
                      </div>
                  @else
                      <div class="text-center py-12">
                          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                          </svg>
                          <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada history order</h3>
                          <p class="mt-1 text-sm text-gray-500">Kurir belum pernah mendapat order.</p>
                      </div>
                  @endif
              </div>
          </div>
      </div>
  </div>
</x-layout>