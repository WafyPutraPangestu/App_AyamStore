<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Produk;

class OrderDetailFactory extends Factory
{
    // OrderDetailFactory.php
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'produk_id' => Produk::factory(),
            'jumlah_produk' => fake()->numberBetween(1, 5),
            'harga' => function (array $attributes) {
                return Produk::find($attributes['produk_id'])->harga;
            },
            'total_harga' => function (array $attributes) {
                return $attributes['jumlah_produk'] * $attributes['harga'];
            },
        ];
    }
}
