<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ProdukFactory extends Factory
{
    public function definition(): array
    {
        Storage::makeDirectory('public/products');

        return [
            'user_id' => User::factory(),
            'nama' => fake()->words(2, true),
            'ayam' => fake()->randomElement(['hidup', 'potong']),
            'satuan' => fake()->randomElement(['kg', 'ekor']),
            'harga' => fake()->randomFloat(2, 10000, 100000),
            'stok' => fake()->numberBetween(1, 100),
            'deskripsi' => fake()->sentence(),
            'gambar' => $this->generateImage(),
        ];
    }

    private function generateImage(): string
    {
        return fake()->boolean(70)
            ? 'products/' . fake()->image(
                dir: storage_path('app/public/products'),
                width: 640,
                height: 480,
                category: 'food',
                fullPath: false
            )
            : fake()->imageUrl(640, 480, 'food');
    }
}
