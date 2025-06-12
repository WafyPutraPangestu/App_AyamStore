{{-- resources/views/admin/dashboard.blade.php --}}
<x-layout> {{-- Pastikan komponen layout x-layout Anda sudah ada --}}
  {{-- Tambahkan meta viewport untuk responsif --}}
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  {{-- ApexCharts --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js"></script>
  
  {{-- Alpine.js --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>

  {{-- Debug area - hanya untuk development --}}
  <div class="container mx-auto px-4 py-2 bg-gray-100 mb-4" style="display: none;"> {{-- Ganti display: none menjadi display: block untuk melihat debug --}}
    <h3 class="font-bold">Debug Info (Chart Data):</h3>
    <pre id="debugChartData" class="text-xs overflow-auto max-h-40"></pre>
  </div>

  <div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Admin Dashboard</h1>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Pengguna</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Produk Tersedia</p>
            <p class="text-3xl font-bold text-green-600">{{ $totalProduks ?? 0 }}</p>
          </div>
          <div class="bg-green-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Pesanan</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalOrders ?? 0 }}</p>
          </div>
          <div class="bg-purple-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-3xl font-bold text-yellow-600">Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
          </div>
          <div class="bg-yellow-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    {{-- Grafik Area --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
      <div class="flex flex-wrap items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Statistik Pesanan</h2>
        <div class="flex space-x-2 mt-2 lg:mt-0">
          <button id="btn7d" class="px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
            7 Hari
          </button>
          <button id="btn30d" class="px-4 py-2 text-sm font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
            30 Hari
          </button>
          <button id="btn12m" class="px-4 py-2 text-sm font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
            12 Bulan
          </button>
        </div>
      </div>
      
      <div id="orderStatsChart" class="h-80"></div> {{-- ID diubah agar lebih deskriptif --}}
    </div>

    {{-- Tabel Pesanan Terbaru --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-700">Pesanan Terbaru</h2>
        <div class="flex items-center space-x-3 mt-2 sm:mt-0">
          <a href="{{ route('reports.orders.export.excel') }}" 
             class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out shadow-sm hover:shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Unduh Laporan Excel
          </a>
          @if(Route::has('admin.detailOrder')) {{-- Cek apakah route 'admin.detailOrder' ada --}}
            <a href="{{ route('admin.detailOrder')}}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
              Lihat Semua
            </a>
          @endif
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($recentOrders ?? [] as $order) {{-- Tambahkan null coalescing untuk $recentOrders --}}
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                    @if($order->status === 'selesai') bg-green-100 text-green-800
                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'gagal' || $order->status === 'batal') bg-red-100 text-red-800 {{-- Menambahkan 'gagal' --}}
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }} {{-- Mengganti underscore dengan spasi --}}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  Rp{{ number_format($order->total ?? 0, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ optional($order->created_at)->format('d M Y H:i') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                  Belum ada pesanan terbaru.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Scripts untuk ApexCharts --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const debugChartDataEl = document.getElementById('debugChartData');

      // Data untuk chart (diasumsikan di-pass dari controller)
      // Pastikan variabel ini ada dan berisi array, atau default ke array kosong
      const chartRanges = {
        '7d': @json($range7 ?? []),
        '30d': @json($range30 ?? []),
        '12m': @json($months ?? []) // 'months' adalah nama variabel dari contoh sebelumnya
      };
      
      const chartTotals = {
        '7d': @json($totals7 ?? []),
        '30d': @json($totals30 ?? []),
        '12m': @json($totals12 ?? []) // 'totals12' adalah nama variabel dari contoh sebelumnya
      };

      if (debugChartDataEl) {
        debugChartDataEl.textContent = JSON.stringify({chartRanges, chartTotals}, null, 2);
      }
      
      let activeRangeKey = '7d'; // Kunci untuk range aktif
      
      function formatChartDates(datesArray, rangeKey) {
        if (!Array.isArray(datesArray) || datesArray.length === 0) {
            return [];
        }
        return datesArray.map(dateStr => {
          if (rangeKey === '12m') { // Untuk range bulanan (YYYY-MM)
            const [year, month] = dateStr.split('-');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            return monthNames[parseInt(month) - 1] + ' ' + year.substring(2); // Misal: Jan '24
          }
          // Untuk range harian (YYYY-MM-DD)
          const dateObj = new Date(dateStr);
          return dateObj.getDate() + ' ' + dateObj.toLocaleString('id-ID', { month: 'short' }); // Misal: 5 Jun
        });
      }
      
      const chartOptions = {
        chart: {
          type: 'area',
          height: 320,
          fontFamily: 'Inter, sans-serif', // Pastikan font ini tersedia atau ganti
          toolbar: { show: false },
          animations: { enabled: true, easing: 'easeinout', speed: 800 }
        },
        series: [{
          name: 'Jumlah Pesanan',
          data: chartTotals[activeRangeKey] || []
        }],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        fill: {
          type: 'gradient',
          gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3, stops: [0, 90, 100] }
        },
        colors: ['#3B82F6'], // Biru Tailwind
        markers: {
          size: 4, colors: ['#FFFFFF'], strokeColors: '#3B82F6', strokeWidth: 2,
          hover: { size: 7 }
        },
        grid: {
          borderColor: '#e2e8f0', strokeDashArray: 4,
          xaxis: { lines: { show: true } },
          padding: { top: 5, right: 15, bottom: 0, left: 15 }
        },
        tooltip: {
          theme: 'light',
          y: { formatter: (value) => `${value} pesanan` }
        },
        xaxis: {
          categories: formatChartDates(chartRanges[activeRangeKey] || [], activeRangeKey),
          labels: {
            rotate: 0,
            style: { fontSize: '12px', fontFamily: 'Inter, sans-serif' }
          },
          tooltip: { enabled: false }
        },
        yaxis: {
          labels: {
            formatter: (value) => Math.round(value), // Menampilkan angka bulat
            style: { fontSize: '12px', fontFamily: 'Inter, sans-serif' }
          }
        },
        responsive: [{
          breakpoint: 768, // md
          options: {
            chart: { height: 280 },
            xaxis: { labels: { rotate: -45, style: { fontSize: '10px' } } }
          }
        },{
          breakpoint: 640, // sm
          options: {
            chart: { height: 250 },
            xaxis: { labels: { rotate: -45, style: { fontSize: '9px' } } }
          }
        }]
      };

      const orderStatsChartEl = document.getElementById('orderStatsChart');
      let orderStatsChartInstance = null;

      if (orderStatsChartEl) {
        try {
          orderStatsChartInstance = new ApexCharts(orderStatsChartEl, chartOptions);
          orderStatsChartInstance.render();
        } catch (e) {
          console.error('Chart initialization error:', e);
          orderStatsChartEl.innerHTML = '<div class="p-4 bg-red-100 text-red-700 rounded-md">Gagal memuat grafik. Periksa konsol untuk detail.</div>';
        }
      } else {
        console.warn('Element dengan ID "orderStatsChart" tidak ditemukan.');
      }
      
      // Event listeners untuk tombol filter chart
      const chartFilterButtons = [
        { id: 'btn7d', key: '7d' },
        { id: 'btn30d', key: '30d' },
        { id: 'btn12m', key: '12m' }
      ];

      chartFilterButtons.forEach(btnInfo => {
        const buttonEl = document.getElementById(btnInfo.id);
        if (buttonEl && orderStatsChartInstance) {
          buttonEl.addEventListener('click', function() {
            activeRangeKey = btnInfo.key;
            // Update style tombol
            chartFilterButtons.forEach(b => {
              const el = document.getElementById(b.id);
              if (el) {
                el.className = 'px-4 py-2 text-sm font-medium bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors';
              }
            });
            this.className = 'px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors';
            
            // Update data chart
            orderStatsChartInstance.updateOptions({
              series: [{ data: chartTotals[activeRangeKey] || [] }],
              xaxis: { categories: formatChartDates(chartRanges[activeRangeKey] || [], activeRangeKey) }
            });
          });
        }
      });
    });
  </script>
</x-layout>