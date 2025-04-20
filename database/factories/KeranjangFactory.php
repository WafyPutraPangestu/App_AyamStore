<?php

namespace Database\Factories;

use App\Models\produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Keranjang>
 */
class KeranjangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produk = produk::inRandomOrder()->first() ?? produk::factory()->create();
        $jumlah = fake()->numberBetween(1, 5);
        return [
            'user_id' => User::factory(),
            'produk_id' => $produk->id,
            'jumlah_produk' => $jumlah,
            'total_harga' => $jumlah * $produk->harga,
        ];
    }
}
