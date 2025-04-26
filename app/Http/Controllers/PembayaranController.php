<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function orderView($id)
    {
        $order = Order::findOrFail($id);

        return view('user.order-form', compact('order'));
    }

    public function mockCheckout(Request $request)
    {
        $orderId = $request->order_id;
        $MidtransId = 'ORDER-' . time() . '-' . rand(1000, 9999);

        $transaction = Pembayaran::create([
            'order_id' => $orderId,
            'status' => 'pending',
        ]);

        // KONFIGURASI MIDTRANS
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $MidtransId,
                'gross_amount' => $request->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $transaction->snap_token = $snapToken;
        $transaction->save();

        return redirect()->route('user.pembayaran', ['pembayaran' => $transaction->id]);
    }

    public function chekout(Pembayaran $pembayaran)
    {
        $produk = config('products');
        $product = collect($produk)->where('id', $pembayaran->produk_id)->first();

        return view('user.pembayaran', compact('product', 'pembayaran'));
    }

    public function success(Pembayaran $pembayaran)
    {
        DB::beginTransaction();

        try {
            Log::info("--> METHOD SUCCESS DIPANGGIL UNTUK PEMBAYARAN ID: " . $pembayaran->id);

            if ($pembayaran->status === 'berhasil') {
                Log::info("--> PEMBAYARAN SUDAH BERHASIL SEBELUMNYA.");
                DB::rollBack();
                return view('user.success', compact('pembayaran'))->with('info', 'Pembayaran sudah dikonfirmasi sebelumnya.');
            }

            $pembayaran->status = 'berhasil';
            $pembayaran->save();

            $pembayaran->load('order.items.produk');
            $order = $pembayaran->order;
            Log::info("--> MENGAMBIL ORDER UNTUK PEMBAYARAN. ORDER ID: " . ($order ? $order->id : 'null'));

            if ($order && $order->items && $order->items->count() > 0) {
                Log::info("--> ORDER DITEMUKAN DAN MEMILIKI ITEM. JUMLAH ITEM: " . $order->items->count());

                foreach ($order->items as $item) {
                    Log::info("--> MEMPROSES ORDER ITEM ID: {$item->id}, PRODUK ID: {$item->produk_id}, QTY: {$item->quantity}");

                    $produk = $item->produk;
                    Log::info("--> PRODUK DITEMUKAN? " . ($produk ? 'YA (ID: ' . ($produk->id ?? 'N/A') . ')' : 'TIDAK'));

                    $stockColumnName = 'stok';

                    if ($produk && array_key_exists($stockColumnName, $produk->getAttributes())) {
                        $produkAttributes = $produk->getAttributes();
                        Log::info("--> DEBUG ATRIBUT PRODUK ID {$produk->id}: " . print_r($produkAttributes, true));

                        $currentStock = (int) $produk->getAttribute($stockColumnName);
                        Log::info("--> STOK SEBELUM UPDATE: {$currentStock}. QTY DIBELI: {$item->quantity}");

                        $newStock = $currentStock - $item->quantity;

                        if ($newStock < 0) {
                            $newStock = 0;
                            Log::warning("--> STOK NEGATIF. PRODUK ID {$produk->id}, ORDER ID {$order->id}");
                        }

                        $produk->setAttribute($stockColumnName, $newStock);
                        $produk->save();

                        Log::info("--> STOK SETELAH UPDATE: {$produk->{$stockColumnName}}");
                    } else {
                        if (!$produk) {
                            Log::error("--> ERROR: PRODUK TIDAK DITEMUKAN. PRODUK ID {$item->produk_id}");
                        } else {
                            Log::error("--> ERROR: ATRIBUT 'stok' TIDAK DITEMUKAN PADA PRODUK ID {$produk->id}");
                        }
                    }
                }

                Log::info("--> SELESAI MEMPROSES SEMUA ITEM. STOK BERHASIL DIKURANGI.");
            } else {
                Log::warning("--> ORDER TIDAK VALID ATAU ITEM KOSONG.");
            }

            DB::commit();
            Log::info("--> TRANSAKSI DATABASE BERHASIL. PEMBAYARAN ID {$pembayaran->id}");

            return view('user.success', compact('pembayaran'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('--> FATAL ERROR. TRANSAKSI DIBATALKAN: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id ?? null,
                'order_id' => $pembayaran->order_id ?? null,
                'trace' => $e->getTraceAsString(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
            ]);

            return view('user.success', compact('pembayaran'))->with('error', 'Pembayaran berhasil, tetapi terjadi kesalahan saat memperbarui stok. Silakan hubungi administrator.');
        }
    }

    public function successView()
    {
        return view('user.success');
    }

    public function riwayat()
    {
        $riwayat = Pembayaran::with([
            'order.user',
            'order.items.produk'
        ])
            ->where('status', 'berhasil')
            ->latest()
            ->simplePaginate(3);

        return view('user.riwayat', compact('riwayat'));
    }
}
