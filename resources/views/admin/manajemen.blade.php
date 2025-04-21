<x-layout>
<div class="">
  <div class="flex justify-between ">
<x-page-header 
    title="Manajemen Pesanan" 
    buttonLink="{{ route('admin.dashboard') }}" 
    buttonText="Kembali ke Dashboard"
/>
<div class="">
  <x-nav-main href="{{ route('admin.manajemen') }}" :active="request()->is('admin/manajemen')">
    <span>Order</span>
  </x-nav-main>
  <x-nav-main href="{{ route('admin.dataProduk') }}" :active="request()->is('admin/dataProduk')">
    <span>Transaksi</span>
  </x-nav-main>
  <x-nav-main href="{{ route('admin.dataProduk') }}" :active="request()->is('admin/dataProduk')">
    <span>Riwayat Transaksi</span>
  </x-nav-main>
</div>
</div>
<div class="">

</div>
@foreach ($order as $orderItem)
{{-- @php
  dd($orderItem->order_details);
@endphp --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold">Order ID: {{ $orderItem->id }}</h2>
        <p class="text-gray-600">Nama: {{ $orderItem->user->name }}</p>
        <p class="text-gray-600">Total: RP {{ number_format($orderItem->total, 0, ',', '.') }}</p>
        <p class="text-gray-600">Status: {{ $orderItem->status }}</p>
        <p class="text-gray-600">Tanggal: {{ $orderItem->created_at->format('d-m-Y H:i') }}</p>
        <a href="{{ route('admin.orderDetail') }}" class="text-blue-500 hover:underline">Lihat Detail</a>
    </div>
  
@endforeach
</div>
</x-layout>