<x-layout>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">Pembayaran Tertunda</div>

                <div class="card-body text-center">
                    <h4 class="card-title">Pesanan Anda Menunggu Pembayaran</h4>
                    <p>ID Pembayaran: {{ $pembayaran->id }}</p>
                    <p>Status Saat Ini: <span class="badge bg-warning text-dark">{{ ucfirst($pembayaran->status) }}</span></p>

                    <p>Silakan selesaikan pembayaran Anda sesuai instruksi yang diberikan (misalnya melalui Virtual Account, Gerai Ritel, dll).</p>
                    <p>Status akan diperbarui secara otomatis setelah pembayaran dikonfirmasi oleh sistem.</p>

                    {{-- Tampilkan detail order jika perlu --}}
                    @if($pembayaran->order)
                        <h5>Detail Pesanan (Order ID: {{ $pembayaran->order->id }})</h5>
                        <ul>
                            @foreach($pembayaran->order->items as $item)
                                <li>{{ $item->produk->nama_produk ?? 'Produk tidak ditemukan' }} ({{ $item->quantity }} pcs)</li>
                            @endforeach
                        </ul>
                        <p>Total: Rp {{ number_format($pembayaran->order->total_harga, 0, ',', '.') }}</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('user.riwayat') }}" class="btn btn-secondary">Lihat Riwayat Pesanan</a>
                        <a href="{{ route('user.katalog') }}" class="btn btn-primary">Kembali ke Katalog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>