<?php

namespace Database\Factories;

use App\Models\order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class orderFactory extends Factory
{
    protected $model = order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tanggal_order' => now(),
            'total' => fake()->numberBetween(10000, 100000),
            'status' => fake()->randomElement(['keranjang', 'checkout', 'diproses', 'dikirim', 'selesai', 'dibatalkan']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
