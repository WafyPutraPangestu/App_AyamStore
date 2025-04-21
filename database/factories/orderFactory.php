<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tanggal_order' => now(),
            'total' => 0, // Akan diupdate setelah dibuat
            'status' => 'menunggu',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Pastikan order details sudah dibuat
            $order->load('order_details');

            // Jika belum ada order details, buat dummy
            if ($order->order_details->isEmpty()) {
                OrderDetail::factory(rand(1, 3))->create(['order_id' => $order->id]);
            }

            $order->update([
                'total' => $order->order_details->sum('total_harga')
            ]);
        });
    }

    public function selesai()
    {
        return $this->state([
            'status' => 'selesai',
            'tanggal_selesai' => now()
        ]);
    }
}
