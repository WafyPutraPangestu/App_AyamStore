<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produk;
use App\Models\User;

class KeranjangFactory extends Factory
{
    public function definition(): array
    {
        $produk = Produk::inRandomOrder()->first() ?? Produk::factory()->create();
        $jumlah = fake()->numberBetween(1, 5);

        return [
            'user_id' => User::factory(),
            'produk_id' => $produk->id,
            'jumlah_produk' => $jumlah,
            'total_harga' => $jumlah * $produk->harga,
            'status' => 'active',
        ];
    }
}
