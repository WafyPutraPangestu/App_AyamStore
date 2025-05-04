{{-- resources/views/admin/dashboard.blade.php --}}
<x-layout>
  {{-- Tambahkan meta viewport untuk responsif --}}
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  {{-- ApexCharts - pastikan menggunakan versi yang stabil --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js"></script>
  
  {{-- Alpine.js - pastikan menggunakan versi lengkap, bukan defer untuk debugging --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>

  {{-- Debug area - hanya untuk development --}}
  <div class="container mx-auto px-4 py-2 bg-gray-100 mb-4" style="display: none;">
    <h3 class="font-bold">Debug Info:</h3>
    <pre id="debug" class="text-xs overflow-auto max-h-40"></pre>
  </div>

  <div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Pengguna</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Produk Tersedia</p>
            <p class="text-3xl font-bold text-green-600">{{ $totalProduks }}</p>
          </div>
          <div class="bg-green-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Pesanan</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalOrders }}</p>
          </div>
          <div class="bg-purple-100 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
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
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <div class="flex flex-wrap items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Statistik Pesanan</h2>
        <div class="flex space-x-2 mt-2 lg:mt-0">
          <button id="btn7d" class="px-4 py-2 bg-blue-500 text-white rounded-md">
            7 Hari
          </button>
          <button id="btn30d" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">
            30 Hari
          </button>
          <button id="btn12m" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">
            12 Bulan
          </button>
        </div>
      </div>
      
      <div id="chart" class="h-80"></div>
    </div>

    {{-- Tabel Pesanan Terbaru --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Pesanan Terbaru</h2>
        <a href="{{ route('admin.dataProduk') ?? '#' }}" class="text-blue-500 hover:text-blue-700 text-sm">
          Lihat Semua
        </a>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($recentOrders as $order)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                    {{ $order->status === 'selesai' ? 'bg-green-100 text-green-800'
                       : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800'
                       : ($order->status === 'batal' ? 'bg-red-100 text-red-800'
                       : 'bg-blue-100 text-blue-800')) }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  Rp{{ number_format($order->total, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $order->created_at->format('d M Y H:i') }}
                </td>
               
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                  Belum ada pesanan
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
      console.log('DOM Content Loaded');
      
      // Print debug info
      try {
        const ranges = {
          '7d': @json($range7),
          '30d': @json($range30),
          '12m': @json($months)
        };
        
        const totals = {
          '7d': @json($totals7),
          '30d': @json($totals30),
          '12m': @json($totals12)
        };
        
        document.getElementById('debug').textContent = JSON.stringify({ranges, totals}, null, 2);
        console.log('Chart data:', {ranges, totals});
      } catch (e) {
        console.error('Debug info error:', e);
      }
      
      let activeRange = '7d';
      const ranges = {
        '7d': @json($range7),
        '30d': @json($range30),
        '12m': @json($months)
      };
      
      const totals = {
        '7d': @json($totals7),
        '30d': @json($totals30),
        '12m': @json($totals12)
      };
      
      // Format tanggal
      function formatDates(dates, rangeType) {
        return dates.map(date => {
          if (rangeType === '12m') {
            const [year, month] = date.split('-');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            return monthNames[parseInt(month) - 1] + ' ' + year;
          }
          
          const dateObj = new Date(date);
          return dateObj.getDate() + ' ' + dateObj.toLocaleString('id-ID', { month: 'short' });
        });
      }
      
      // Opsi chart
      const options = {
        chart: {
          type: 'area',
          height: 320,
          fontFamily: 'Inter, sans-serif',
          toolbar: {
            show: false
          },
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          }
        },
        series: [{
          name: 'Pesanan',
          data: totals['7d']
        }],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
            stops: [0, 90, 100]
          }
        },
        colors: ['#3B82F6'],
        markers: {
          size: 4,
          colors: ['#FFFFFF'],
          strokeColors: '#3B82F6',
          strokeWidth: 2,
          hover: {
            size: 7
          }
        },
        grid: {
          borderColor: '#e2e8f0',
          strokeDashArray: 4,
          xaxis: {
            lines: {
              show: true
            }
          },
          padding: {
            top: 0,
            right: 10,
            bottom: 0,
            left: 10
          }
        },
        tooltip: {
          theme: 'light',
          y: {
            formatter: function(value) {
              return value + ' pesanan';
            }
          }
        },
        xaxis: {
          categories: formatDates(ranges['7d'], '7d'),
          labels: {
            rotate: 0,
            style: {
              fontSize: '12px',
              fontFamily: 'Inter, sans-serif',
            }
          }
        },
        yaxis: {
          labels: {
            formatter: function(value) {
              return Math.round(value);
            }
          }
        },
        responsive: [
          {
            breakpoint: 640,
            options: {
              chart: {
                height: 250
              },
              legend: {
                position: 'bottom'
              }
            }
          }
        ]
      };

      // Inisialisasi chart
      try {
        const chart = new ApexCharts(document.getElementById('chart'), options);
        chart.render();
        console.log('Chart rendered successfully!');
        
        // Button event listeners
        document.getElementById('btn7d').addEventListener('click', function() {
          updateChartRange('7d', this);
        });
        
        document.getElementById('btn30d').addEventListener('click', function() {
          updateChartRange('30d', this);
        });
        
        document.getElementById('btn12m').addEventListener('click', function() {
          updateChartRange('12m', this);
        });
        
        // Function to update chart
        function updateChartRange(key, button) {
          // Update active button
          document.getElementById('btn7d').className = 'px-4 py-2 bg-gray-200 text-gray-700 rounded-md';
          document.getElementById('btn30d').className = 'px-4 py-2 bg-gray-200 text-gray-700 rounded-md';
          document.getElementById('btn12m').className = 'px-4 py-2 bg-gray-200 text-gray-700 rounded-md';
          button.className = 'px-4 py-2 bg-blue-500 text-white rounded-md';
          
          // Update chart
          activeRange = key;
          chart.updateOptions({
            series: [{
              name: 'Pesanan',
              data: totals[key]
            }],
            xaxis: {
              categories: formatDates(ranges[key], key)
            }
          });
        }
      } catch (e) {
        console.error('Chart initialization error:', e);
        document.getElementById('chart').innerHTML = '<div class="p-4 bg-red-100 text-red-800 rounded">Error saat memuat grafik. Lihat konsol untuk detail.</div>';
      }
    });
  </script>
</x-layout>