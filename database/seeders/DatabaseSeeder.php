<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pembayaran;
use App\Models\Keranjang;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'role' => 'admin',
            'name' => 'Admin Ayam',
            'email' => 'admin@ayam.com',
            'password' => Hash::make('password')
        ]);

        $user = User::create([
            'role' => 'user',
            'name' => 'User Biasa',
            'email' => 'user@ayam.com',
            'password' => Hash::make('password')
        ]);

        $produk1 = Produk::create([
            'user_id' => $admin->id,
            'nama' => 'Ayam Kampung Hidup',
            'ayam' => 'hidup',
            'satuan' => 'ekor',
            'harga' => 45000,
            'stok' => 20,
            'deskripsi' => 'Ayam kampung sehat langsung dari peternakan.',
            'gambar' => null
        ]);

        $produk2 = Produk::create([
            'user_id' => $admin->id,
            'nama' => 'Ayam Potong Segar',
            'ayam' => 'potong',
            'satuan' => 'kg',
            'harga' => 35000,
            'stok' => 50,
            'deskripsi' => 'Ayam potong segar kualitas terbaik.',
            'gambar' => null
        ]);

        Keranjang::create([
            'user_id' => $user->id,
            'produk_id' => $produk1->id,
            'jumlah_produk' => 2,
            'total_harga' => 2 * $produk1->harga,
            'status' => 'active'
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'tanggal_order' => now(),
            'total' => 0,
            'status' => 'menunggu'
        ]);

        $grandTotal = 0;

        $items = [
            ['produk' => $produk1, 'jumlah' => 2],
            ['produk' => $produk2, 'jumlah' => 3]
        ];

        foreach ($items as $item) {
            $subtotal = $item['produk']->harga * $item['jumlah'];
            OrderDetail::create([
                'order_id' => $order->id,
                'produk_id' => $item['produk']->id,
                'jumlah_produk' => $item['jumlah'],
                'harga' => $item['produk']->harga,
                'total_harga' => $subtotal
            ]);
            $grandTotal += $subtotal;
        }

        $order->update(['total' => $grandTotal]);

        Pembayaran::create([
            'order_id' => $order->id,
            'atas_nama' => 'User Biasa',
            'no_rek' => '1234567890',
            'metode_pembayaran' => 'Bank',
            'bukti_pembayaran' => 'bukti.jpg',
            'total_pembayaran' => $grandTotal,
            'keterangan' => 'menunggu konfirmasi',
            'tanggal_pembayaran' => now()
        ]);
    }
}
