<x-layout>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Pembayaran Gagal</div>

                <div class="card-body text-center">
                    <h4 class="card-title text-danger">Oops! Terjadi Kesalahan</h4>
                    <p>ID Pembayaran: {{ $pembayaran->id }}</p>
                    <p>Status Saat Ini: <span class="badge bg-danger">{{ ucfirst($pembayaran->status) }}</span></p>

                    {{-- Tampilkan pesan error spesifik jika ada --}}
                    <p class="text-danger">{{ $errorMessage ?? 'Pembayaran Anda tidak berhasil diproses atau dibatalkan.' }}</p>

                    <p>Silakan coba lagi atau hubungi dukungan jika masalah berlanjut.</p>

                    {{-- Tampilkan detail order jika perlu --}}
                     @if($pembayaran->order)
                        <h5>Detail Pesanan yang Gagal (Order ID: {{ $pembayaran->order->id }})</h5>
                        <ul>
                            @foreach($pembayaran->order->items as $item)
                                <li>{{ $item->produk->nama_produk ?? 'Produk tidak ditemukan' }} ({{ $item->quantity }} pcs)</li>
                            @endforeach
                        </ul>
                        <p>Total: Rp {{ number_format($pembayaran->order->total_harga, 0, ',', '.') }}</p>
                    @endif


                    <div class="mt-4">
                         {{-- Beri Opsi untuk mencoba membayar lagi order yang sama --}}
                        {{-- PENTING: Pastikan logic mockCheckout bisa handle order yang sudah ada --}}
                        {{-- Jika tidak, arahkan kembali ke keranjang atau katalog --}}
                        @if($pembayaran->order)
                           {{-- <form action="{{ route('user.order-store') }}" method="POST" style="display:inline;">
                               @csrf
                               <input type="hidden" name="order_id" value="{{ $pembayaran->order_id }}">
                               <input type="hidden" name="total_harga" value="{{ $pembayaran->order->total_harga }}">
                               <button type="submit" class="btn btn-warning">Coba Bayar Lagi</button>
                           </form> --}}
                           <a href="{{ route('user.pembayaran', $pembayaran->id) }}" class="btn btn-warning">Coba Lagi Pembayaran</a>
                           {{-- Atau kembali ke Keranjang? Tergantung flow Anda --}}
                           <a href="{{ route('user.keranjang') }}" class="btn btn-secondary">Kembali ke Keranjang</a>
                        @else
                             <a href="{{ route('user.katalog') }}" class="btn btn-primary">Kembali ke Katalog</a>
                        @endif
                        <a href="{{ route('user.riwayat') }}" class="btn btn-secondary">Lihat Riwayat Pesanan</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>