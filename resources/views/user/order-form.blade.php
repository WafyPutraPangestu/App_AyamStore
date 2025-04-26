<x-layout>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Form Pemesanan</h2>

    @foreach ($order->items as $item )
        <div class="bg-white shadow-md hover:shadow-xl transition duration-300 ease-in-out rounded-2xl p-4 flex flex-col md:flex-row items-center gap-4 mb-4">
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/images/'. $item->produk->gambar) }}" alt="{{ $item->produk->nama_produk }}" class="w-24 h-24 object-cover rounded-xl border border-gray-300">
            </div>

            <div class="flex-grow text-center md:text-left">
                <h3 class="text-lg font-semibold text-gray-800">{{ $item->produk->nama_produk }}</h3>
                <p class="text-sm text-gray-500 mt-1">Jumlah: {{ $item->quantity }}</p>
                <p class="text-base font-medium text-blue-600 mt-2">Harga: Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
            </div>
        </div>
    @endforeach

    <h2 class="text-xl font-semibold text-gray-900 mt-6 mb-4">Total: Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h2>

    <x-form method="POST" action="{{ route('user.order-store') }}">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="total_harga" value="{{ $order->total_harga }}">

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-xl transition duration-200">
            Bayar
        </button>
    </x-form>
</x-layout>
