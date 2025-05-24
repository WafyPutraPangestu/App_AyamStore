<x-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header with amber/yellow background for cancelled status -->
            <div class="bg-amber-400 text-amber-950 py-3 px-4 font-medium">
                Order Dibatalkan
            </div>

            <div class="p-6 text-center">
                <h4 class="text-xl font-bold text-amber-500 mb-4">Order Dibatalkan</h4>
                <p class="text-gray-700 mb-2">Order dibatalkan dikarenakan Anda tidak langsung menyelesaikan proses pembayaran.</p>
                <p class="text-gray-700 mb-6">Silakan lakukan order kembali jika Anda masih berminat dengan produk kami.</p>

                <!-- Payment ID section -->
                @if(isset($pembayaran) && $pembayaran->id)
                    <p class="mt-4 text-gray-600">ID Pembayaran Terkait: {{ $pembayaran->id }}</p>
                @endif

                <!-- Order details section -->
                @if(isset($pembayaran) && $pembayaran->order)
                    <div class="mt-6 mb-6">
                        <h5 class="font-medium text-gray-800 mb-2">Detail Order Sebelumnya (ID: {{ $pembayaran->order->id }})</h5>
                        <ul class="space-y-1 mb-3">
                            @foreach($pembayaran->order->items as $item)
                                <li class="text-gray-600">{{ $item->produk->nama_produk ?? 'Produk tidak ditemukan' }} ({{ $item->quantity }} pcs)</li>
                            @endforeach
                        </ul>
                        <p class="font-medium">Total: Rp {{ number_format($pembayaran->order->total_harga, 0, ',', '.') }}</p>
                    </div>
                @endif

                <!-- Action buttons -->
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('user.katalog') }}" class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-amber-950 rounded-lg transition-colors">
                        Kembali ke Katalog
                    </a>
                    <a href="{{ route('user.keranjang') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Lihat Keranjang Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>