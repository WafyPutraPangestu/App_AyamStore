<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\produk>
 */
class produkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama' => fake()->word(),
            'ayam' => fake()->randomElement(['hidup', 'potong']),
            'satuan' => fake()->randomElement(['kg', 'ekor']),
            'harga' => fake()->numberBetween(10000, 100000),
            'stok' => fake()->numberBetween(1, 100),
            'deskripsi' => fake()->sentence(),
            'gambar' => fake()->imageUrl(640, 480, 'animals', true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
