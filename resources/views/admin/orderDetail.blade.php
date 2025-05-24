<x-layout>
<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Pesanan Terbaru</h2>
        <a href="{{ route('admin.dashboard')}}" class="text-blue-500 hover:text-blue-700 text-sm">
          Kembali Ke Dashboard
        </a>
      </div>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($allOrders as $order)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                {{ $order->status === 'selesai' ? 'bg-green-100 text-green-800'
                   : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800'
                   : ($order->status === 'batal' ? 'bg-red-100 text-red-800'
                   : 'bg-blue-100 text-blue-800')) }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              Rp{{ number_format($order->total, 0, ',', '.') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ $order->created_at->format('d M Y H:i') }}
            </td>
           
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
              Belum ada pesanan
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</div>
<div class="max-w-4xl mx-auto px-4">
  <x-pagination-wrapper class="bg-blue-400">
    {{ $allOrders->links() }}
  </x-pagination-wrapper>

</div>
</x-layout>