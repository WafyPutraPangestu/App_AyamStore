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
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'total' => 'required|numeric',
            'alamat_pengiriman' => 'required|string',
        ]);

        $orderId = $request->order_id;

        $order = Order::with(['items.produk'])->find($orderId);
        if ($order) {
            $order->alamat_pengiriman = $request->alamat_pengiriman;
            $order->save();
        }

        $items = [];
        Log::info('Order items:', $order->items->toArray());

        foreach ($order->items as $item) {
            $items[] = [
                'id'       => $item->produk->id,
                'price'    => $item->produk->harga,
                'quantity' => $item->quantity,
                'name'     => $item->produk->nama_produk,
            ];
        }

        // Tambahkan ongkir sebagai item tambahan
        if ($order->ongkir > 0) {
            $items[] = [
                'id'       => 'ONGKIR',
                'price'    => $order->ongkir,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Hitung ulang total berdasarkan item_details
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $items));

        $MidtransId = 'ORDER-' . time() . '-' . rand(1000, 9999);

        $transaction = Pembayaran::create([
            'order_id' => $orderId,
            'status' => 'pending',
        ]);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $MidtransId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'shipping_address' => [
                    'address' => $request->alamat_pengiriman
                ],
            ],
            'item_details' => $items,
        ];

        Log::info("Parameter Midtrans: " . json_encode($params));

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error("Midtrans SnapToken Error: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }

        if (!$snapToken) {
            Log::error("Snap token tidak diterima dari Midtrans. Response kemungkinan kosong.");
            return redirect()->back()->with('error', 'Token pembayaran tidak tersedia.');
        }

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
            if ($pembayaran->status === 'berhasil') {
                return view('user.success', compact('pembayaran'))->with('info', 'Pembayaran sudah dikonfirmasi sebelumnya.');
            }

            $pembayaran->status = 'berhasil';
            $pembayaran->save();

            $pembayaran->load('order.items.produk');
            $order = $pembayaran->order;

            if ($order) {
                if ($order->items && $order->items->count() > 0) {
                    foreach ($order->items as $item) {
                        $produk = $item->produk;

                        $stockColumnName = 'stok';
                        if ($produk && array_key_exists($stockColumnName, $produk->getAttributes())) {
                            $currentStock = (int) $produk->getAttribute($stockColumnName);
                            $newStock = $currentStock - $item->quantity;

                            if ($newStock < 0) {
                                $newStock = 0;
                            }

                            $produk->setAttribute($stockColumnName, $newStock);
                            $produk->save();
                        } else {
                            if (!$produk) {
                                throw new \Exception("Produk dengan ID {$item->produk_id} tidak ditemukan.");
                            } else {
                                throw new \Exception("Kolom stok '{$stockColumnName}' tidak ditemukan untuk produk ID: {$produk->id}");
                            }
                        }
                    }
                }

                $order->status = 'selesai';
                $order->save();
            } else {
                throw new \Exception("Order tidak ditemukan untuk Pembayaran ID: {$pembayaran->id}");
            }

            DB::commit();

            return view('user.success', compact('pembayaran'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('--> FATAL ERROR DALAM PROSES SUCCESS PEMBAYARAN. TRANSAKSI DIBATALKAN: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id ?? null,
                'order_id' => $pembayaran->order_id ?? ($order->id ?? null),
                'exception_class' => get_class($e),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
            ]);

            return view('user.success', compact('pembayaran'))->with('error', 'Terjadi kesalahan saat memproses pesanan Anda setelah pembayaran berhasil. Silakan hubungi administrator.');
        }
    }

    public function pending(Pembayaran $pembayaran)
    {
        Log::info("--> METHOD PENDING DIPANGGIL UNTUK PEMBAYARAN ID: " . $pembayaran->id);

        $pembayaran->load('order.items.produk');

        return view('user.pending', compact('pembayaran'));
    }

    public function failed(Pembayaran $pembayaran, Request $request)
    {
        Log::warning("--> METHOD FAILED DIPANGGIL UNTUK PEMBAYARAN ID: " . $pembayaran->id);

        if ($pembayaran->status !== 'berhasil') {
            $pembayaran->status = 'gagal';
            $pembayaran->save();
        } else {
            Log::info("--> PEMBAYARAN ID {$pembayaran->id} SUDAH 'berhasil', tidak diubah ke 'gagal'.");
        }

        $reason = $request->query('reason');
        $errorMessage = 'Pembayaran Anda gagal atau dibatalkan.';
        if ($reason === 'closed') {
            $errorMessage = 'Anda menutup jendela pembayaran sebelum transaksi selesai.';
        }

        $pembayaran->load('order.items.produk');

        return view('user.failed', compact('pembayaran', 'errorMessage'));
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
