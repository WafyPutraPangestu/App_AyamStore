<x-layout>
<h1>halaman order Detail</h1>
<div class="">
  @foreach ($detail as $orderDetail )
      <h2 class="text-lg font-semibold">Order ID: {{ $orderDetail->id }}</h2>
      <p class="text-gray-600">Nama: {{ $orderDetail->order->user->name }}</p>
      <p class="text-gray-600">Nama Produk: {{ $orderDetail->produk->nama }}</p>
      <p class="text-gray-600">Total: RP {{ number_format($orderDetail->produk->harga, 0, ',', '.') }}</p>
      <p class="text-gray-600">Status: {{ $orderDetail->order->status }}</p>
      <p class="text-gray-600">Tanggal: {{ $orderDetail->created_at->format('d-m-Y H:i') }}</p>
      <a href="#" class="text-blue-500 hover:underline">Lihat Detail</a>
  @endforeach
</div>
</x-layout>