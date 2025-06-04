<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PengirimanController extends Controller
{
    // PengirimanController.php
    public function pengirimanView()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            // Handle jika user belum login, misalnya redirect ke login
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat status pengiriman.');
        }

        $loggedInUserId = Auth::user()->id; // Dapatkan ID user yang login

        $order = Order::with('kurir.user')
            ->where('user_id', $loggedInUserId) // Filter berdasarkan user yang login
            ->orderBy('created_at', 'desc')
            ->first();

        // AWAL DEBUGGING SECTION ====================================================
        // echo "<!DOCTYPE html><html><head><title>Debug Output</title><style>body { font-family: sans-serif; padding: 20px; } pre { background-color: #f4f4f4; border: 1px solid #ddd; padding: 15px; border-radius: 5px; white-space: pre-wrap; word-wrap: break-word; }</style></head><body>";
        // echo "<h1>Debugging Detail Pengiriman</h1>";
        // echo "<p>User ID Pelanggan yang Login: " . $loggedInUserId . "</p>";

        // if (!$order) {
        //     echo "<p style='color:red; font-weight:bold;'>Tidak ada order ditemukan untuk user ID pelanggan: " . $loggedInUserId . "</p>";
        // } else {
        //     echo "<h2>Detail Order yang Ditemukan:</h2>";
        //     echo "<pre>";
        //     echo "Order ID: " . $order->id . "\n";
        //     echo "Order user_id (ID Pelanggan): " . $order->user_id . "\n";
        //     echo "Order kurir_id (Foreign Key ke tabel kurirs): " . ($order->kurir_id ?? 'NULL') . "\n\n";

        //     if ($order->kurir) {
        //         echo "Data Kurir (dari relasi \$order->kurir):\n";
        //         print_r($order->kurir->toArray());
        //         echo "\n";
        //         echo "Kurir's user_id (Foreign Key ke tabel users dari tabel kurirs): " . ($order->kurir->user_id ?? 'NULL') . "\n\n";

        //         if ($order->kurir->user) {
        //             echo "Data User Kurir (dari relasi \$order->kurir->user):\n";
        //             print_r($order->kurir->user->toArray());
        //             echo "\n";
        //             echo "Nama User Kurir: '" . ($order->kurir->user->name ?? 'NAMA KOSONG/NULL') . "'\n\n";
        //             if (empty($order->kurir->user->name)) {
        //                 echo "<p style='color:orange; font-weight:bold;'>PERINGATAN: Nama kurir KOSONG atau NULL di database.</p>";
        //             }
        //         } else {
        //             echo "<p style='color:red; font-weight:bold;'>ERROR: Data User Kurir TIDAK DITEMUKAN (relasi \$order->kurir->user menghasilkan null).</p>";
        //             echo "<p>Cek apakah kurirs.user_id (nilai: " . ($order->kurir->user_id ?? 'TIDAK ADA') . ") ada sebagai ID di tabel users.</p>\n\n";
        //         }
        //     } else {
        //         echo "<p style='color:red; font-weight:bold;'>ERROR: Data Kurir TIDAK DITEMUKAN (relasi \$order->kurir menghasilkan null).</p>";
        //         echo "<p>Cek apakah orders.kurir_id (nilai: " . ($order->kurir_id ?? 'NULL') . ") ada sebagai ID di tabel kurirs.</p>\n\n";
        //         if (is_null($order->kurir_id)) {
        //             echo "<p style='color:orange; font-weight:bold;'>INFO: Kolom 'kurir_id' pada tabel 'orders' untuk order ini adalah NULL. Kurir belum ditetapkan.</p>";
        //         }
        //     }
        //     echo "</pre>";
        // }
        // echo "</body></html>";
        // die(); // Hentikan eksekusi di sini setelah menampilkan debug
        // AKHIR DEBUGGING SECTION ===================================================

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
