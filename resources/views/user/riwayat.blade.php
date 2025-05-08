<x-layout>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8"> 

    <div class="p-6 rounded-lg mb-6 bg-white shadow"> 
      <div class="flex justify-between items-center mb-4"> 
        <h1 class="text-2xl sm:text-3xl font-semibold text-indigo-800">Riwayat Pembelian</h1>
      </div>
    </div> 

    @if($riwayat->count() > 0)
      <div class="space-y-4" id="orderList">
        @foreach($riwayat as $pembayaran)
            <div class="order-item bg-white rounded-lg shadow-md border-l-4 overflow-hidden transition-all duration-300 hover:shadow-lg" 
                 data-order-id="{{ $pembayaran->order->id }}"
                 data-status="{{ strtolower($pembayaran->status) }}"
                 data-items="{{ implode(',', $pembayaran->order->items->pluck('produk.nama_produk')->toArray()) }}"
                 @class([
                   'border-green-500' => strtolower($pembayaran->status) == 'paid' || strtolower($pembayaran->status) == 'success' || strtolower($pembayaran->status) == 'settlement',
                   'border-yellow-500' => strtolower($pembayaran->status) == 'pending',
                   'border-red-500' => strtolower($pembayaran->status) == 'failed' || strtolower($pembayaran->status) == 'expire',
                   'border-blue-500' => strtolower($pembayaran->status) == 'shipping',
                   'border-gray-500' => !in_array(strtolower($pembayaran->status), ['paid', 'success', 'settlement', 'pending', 'failed', 'expire', 'shipping'])
                 ])>
              <button 
                class="w-full text-left p-4 sm:p-5 hover:bg-gray-50 transition-colors duration-200 focus:outline-none"
                onclick="toggleDetails('order-{{ $pembayaran->order->id }}')"
              >
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                  <div class="flex items-center space-x-3">
                    <div class="bg-indigo-100 p-2 rounded-full">
                      <svg id="icon-{{ $pembayaran->order->id }}" class="h-5 w-5 text-indigo-600 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                    
                    <div>
                      <p class="font-semibold text-gray-800">Order ID: <span class="text-indigo-700">{{ $pembayaran->order->id }}</span></p>
                      <div class="flex flex-col sm:flex-row sm:space-x-4 text-xs text-gray-500">
                        <p>Tanggal: {{ $pembayaran->order->created_at->translatedFormat('d M Y, H:i') }}</p>
                        <p>Bayar: {{ $pembayaran->created_at ? $pembayaran->created_at->translatedFormat('d M Y, H:i') : '-' }}</p>
                        <p>User: {{ $pembayaran->order->user->name }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center mt-2 sm:mt-0">
                    <span @class([
                      'inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full',
                      'bg-green-100 text-green-800' => strtolower($pembayaran->status) == 'paid' || strtolower($pembayaran->status) == 'success' || strtolower($pembayaran->status) == 'settlement',
                      'bg-yellow-100 text-yellow-800' => strtolower($pembayaran->status) == 'pending',
                      'bg-red-100 text-red-800' => strtolower($pembayaran->status) == 'failed' || strtolower($pembayaran->status) == 'expire',
                      'bg-blue-100 text-blue-800' => strtolower($pembayaran->status) == 'shipping',
                      'bg-gray-100 text-gray-800' => !in_array(strtolower($pembayaran->status), ['paid', 'success', 'settlement', 'pending', 'failed', 'expire', 'shipping'])
                    ])>
                      @if(strtolower($pembayaran->status) == 'paid' || strtolower($pembayaran->status) == 'success' || strtolower($pembayaran->status) == 'settlement')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                      @elseif(strtolower($pembayaran->status) == 'pending')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                      @elseif(strtolower($pembayaran->status) == 'failed' || strtolower($pembayaran->status) == 'expire')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                      @elseif(strtolower($pembayaran->status) == 'shipping')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H13a1 1 0 001-1v-5h2a1 1 0 00.77-.37l3-3.86A1 1 0 0019 5h-1V4a1 1 0 00-1-1H3z"></path></svg>
                      @endif
                      {{ ucfirst($pembayaran->status) }}
                    </span>
                  </div>
                </div>
              </button>

              <div id="order-{{ $pembayaran->order->id }}" class="hidden border-t border-gray-200">
                <div class="p-4 sm:p-5 bg-gradient-to-r from-gray-50 to-indigo-50">
                   <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                     <div>
                       <p class="text-gray-500">Nama User:</p>
                       <p class="font-medium">{{ $pembayaran->order->user->name }}</p>
                     </div>
                     <div>
                       <p class="text-gray-500">Tanggal Pesan:</p>
                       <p class="font-medium">{{ $pembayaran->order->created_at->translatedFormat('l, d F Y H:i') }}</p>
                     </div>
                     <div>
                       <p class="text-gray-500">Tanggal Bayar:</p>
                       <p class="font-medium">{{ $pembayaran->created_at ? $pembayaran->created_at->translatedFormat('d M Y, H:i') : '-' }}</p>
                     </div>
                   </div>
                 </div>

                <div class="p-4 sm:p-5">
                   <div class="flex items-center space-x-2 mb-4">
                     <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                     <h4 class="font-medium text-indigo-700">Daftar Item</h4>
                   </div>
                   
                   <ul class="divide-y divide-gray-200">
                     @foreach($pembayaran->order->items as $item)
                       <li class="py-3 flex items-start space-x-4">
                         <div class="flex-shrink-0">
                           @if($item->produk->gambar)
                             <img src="{{ asset('storage/images/'. $item->produk->gambar)}}" alt="{{ $item->produk->nama_produk }}" class="w-16 h-16 object-cover rounded-md border border-gray-200">
                           @else
                             <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-md border border-gray-200 flex items-center justify-center text-indigo-400">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                             </div>
                           @endif
                         </div>

                         <div class="flex-grow">
                           <p class="font-medium text-gray-900">{{ $item->produk->nama_produk }}</p>
                           <p class="text-sm text-gray-600">Jumlah: {{ $item->quantity }}</p>
                         </div>

                         <div class="text-right flex-shrink-0">
                           <p class="font-medium text-gray-800">Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}</p>
                           <p class="text-xs text-gray-500">(Rp {{ number_format($item->produk->harga, 0, ',', '.') }}/item)</p>
                         </div>
                       </li>
                     @endforeach
                   </ul>
                 </div>

                <div class="p-4 sm:p-5 bg-gradient-to-r from-indigo-50 to-blue-50 border-t border-gray-200">
                   <div class="flex justify-between items-center">
                     <div>
                       @if(strtolower($pembayaran->status) == 'paid' || strtolower($pembayaran->status) == 'success' || strtolower($pembayaran->status) == 'settlement')
                         <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                           <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                           Pembayaran Berhasil
                         </span>
                       @endif
                     </div>
                     <div class="text-right">
                       <p class="text-sm text-gray-600">Total Pesanan:</p>
                       <p class="text-lg font-bold text-indigo-700">Rp {{ number_format($pembayaran->order->total_harga, 0, ',', '.') }}</p>
                     </div>
                   </div>
                 </div>
              </div>
            </div>
        @endforeach
      </div>

      <div id="noResults" class="hidden text-center py-8 bg-white rounded-lg shadow border border-gray-200 mt-4">
         <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
         </svg>
         <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak Ada Hasil</h3>
         <p class="mt-1 text-sm text-gray-500">Coba kata kunci pencarian yang lain.</p>
       </div>

      <div class="mt-8">
        {{ $riwayat->links() }}
      </div>

    @else
      <div class="text-center py-12 px-6 bg-white rounded-lg shadow border border-gray-200">
         <svg class="mx-auto h-12 w-12 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
           <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
         </svg>
         <h3 class="mt-2 text-lg font-medium text-indigo-900">Riwayat Pesanan Kosong</h3>
         <p class="mt-1 text-sm text-gray-500">Anda belum melakukan pemesanan apapun.</p>
         <div class="mt-6">
           <a href="/" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
             <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
             Mulai Belanja
           </a>
         </div>
       </div>
    @endif

  </div> 

  <script>
    function toggleDetails(orderId) {
      const details = document.getElementById(orderId);
      const icon = document.getElementById('icon-' + orderId.replace('order-', ''));
      
      if (details.classList.contains('hidden')) {
        details.classList.remove('hidden');
        icon.classList.add('rotate-180');
      } else {
        details.classList.add('hidden');
        icon.classList.remove('rotate-180');
      }
    }
  </script>
</x-layout>
