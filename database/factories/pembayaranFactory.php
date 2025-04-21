<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class PembayaranFactory extends Factory
{
    public function definition(): array
    {
        Storage::makeDirectory('public/bukti_pembayaran');

        return [
            'order_id' => Order::factory(),
            'atas_nama' => fake()->name(),
            'no_rek' => fake()->numerify('#############'),
            'metode_pembayaran' => fake()->randomElement(['Dana', 'Gopay', 'Bank', 'Cash']),
            'bukti_pembayaran' => $this->generateBukti(),
            'total_pembayaran' => fn($attrs) => Order::find($attrs['order_id'])->total,
            'keterangan' => 'menunggu konfirmasi',
            'tanggal_pembayaran' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }

    private function generateBukti(): string
    {
        return fake()->boolean(70)
            ? 'bukti_pembayaran/' . fake()->image(
                dir: storage_path('app/public/bukti_pembayaran'),
                width: 640,
                height: 480,
                category: 'business',
                fullPath: false
            )
            : fake()->imageUrl(640, 480, 'business');
    }
}
