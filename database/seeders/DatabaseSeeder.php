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
        // Admin dan User
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

        // Produk
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

        // Keranjang
        Keranjang::create([
            'user_id' => $user->id,
            'produk_id' => $produk1->id,
            'jumlah_produk' => 2,
            'total_harga' => 90000,
            'status' => 'active'
        ]);

        // Order
        $order = Order::create([
            'user_id' => $user->id,
            'tanggal_order' => now(),
            'total' => 90000,
            'status' => 'menunggu'
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'produk_id' => $produk1->id,
            'jumlah_produk' => 2,
            'harga' => 45000,
            'total_harga' => 90000
        ]);

        // Pembayaran
        Pembayaran::create([
            'order_id' => $order->id,
            'atas_nama' => 'User Biasa',
            'no_rek' => '1234567890',
            'metode_pembayaran' => 'Bank',
            'bukti_pembayaran' => 'bukti.jpg',
            'total_pembayaran' => 90000,
            'keterangan' => 'menunggu konfirmasi',
            'tanggal_pembayaran' => now()
        ]);
    }
}
