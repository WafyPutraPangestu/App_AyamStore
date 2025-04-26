<x-layout>
    <x-notifications /> {{-- Asumsikan komponen notifikasi sama dengan di katalog --}}
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Form Pemesanan</h1>

            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                {{-- List Item Pemesanan --}}
                <div class="space-y-4 mb-8">
                    @forelse ($order->items as $item)
                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 p-4 border border-gray-200 rounded-xl hover:border-indigo-200 transition-all duration-300">
                            {{-- Gambar Produk --}}
                            <div class="overflow-hidden relative aspect-square w-24 h-24 bg-gray-100 rounded-lg flex-shrink-0">
                                <img
                                    src="{{ $item->produk->gambar ? asset('storage/images/'. $item->produk->gambar) : asset('images/default-placeholder.jpg') }}"
                                    alt="{{ $item->produk->nama_produk }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                    loading="lazy"
                                >
                            </div>

                            {{-- Informasi Produk --}}
                            <div class="flex-grow text-center sm:text-left">
                                <h3 class="text-lg font-bold text-gray-800">{{ $item->produk->nama_produk }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Jumlah: {{ $item->quantity }}</p>
                                <p class="text-base font-semibold text-indigo-600 mt-2">
                                    Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Subtotal: Rp {{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada item dalam pesanan ini.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Informasi Total dan Summary --}}
                <div class="border-t border-gray-200 pt-6 mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    {{-- Biaya lain jika ada --}}
                    {{-- <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-medium">Rp 0</span>
                    </div> --}}
                    <div class="flex justify-between items-center text-lg font-bold mt-4 pt-4 border-t border-gray-200">
                        <span class="text-gray-800">Total</span>
                        <span class="text-indigo-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <form method="POST" action="{{ route('user.order-store') }}" class="w-full">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="total_harga" value="{{ $order->total_harga }}">

                        <button type="submit" class="w-full flex items-center justify-center bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold text-base hover:bg-emerald-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Bayar Sekarang
                        </button>
                    </form>

                    <form method="POST" action="{{ route('user.delete', $order) }}" id="cancel-form" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmCancel()" class="w-full flex items-center justify-center bg-white border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold text-base hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>

                {{-- Kembali ke Katalog --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('user.katalog') }}" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel() {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                document.getElementById('cancel-form').submit();
            }
        }
    </script>
</x-layout>