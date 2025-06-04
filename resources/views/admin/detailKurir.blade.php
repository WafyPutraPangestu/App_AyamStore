<x-layout>
  {{-- Meta viewport untuk responsif --}}
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  {{-- Alpine.js untuk interaktivitas --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

  <div class="container mx-auto px-4 py-6" x-data="{ activeTab: 'today' }">
    {{-- Header dengan Breadcrumb --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <nav class="flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a href="{{ route('admin.panel') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Panel Kurir
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Kurir</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-3xl font-bold mt-2">Detail Kurir</h1>
      </div>
      <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
        ← Kembali
      </button>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="h-20 w-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
              <span class="text-2xl font-bold text-white">
                {{ strtoupper(substr($kurir->user->name, 0, 2)) }}
              </span>
            </div>
          </div>
          <div class="ml-6">
            <h2 class="text-2xl font-bold text-white">{{ $kurir->user->name }}</h2>
            <div class="mt-2">
              <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                {{ $kurir->status === 'aktif' ? 'bg-green-100 text-green-800'
                   : ($kurir->status === 'tidak_aktif' ? 'bg-red-100 text-red-800'
                   : 'bg-yellow-100 text-yellow-800') }}">
                {{ ucfirst(str_replace('_', ' ', $kurir->status)) }}
              </span>
            </div>
          </div>
        </div>
      </div>
      
      {{-- Stats Grid --}}
      <div class="px-6 py-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-3xl font-bold text-blue-600">{{ $totalOrdersToday }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Order Hari Ini</div>
          </div>
          <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="text-3xl font-bold text-green-600">{{ $completedOrdersToday }}</div>
            <div class="text-sm text-gray-600 mt-1">Order Selesai Hari Ini</div>
          </div>
          <div class="text-center p-4 bg-purple-50 rounded-lg">
            <div class="text-lg font-semibold text-purple-600">{{ $lastLogin }}</div>
            <div class="text-sm text-gray-600 mt-1">Login Terakhir</div>
          </div>
        </div>
        
        {{-- Performance Bar --}}
        @if($totalOrdersToday > 0)
          <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-gray-700">Tingkat Penyelesaian Hari Ini</span>
              <span class="text-sm font-bold text-gray-900">
                {{ number_format(($completedOrdersToday / $totalOrdersToday) * 100, 1) }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" 
                   style="width: {{ ($completedOrdersToday / $totalOrdersToday) * 100 }}%"></div>
            </div>
          </div>
        @endif
      </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
          <button @click="activeTab = 'today'" 
                  :class="activeTab === 'today' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                  class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Order Hari Ini
              <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ $ordersToday->count() }}
              </span>
            </div>
          </button>
          <button @click="activeTab = 'history'" 
                  :class="activeTab === 'history' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                  class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              Semua History
              <span class="ml-2 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ $ordersAll->count() }}
              </span>
            </div>
          </button>
        </nav>
      </div>

      {{-- Tab Content --}}
      <div class="p-6">
        {{-- Order Hari Ini Tab --}}
        <div x-show="activeTab === 'today'" x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 transform translate-y-2" 
             x-transition:enter-end="opacity-100 transform translate-y-0">
          @if ($ordersToday->count())
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Order ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tanggal Update
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  @foreach ($ordersToday as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                              <span class="text-xs font-medium text-blue-800">#{{ $order->id }}</span>
                            </div>
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->updated_at->format('d M Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $order->updated_at->format('H:i') }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                          {{ $order->status_pengiriman === 'terkirim' ? 'bg-green-100 text-green-800'
                             : ($order->status_pengiriman === 'dalam_pengiriman' ? 'bg-blue-100 text-blue-800'
                             : ($order->status_pengiriman === 'dibatalkan' ? 'bg-red-100 text-red-800'
                             : 'bg-yellow-100 text-yellow-800')) }}">
                          {{ ucfirst(str_replace('_', ' ', $order->status_pengiriman)) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 transition-colors duration-150">
                          Detail
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-12">
              <div class="flex flex-col items-center justify-center">
                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada order hari ini</h3>
                <p class="text-sm text-gray-500">Kurir belum memiliki order yang dikirim hari ini.</p>
              </div>
            </div>
          @endif
        </div>

        {{-- History Semua Order Tab --}}
        <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 transform translate-y-2" 
             x-transition:enter-end="opacity-100 transform translate-y-0">
          @if ($ordersAll->count())
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Order ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Tanggal Update
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Aksi
                    </th> --}}
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  @foreach ($ordersAll as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                              <span class="text-xs font-medium text-gray-600">#{{ $order->id }}</span>
                            </div>
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->updated_at->format('d M Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $order->updated_at->format('H:i') }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                          {{ $order->status_pengiriman === 'terkirim' ? 'bg-green-100 text-green-800'
                             : ($order->status_pengiriman === 'dalam_pengiriman' ? 'bg-blue-100 text-blue-800'
                             : ($order->status_pengiriman === 'dibatalkan' ? 'bg-red-100 text-red-800'
                             : 'bg-yellow-100 text-yellow-800')) }}">
                          {{ ucfirst(str_replace('_', ' ', $order->status_pengiriman)) }}
                        </span>
                      </td>
                      {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 transition-colors duration-150">
                          Detail
                        </button>
                      </td> --}}
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-12">
              <div class="flex flex-col items-center justify-center">
                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada riwayat order</h3>
                <p class="text-sm text-gray-500">Kurir belum memiliki riwayat pengiriman order.</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-layout>