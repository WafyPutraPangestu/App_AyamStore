<x-layout>
  <x-notifications /> {{-- Asumsikan komponen notifikasi sama dengan di katalog --}}
  <div class="min-h-screen bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <h1 class="text-3xl font-bold mb-8 text-center sm:text-left text-gray-800">Pembayaran</h1>

          <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
              @if(isset($pembayaran) && $pembayaran->snap_token)
                  <div class="text-center mb-6">
                      <svg class="w-16 h-16 text-emerald-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M10.5 3.75a6 6 0 0 0-5.98 6.496A5.25 5.25 0 0 0 6.75 20.25H18a4.5 4.5 0 0 0 2.206-8.423 3.75 3.75 0 0 0-4.133-4.303A6.001 6.001 0 0 0 10.5 3.75Z" />
                      </svg>
                      <p class="text-lg text-gray-700 mb-2">Pembayaran Anda sudah siap diproses</p>
                      <p class="text-sm text-gray-500 mb-6">Klik tombol di bawah untuk melanjutkan ke halaman pembayaran</p>
                      
                      <div class="mt-4">
                          <button id="pay-button" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1">Bayar Sekarang</button>
                      </div>
                  </div>
                  
                  <pre id="result-json" class="mt-4 bg-gray-100 p-4 rounded-lg text-sm text-gray-700 overflow-x-auto hidden"></pre>
                  
                  <div class="border-t border-gray-200 pt-6 mt-6">
                      <div class="flex justify-center">
                          <a href="{{ route('user.katalog') }}" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                              </svg>
                              Kembali ke Katalog
                          </a>
                      </div>
                  </div>
              @else
                  <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6">
                      <div class="flex">
                          <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                          </svg>
                          <p>Terjadi kesalahan. Token pembayaran tidak tersedia.</p>
                      </div>
                  </div>
                  
                  <div class="text-center">
                      <a href="{{ route('user.katalog') }}" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                          Kembali ke Katalog
                      </a>
                  </div>
              @endif
          </div>
      </div>
  </div>

  @if(isset($pembayaran) && $pembayaran->snap_token)
      <!-- Script Midtrans (tidak diubah) -->
      <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(event) {
            event.preventDefault();
    
            snap.pay("{{ $pembayaran->snap_token }}", {
                onSuccess: function(result) {
                    /* Handler ketika sukses */
                    console.log("Payment Success:", result);
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2); // Opsional
                    window.location.href = "{{ route('user.order-success', $pembayaran->id) }}"; // Arahkan ke route success
                },
                onPending: function(result) {
                    /* Handler ketika pending */
                    console.log("Payment Pending:", result);
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2); // Opsional
                    // Arahkan ke route pending baru
                    window.location.href = "{{ route('user.order-pending', $pembayaran->id) }}";
                },
                onError: function(result) {
                    /* Handler ketika error */
                    console.log("Payment Error:", result);
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2); // Opsional
                    // Arahkan ke route failed/error baru
                    window.location.href = "{{ route('user.order-failed', $pembayaran->id) }}";
                },
                onClose: function() {
                    /* Handler ketika pembeli menutup Snap popup tanpa menyelesaikan pembayaran */
                    console.log('Customer closed the popup without finishing the payment');
                    // Anda mungkin ingin mengarahkan ke halaman 'failed' atau kembali ke keranjang
                    // Di sini kita arahkan ke halaman 'failed' sebagai contoh
                    window.location.href = "{{ route('user.order-failed', $pembayaran->id) }}?reason=closed";
                }
            });
        };
    </script>
  @endif
</x-layout>