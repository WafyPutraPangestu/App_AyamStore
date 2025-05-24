<x-layout>
  <table>
    <thead>
        <tr>
            <th>Nama Kurir</th>
            <th>Status</th>
            <th>Jumlah Order</th>
            <th>Order Selesai</th>
            <th>Login Terakhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kurirs as $kurir)
            <tr>
                <td>{{ $kurir['nama'] }}</td>
                <td>{{ ucfirst($kurir['status_kurir']) }}</td>
                <td>{{ $kurir['total_order'] }}</td>
                <td>{{ $kurir['order_selesai'] }}</td>
                <td>{{ \Carbon\Carbon::parse($kurir['login_terakhir'])->diffForHumans() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</x-layout>