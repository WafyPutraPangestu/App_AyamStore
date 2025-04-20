<?php

namespace Database\Factories;

use App\Models\order;
use App\Models\produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order_detail>
 */
class order_detailFactory extends Factory
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
        $harga = $produk->harga;
        return [
            'user_id' => User::factory(),
            'order_id' => order::factory(),
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama,
            'jumlah_produk' => $jumlah,
            'harga' => $harga,
            'total_harga' => $jumlah * $harga,
        ];
    }
}
