<x-layout>
  <h1>Halaman Order Detail</h1>
  <div class="">
      @foreach ($orderDetails as $orderId => $details)
          @php
              $order = $details->first()->order;
          @endphp
          <div class="mb-4 border p-4 rounded shadow">
              <h2 class="text-lg font-semibold">Order ID: {{ $order->id }}</h2>
              <p class="text-gray-600">Nama: {{ $order->user->name }}</p>

              <ul class="list-disc pl-5">
                  @foreach ($details as $detail)
                      <li>
                          {{ $detail->produk->nama }} {{ $detail->jumlah_produk }}
                      </li>
                  @endforeach
              </ul>

              <p class="text-gray-600 mt-2">Total: RP {{ number_format($order->total, 0, ',', '.') }}</p>
              <p class="text-gray-600">Status: {{ $order->status }}</p>
              <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d-m-Y H:i') }}</p>
          </div>
      @endforeach
  </div>
</x-layout>
