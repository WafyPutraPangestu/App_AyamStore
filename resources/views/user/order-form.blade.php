<x-layout>
    <h2>Form Pemesanan</h2>
    s
    @foreach ($order->items as $item )
        <div class="bg-white shadow-lg rounded-lg p-4 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 transition duration-300 ease-in-out hover:shadow-xl">
            <div class="flex-shrink-0">
                <img src="{{ $item->gambar_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $item->produk->nama_produk }}" class="w-24 h-24 object-cover rounded-md border border-gray-200">
            </div>

            <div class="flex-grow text-center md:text-left">
                <h2 class="text-lg font-semibold text-gray-800">{{ $item->produk->nama_produk }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $item->quantity }}</p>
                <p class="text-md font-medium text-blue-600 mt-2">Harga: Rp {{ number_format($item->produk->harga,0, ',', '.') }}</p>
            </div>
        </div>
    @endforeach
    <h2 class="text-lg font-semibold text-gray-800">{{ $order->total_harga }}</h2>

    
    <x-form method="POST" action="{{ route('user.order-store') }}">
      @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="total_harga" value="{{ $order->total_harga }}">

      <button type="submit" class="btn btn-primary py-2 px-4 text-white cursor-pointer bg-blue-400 rounded-xl">Bayar</button>
    </x-form>
  </x-layout>