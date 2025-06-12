{{-- resources/views/admin/panel.blade.php --}}

<x-layout>
  {{-- Meta viewport untuk responsif --}}
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  {{-- Alpine.js untuk interaktivitas --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

  <div class="container mx-auto px-4 py-6" x-data="{ searchTerm: '', selectedStatus: 'all', showPerformanceDetails: false }">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Panel Kurir</h1>
        <p class="text-gray-600 mt-1">Kelola dan monitor performance kurir Anda</p>
      </div>
    </div>

    {{-- Enhanced Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Kurir</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ count($kurirs) }}</p>
            <div class="flex items-center mt-2">
              <span class="text-xs text-gray-400">Terdaftar dalam sistem</span>
            </div>
          </div>
          <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kurir Aktif</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
              {{ collect($kurirs)->whereIn('status_kurir', ['tersedia', 'sedang_mengantar'])->count() }}
            </p>            
            <div class="flex items-center mt-2">
              <span class="text-xs text-green-600 font-medium">
                {{ count($kurirs) > 0 ? number_format((collect($kurirs)->whereIn('status_kurir', ['tersedia', 'sedang_mengantar'])->count() / count($kurirs)) * 100, 1) : 0 }}% dari total
              </span>
            </div>
          </div>
          <div class="bg-gradient-to-br from-green-100 to-green-200 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Order</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">
              {{ collect($kurirs)->sum('total_order') }}
            </p>
            <div class="flex items-center mt-2">
              <span class="text-xs text-gray-400">Order hari ini</span>
            </div>
          </div>
          <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Order Selesai</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">
              {{ collect($kurirs)->sum('order_selesai') }}
            </p>
            <div class="flex items-center mt-2">
              <span class="text-xs text-purple-600 font-medium">
                {{ collect($kurirs)->sum('total_order') > 0 ? number_format((collect($kurirs)->sum('order_selesai') / collect($kurirs)->sum('total_order')) * 100, 1) : 0 }}% completion rate
              </span>
            </div>
          </div>
          <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </div>
      </div>

      {{-- New Performance Card --}}
      <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Avg Performance</p>
            <p class="text-3xl font-bold text-indigo-600 mt-2">
              {{ collect($kurirs)->where('performance.performance_score', '>', 0)->avg('performance.performance_score') ? number_format(collect($kurirs)->where('performance.performance_score', '>', 0)->avg('performance.performance_score'), 1) : 0 }}
            </p>
            <div class="flex items-center mt-2">
              <span class="text-xs text-indigo-600 font-medium">Performance Score</span>
            </div>
          </div>
          <div class="bg-gradient-to-br from-indigo-100 to-indigo-200 p-3 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter dan Search Section --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="flex items-center space-x-4">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input x-model="searchTerm" type="text" placeholder="Cari nama kurir..." 
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
          </div>
          
          <select x-model="selectedStatus" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
            <option value="all">Semua Status</option>
            <option value="tersedia">Tersedia</option>
            <option value="tidak_aktif">Tidak Aktif</option>
            <option value="sedang_mengantar">Sedang Mengantar</option>
          </select>

          <button @click="showPerformanceDetails = !showPerformanceDetails" 
                  class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm hover:bg-indigo-200 transition-colors duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span x-text="showPerformanceDetails ? 'Sembunyikan Detail' : 'Tampilkan Performance'"></span>
          </button>
        </div>
        
        <div class="flex items-center space-x-2 text-sm text-gray-600">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>Total <span class="font-semibold">{{ count($kurirs) }}</span> kurir</span>
        </div>
      </div>
    </div>

    {{-- Enhanced Tabel Data Kurir --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold text-gray-900 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Data Kurir & Performance
          </h2>
          <div class="flex items-center space-x-2 text-sm text-gray-500">
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span>Tersedia</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
              <span>Tidak Aktif</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
              <span>Sedang Mengantar</span>
            </div>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Nama Kurir
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Performance Hari Ini
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" x-show="showPerformanceDetails">
                Performance 30 Hari
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Login Terakhir
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($kurirs as $kurir)
              <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200"
                  x-show="(searchTerm === '' || '{{ strtolower($kurir['nama']) }}'.includes(searchTerm.toLowerCase())) && 
                          (selectedStatus === 'all' || selectedStatus === '{{ $kurir['status_kurir'] }}')">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                      <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                        <span class="text-sm font-bold text-white">
                          {{ strtoupper(substr($kurir['nama'], 0, 2)) }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-semibold text-gray-900">{{ $kurir['nama'] }}</div>
                      <div class="text-xs text-gray-500">ID: #{{ $kurir['id'] }}</div>
                      {{-- Performance Grade Badge --}}
                      @if(isset($kurir['performance']['performance_grade']))
                        <div class="mt-1">
                          <span class="px-2 py-1 text-xs font-bold rounded-full
                            {{ $kurir['performance']['performance_grade']['color'] === 'green' ? 'bg-green-100 text-green-800' :
                               ($kurir['performance']['performance_grade']['color'] === 'blue' ? 'bg-blue-100 text-blue-800' :
                               ($kurir['performance']['performance_grade']['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' :
                               ($kurir['performance']['performance_grade']['color'] === 'orange' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                            Grade {{ $kurir['performance']['performance_grade']['grade'] }}
                          </span>
                        </div>
                      @endif
                    </div>
                  </div>
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col space-y-2">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                    {{ $kurir['status_kurir'] === 'tersedia' ? 'bg-green-100 text-green-800 ring-1 ring-green-200'
                       : ($kurir['status_kurir'] === 'sedang_mengantar' ? 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200'
                       : 'bg-red-100 text-red-800 ring-1 ring-red-200') }}">
                      {{ ucfirst(str_replace('_', ' ', $kurir['status_kurir'])) }}
                    </span>
                    
                    {{-- Online Status --}}
                    <div class="flex items-center">
                      <div class="w-2 h-2 rounded-full mr-2 
                        {{ $kurir['status_online'] === 'online' ? 'bg-green-400' : 'bg-gray-400' }}">
                      </div>
                      <span class="text-xs text-gray-600">
                        {{ ucfirst($kurir['status_online']) }}
                      </span>
                    </div>
                  </div>
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-gray-600">Order Hari Ini:</span>
                      <span class="font-semibold text-gray-900">{{ $kurir['total_order'] }}
                      </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-gray-600">Order Selesai:</span>
                      <span class="font-semibold text-green-600">{{ $kurir['order_selesai'] }}</span>
                    </div>
                    {{-- Success Rate untuk hari ini --}}
                    @if($kurir['total_order'] > 0)
                      <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Success Rate:</span>
                        <span class="font-semibold text-blue-600">
                          {{ number_format(($kurir['order_selesai'] / $kurir['total_order']) * 100, 1) }}%
                        </span>
                      </div>
                    @endif
                  </div>
                </td>

                {{-- Performance 30 Hari (Detail) --}}
                <td class="px-6 py-4 whitespace-nowrap" x-show="showPerformanceDetails">
                  <div class="space-y-3">
                    {{-- Performance Score dengan Progress Bar --}}
                    <div class="flex items-center justify-between">
                      <span class="text-xs text-gray-600">Performance Score:</span>
                      <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                          <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" 
                               style="width: {{ min($kurir['performance']['performance_score'] ?? 0, 100) }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-900">
                          {{ $kurir['performance']['performance_score'] ?? 0 }}/100
                        </span>
                      </div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                      <span class="text-gray-600">Avg Delivery:</span>
                      <span class="font-medium {{ ($kurir['performance']['avg_delivery_time_minutes'] ?? 0) <= 30 ? 'text-green-600' : (($kurir['performance']['avg_delivery_time_minutes'] ?? 0) <= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                          {{ ($kurir['performance']['avg_delivery_time_minutes'] ?? 0) > 0 ? $kurir['performance']['avg_delivery_time_formatted'] : 'Belum ada data' }}
                      </span>
                  </div>

                    {{-- Active Hours --}}
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-gray-600">Daily Active:</span>
                      <span class="font-medium text-blue-600">
                        {{ $kurir['performance']['daily_active_hours'] ?? 0 }}h/hari
                      </span>
                    </div>

                    {{-- Success Rate 30 Days --}}
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-gray-600">30-Day Success:</span>
                      <span class="font-medium text-green-600">
                        {{ $kurir['performance']['success_rate'] ?? 0 }}%
                      </span>
                    </div>

                    {{-- Orders per Hour --}}
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-gray-600">Orders/Hour:</span>
                      <span class="font-medium text-purple-600">
                        {{ $kurir['performance']['orders_per_hour'] ?? 0 }}/jam
                      </span>
                    </div>

                    {{-- Total Orders 30 Days --}}
                    <div class="flex items-center justify-between text-xs border-t pt-2">
                      <span class="text-gray-600">Total Orders:</span>
                      <span class="font-medium text-gray-900">
                        {{ $kurir['performance']['total_orders_30days'] ?? 0 }} (30 hari)
                      </span>
                    </div>
                  </div>
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    @if($kurir['login_terakhir'])
                      <div class="flex flex-col">
                        <span class="font-medium">{{ $kurir['login_terakhir']->format('d/m/Y') }}</span>
                        <span class="text-xs text-gray-500">{{ $kurir['login_terakhir']->format('H:i') }}</span>
                        <span class="text-xs text-gray-400 mt-1">
                          {{ $kurir['login_terakhir']->diffForHumans() }}
                        </span>
                      </div>
                    @else
                      <span class="text-gray-400 italic">Belum pernah login</span>
                    @endif
                  </div>
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <a href="{{ route('admin.detailKurir', $kurir['id']) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-xs font-medium transition-colors duration-200 flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      Detail
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kurir</h3>
                    <p class="text-gray-500 mb-4">Tambahkan kurir pertama Anda untuk mulai mengelola pengiriman</p>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                      Tambah Kurir
                    </button>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Performance Analytics Section --}}
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8" x-show="showPerformanceDetails">
      
      {{-- Performance Distribution Chart --}}
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Distribusi Performance Grade
          </h3>
        </div>
        
        <div class="space-y-4">
          @php
            $gradeStats = collect($kurirs)->groupBy(function($kurir) {
              return $kurir['performance']['performance_grade']['grade'] ?? 'N/A';
            })->map(function($group) {
              return $group->count();
            });
          @endphp
          
          @foreach(['A+', 'A', 'B+', 'B', 'C+', 'C', 'D'] as $grade)
            @php
              $count = $gradeStats->get($grade, 0);
              $percentage = count($kurirs) > 0 ? ($count / count($kurirs)) * 100 : 0;
              $color = match($grade) {
                'A+', 'A' => 'green',
                'B+', 'B' => 'blue', 
                'C+' => 'yellow',
                'C' => 'orange',
                'D' => 'red'
              };
            @endphp
            
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <span class="px-2 py-1 text-xs font-bold rounded-full mr-3
                  {{ $color === 'green' ? 'bg-green-100 text-green-800' :
                     ($color === 'blue' ? 'bg-blue-100 text-blue-800' :
                     ($color === 'yellow' ? 'bg-yellow-100 text-yellow-800' :
                     ($color === 'orange' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                  Grade {{ $grade }}
                </span>
                <span class="text-sm text-gray-600">{{ $count }} kurir</span>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-24 bg-gray-200 rounded-full h-2">
                  <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all duration-300" 
                       style="width: {{ $percentage }}%"></div>
                </div>
                <span class="text-xs text-gray-500 min-w-[3rem]">{{ number_format($percentage, 1) }}%</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Top Performers --}}
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            Top Performers
          </h3>
        </div>
        
        <div class="space-y-4">
          @foreach(collect($kurirs)->sortByDesc('performance.performance_score')->take(5) as $index => $topKurir)
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8">
                  @if($index === 0)
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center">
                      <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                      </svg>
                    </div>
                  @else
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                      <span class="text-xs font-bold text-white">{{ $index + 1 }}</span>
                    </div>
                  @endif
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">{{ $topKurir['nama'] }}</div>
                  <div class="text-xs text-gray-500">
                    {{ $topKurir['performance']['avg_delivery_time_formatted'] ?? 'N/A' }} • 
                    {{ $topKurir['performance']['orders_per_hour'] ?? 0 }}/jam
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <span class="px-2 py-1 text-xs font-bold rounded-full
                  {{ $topKurir['performance']['performance_grade']['color'] === 'green' ? 'bg-green-100 text-green-800' :
                     ($topKurir['performance']['performance_grade']['color'] === 'blue' ? 'bg-blue-100 text-blue-800' :
                     ($topKurir['performance']['performance_grade']['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' :
                     ($topKurir['performance']['performance_grade']['color'] === 'orange' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                  {{ $topKurir['performance']['performance_score'] ?? 0 }}
                </span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      
    </div>

    {{-- Performance Insights --}}
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6" x-show="showPerformanceDetails">
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-semibold text-blue-900">Performance Insights</h3>
          <div class="mt-2 text-sm text-blue-700">
            <p class="mb-2">
              <strong>Rata-rata Performance Score:</strong> 
              {{ collect($kurirs)->where('performance.performance_score', '>', 0)->avg('performance.performance_score') ? number_format(collect($kurirs)->where('performance.performance_score', '>', 0)->avg('performance.performance_score'), 1) : 0 }}/100
            </p>
          <p class="mb-2">
    <strong>Rata-rata Waktu Pengantaran:</strong>
    @php
        $validKurirs = collect($kurirs)->filter(function ($kurir) {
            return isset($kurir['performance']['avg_delivery_time_minutes']) && $kurir['performance']['avg_delivery_time_minutes'] > 0;
        });
        $avgDeliveryTime = $validKurirs->count() > 0 ? $validKurirs->avg('performance.avg_delivery_time_minutes') : 0;
    @endphp
    {{ $avgDeliveryTime > 0 ? number_format($avgDeliveryTime, 1) . ' menit' : 'Belum ada data' }}
</p>
            <p>
              <strong>Total Orders (30 hari):</strong> 
              {{ collect($kurirs)->sum('performance.total_orders_30days') }} orders dengan success rate 
              {{ collect($kurirs)->where('performance.success_rate', '>', 0)->avg('performance.success_rate') ? number_format(collect($kurirs)->where('performance.success_rate', '>', 0)->avg('performance.success_rate'), 1) : 0 }}%
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layout>