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
            'telepon' => '08764566788889',
            'name' => 'Admin Ayam',
            'email' => 'admin@gmail.com',
            'password' => 'test1234'
        ]);

        $user = User::create([
            'role' => 'user',
            'telepon' => '085156411212',
            'name' => 'User Biasa',
            'email' => 'user@gmail.com',
            'password' => 'test1234'
        ]);
        $kurir = User::create([
            'role' => 'kurir',
            'telepon' => '201972170720',
            'name' => 'Kurir Ayam',
            'email' => 'kurir@gmail.com',
            'password' => 'test1234'
        ]);
        $product1 = Produk::create([
            'nama_produk' => 'Ayam Goreng',
            'harga' => 20000,
            'stok' => 100,
            'deskripsi' => 'Ayam goreng yang enak dan crispy',
        ]);
        $product1 = Produk::create([
            'nama_produk' => 'Ayam rebus',
            'harga' => 30000,
            'stok' => 200,
            'deskripsi' => 'Ayam rebus yang enak dan lembut',
        ]);
    }
}
