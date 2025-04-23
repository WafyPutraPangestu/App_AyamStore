<x-layout>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Keranjang Belanja Anda</h1>

    @if($keranjangs == null || $keranjangs->items->isEmpty())
      <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <p class="mt-4 text-lg font-medium text-gray-600">Keranjang Anda masih kosong.</p>
        <a href="{{ route('user.katalog') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">Mulai Belanja</a>
      </div>
    @else
      <div class="mb-4 flex items-center gap-2 justify-between">
        <div class="flex items-center gap-2">
          <input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-blue-600">
          <label for="select-all" class="text-gray-700 font-medium">Pilih Semua</label>
  
        </div>
        @if(!$keranjangs->items->isEmpty())
        <form id="bulk-delete-form" action="{{ route('keranjang.bulkDestroy') }}" method="POST" class="mt-6 text-right hidden">
          @csrf
          @method('DELETE')
          <input type="hidden" name="selected_ids" id="selected-ids">
          <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">
            Hapus Yang Dipilih
          </button>
        </form>
      @endif

      </div>

      <div class="space-y-4">
        @php $totalKeseluruhan = 0; @endphp
        @foreach ($keranjangs->items as $item)
          @php
            $subtotal = $item->produk->harga * $item->quantity;
            $totalKeseluruhan += $subtotal;
          @endphp
          <div class="bg-white shadow-lg rounded-lg p-4 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 transition duration-300 ease-in-out hover:shadow-xl">
            <div class="flex-shrink-0">
              <input type="checkbox" name="selected_items[]" value="{{ $item->produk->id }}" class="item-checkbox form-checkbox h-5 w-5 text-blue-600">
            </div>

            <div class="flex-shrink-0">
              <img src="{{ $item->produk->gambar_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $item->produk->nama_produk }}" class="w-24 h-24 object-cover rounded-md border border-gray-200">
            </div>

            <div class="flex-grow text-center md:text-left">
              <h2 class="text-lg font-semibold text-gray-800">{{ $item->produk->nama_produk }}</h2>
              <p class="text-sm text-gray-500 mt-1">{{ $item->produk->deskripsi }}</p>
              <p class="text-md font-medium text-blue-600 mt-2">Harga: Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>

              <form action="#" method="POST" class="mt-3 flex items-center justify-center md:justify-start space-x-2">
                @csrf
                @method('PUT')
                <label for="quantity-{{ $item->id }}" class="text-sm font-medium text-gray-700">Jumlah:</label>
                <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" min="1"
                  data-id="{{ $item->id }}"
                  data-harga="{{ $item->produk->harga }}"
                  class="quantity-input w-16 px-2 py-1 text-center border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                <button type="submit" class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-md shadow-sm transition duration-300 ease-in-out transform hover:scale-105">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                </button>
              </form>
            </div>

            <div class="text-center md:text-right space-y-2 md:space-y-3">
              <p class="text-lg font-semibold text-gray-900 subtotal" id="subtotal-{{ $item->id }}">
                Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}
              </p>
              <form action="{{ route('keranjang.destroy',[
                'product_id' => $item->produk->id,
              ]) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition duration-300 ease-in-out transform hover:scale-105" onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-8 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Belanja</h2>
        <div class="flex justify-between items-center border-t border-gray-200 pt-4">
          <span class="text-lg font-medium text-gray-700">Total Keseluruhan:</span>
          <span id="total-keseluruhan" class="text-2xl font-bold text-blue-700">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
        </div>
        <form  method="POST" id="checkout-form">
          <input type="hidden" name="selected_ids" id="checkout-selected-ids">
          <button type="submit" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl">
            Lanjut ke Checkout
          </button>
        </form>
      </div>
    @endif
  </div>
  @vite('resources/js/cart.js')
 
</x-layout>
