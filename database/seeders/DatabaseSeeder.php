<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use App\Models\order;
use App\Models\order_detail;
use App\Models\Pembayaran;
use App\Models\produk;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(4)->create()->each(function ($user) {

            $produks = produk::factory(3)->create([
                'user_id' => $user->id,
            ]);

            $orders = order::factory(2)->create([
                'user_id' => $user->id,
            ]);

            $orders->each(function ($order) use ($user, $produks) {
                order_detail::factory(2)->create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'produk_id' => $produks->random()->id,
                ]);

                Pembayaran::factory()->create([
                    'order_id' => $order->id,
                ]);
            });

            Keranjang::factory(2)->create([
                'user_id' => $user->id,
                'produk_id' => $produks->random()->id,
            ]);
        });
    }
}
