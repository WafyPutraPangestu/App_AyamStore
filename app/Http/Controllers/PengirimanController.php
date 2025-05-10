<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    public function pengirimanView()
    {
        $order = Order::with('kurir')
            ->where('user_id', Auth::user()->id)
            ->first();
        return view("user.pengiriman", compact('order'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        // Validasi status pengiriman yang valid
        $validated = $request->validate([
            'status_pengiriman' => 'required|in:mencari_kurir,menunggu_pickup,sedang_diantar,terkirim,gagal_kirim',
        ]);

        // Temukan pesanan berdasarkan ID
        $order = Order::findOrFail($orderId);

        // Update status pengiriman
        $order->status_pengiriman = $request->status_pengiriman;
        $order->save();

        return response()->json(['success' => true]); // Menyediakan respons sukses
    }
    public function getStatus(Order $order)
    {
        return response()->json([
            'status_pengiriman' => $order->status_pengiriman,
        ]);
    }
}
