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
            'email' => 'admin@gmail.com',
            'password' => 'test1234'
        ]);

        $user = User::create([
            'role' => 'user',
            'name' => 'User Biasa',
            'email' => 'user@gmail.com',
            'password' => 'test1234'
        ]);
        $product1 = Produk::create([
            'nama_produk' => 'Ayam Goreng',
            'harga' => 20000,
            'stok' => 100,
            'deskripsi' => 'Ayam goreng yang enak dan crispy',
        ]);
        $product1 = Produk::create([
            'nama_produk' => 'Ayam memek',
            'harga' => 30000,
            'stok' => 200,
            'deskripsi' => 'Ayam memek yang enak dan crispy',
        ]);
    }
}
