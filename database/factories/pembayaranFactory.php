<?php

namespace Database\Factories;

use App\Models\order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pembayaran>
 */
class pembayaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => order::factory(),
            'atas_nama' => $this->faker->name(),
            'no_rek' => $this->faker->numerify('###-###-###'),
            'metode_pembayaran' => $this->faker->randomElement(['Dana', 'Gopay', 'bank', 'cash']),
            'bukti_pembayaran' => $this->faker->imageUrl(640, 480, 'business', true),
            'status' => $this->faker->randomElement(['pending', 'dibayar']),
            'total_pembayaran' => $this->faker->randomFloat(2, 10000, 500000),
            'keterangan' => $this->faker->randomElement(['menunggu konfirmasi', 'berhasil', 'gagal']),
            'tanggal_pembayaran' => $this->faker->dateTime(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
