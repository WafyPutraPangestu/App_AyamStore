<x-layout>
  <div class="container mx-auto p-6">
    <div class="page-header">
      <h1 class="page-title">Riwayat Tugas Kurir</h1>
    </div>

    @if($orders->isEmpty())
      <div class="empty-state">
        <div class="empty-icon">📦</div>
        <p class="empty-message">Tidak ada riwayat tugas saat ini.</p>
      </div>
    @else
      <div class="card-grid">
        @foreach($orders as $index => $order)
          <div class="order-card shadow-3d" style="--animation-order: {{ $index }}">
            <div class="card-header">
              <h2 class="order-number">Pesanan #{{ $order->id }}</h2>
              <div class="package-icon">
                <div class="package">
                  <div class="package-face package-front">📦</div>
                  <div class="package-face package-back">🚚</div>
                </div>
              </div>
            </div>
            
            <div class="card-body">
              <div class="info-item">
                <span class="info-label">Tanggal</span>
                <span class="info-value">
                  <span class="date-badge">{{ $order->created_at->format('d M Y H:i') }} Sampai {{ $order->updated_at->format('d M Y H:i') }}</span>
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Alamat</span>
                <div class="address-container">
                  <span class="map-pin">📍</span>
                  <span class="address-text">{{ $order->alamat_pengiriman }}</span>
                </div>
              </div>
              
              <h4 class="section-title">Item Pesanan</h4>
              @if($order->items->isNotEmpty())
                <ul class="item-list">
                  @foreach($order->items as $item)
                    <li class="item">
                      <span class="item-name">{{ $item->produk->nama_produk ?? 'Produk #'.$item->produk_id }}</span>
                      <span class="item-quantity">× {{ $item->quantity }}</span>
                    </li>
                    <li class=" py-2 px-3  rounded-xl bg-red-600/50">
                      <span class="item-name uppercase text-white">{{ $order->status_pengiriman }}</span>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="text-gray-500">Tidak ada detail item.</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <div class="pagination-container">
        {{ $orders->links() }}
      </div>
    @endif
  </div>

  @vite(['resources/js/kurir.js', 'resources/css/riwayat.css'])

</x-layout>