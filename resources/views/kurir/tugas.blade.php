<x-layout>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>
  <div class="container mx-auto px-4 py-6">
    {{-- Header dengan Breadcrumb --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold mt-2">Pesanan Tersedia</h1>
      </div>
      <button onclick="history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
        ← Kembali
      </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('error'))
      <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
          </div>
        </div>
      </div>
    @endif
    
    @if(session('success'))
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
          </div>
        </div>
      </div>
    @endif

    <form id="orders-form" action="{{ route('kurir.ambil-tugas') }}" method="POST">
      @csrf
      
      {{-- Main Content --}}
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        {{-- Header dengan Counter --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <h2 class="text-xl font-bold text-white">Daftar Pesanan</h2>
            </div>
            <div class="text-white">
              <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm text-black font-semibold">
                {{ $availableOrders->count() }} Pesanan Tersedia
              </span>
            </div>
          </div>
          
          {{-- Selection Counter --}}
          <div id="selection-counter" class="mt-4 hidden">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
              <div class="flex items-center justify-between">
                <span class="text-white font-medium">
                  <span id="selected-count">0</span> pesanan dipilih
                </span>
                <button type="submit" id="submit-button" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                  <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                  </svg>
                  Ambil Pesanan
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- Content Area --}}
        <div class="p-6">
          @forelse($availableOrders as $order)
            <div class="border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-md transition-shadow duration-200 order-item">
              {{-- Header Order --}}
              <div class="flex items-center mb-4">
                <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" id="order-{{ $order->id }}" 
                       class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 order-checkbox">
                <label for="order-{{ $order->id }}" class="ml-3 flex items-center cursor-pointer">
                  <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                      <span class="text-xs font-medium text-blue-800">#{{ $order->id }}</span>
                    </div>
                  </div>
                  <div class="ml-3">
                    <div class="text-lg font-semibold text-gray-900">Pesanan #{{ $order->id }}</div>
                  </div>
                </label>
              </div>

              {{-- Order Details --}}
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="space-y-2">
                  <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Tanggal:</span>
                    <span class="ml-1">{{ $order->created_at->format('d M Y H:i') }}</span>
                  </div>
                  
                  <div class="flex items-start text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                      <span class="font-medium">Alamat:</span>
                      <p class="ml-1 mt-1">{{ $order->alamat_pengiriman }}</p>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Order Items --}}
              <div class="border-t border-gray-100 pt-4">
                <h4 class="flex items-center font-medium text-gray-800 mb-3">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                  </svg>
                  Item Pesanan:
                </h4>
                
                @if($order->items->isNotEmpty())
                  <div class="space-y-2">
                    @foreach($order->items as $item)
                      <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                        <div class="flex items-center">
                          <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                          <span class="text-sm font-medium text-gray-900">
                            {{ $item->produk->nama_produk ?? 'Produk #'.$item->produk_id }}
                          </span>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                          × {{ $item->quantity }}
                        </span>
                      </div>
                    @endforeach
                  </div>
                @else
                  <p class="text-sm text-gray-500 italic bg-gray-50 rounded-lg p-3">
                    Tidak ada detail item tersedia.
                  </p>
                @endif
              </div>
            </div>
          @empty
            {{-- Empty State --}}
            <div class="text-center py-12">
              <div class="flex flex-col items-center justify-center">
                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan tersedia</h3>
                <p class="text-sm text-gray-500">Tidak ada pesanan yang siap untuk diambil saat ini.</p>
              </div>
            </div>
          @endforelse
        </div>

        {{-- Pagination --}}
        @if($availableOrders->hasPages())
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-center">
              {{ $availableOrders->links() }}
            </div>
          </div>
        @endif
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const checkboxes = document.querySelectorAll('.order-checkbox');
      const submitButton = document.getElementById('submit-button');
      const selectionCounter = document.getElementById('selection-counter');
      const selectedCount = document.getElementById('selected-count');
      const form = document.getElementById('orders-form');
      
      function updateSelectionCounter() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
          submitButton.disabled = false;
          selectionCounter.classList.remove('hidden');
        } else {
          submitButton.disabled = true;
          selectionCounter.classList.add('hidden');
        }
      }
      
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          updateSelectionCounter();
          
          // Add visual feedback to selected orders
          const orderItem = this.closest('.order-item');
          if (this.checked) {
            orderItem.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
          } else {
            orderItem.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
          }
        });
      });
      
      // Form submission handling
      if (form) {
        form.addEventListener('submit', function(e) {
          const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
          
          if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal satu pesanan terlebih dahulu.');
            return;
          }
          
          // Show loading state
          submitButton.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
          `;
          submitButton.disabled = true;
        });
      }
    });
  </script>
</x-layout>