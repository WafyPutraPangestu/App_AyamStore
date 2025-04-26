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
        DB::beginTransaction(); // Mulai transaksi database

        try {
            Log::info("--> METHOD SUCCESS DIPANGGIL UNTUK PEMBAYARAN ID: " . $pembayaran->id);

            // 1. Cek apakah pembayaran sudah berhasil sebelumnya
            if ($pembayaran->status === 'berhasil') {
                Log::info("--> PEMBAYARAN SUDAH BERHASIL SEBELUMNYA.");
                // Tidak perlu rollback karena tidak ada perubahan database yang dilakukan di sini
                return view('user.success', compact('pembayaran'))->with('info', 'Pembayaran sudah dikonfirmasi sebelumnya.');
            }

            // 2. Update status Pembayaran menjadi 'berhasil'
            $pembayaran->status = 'berhasil';
            $pembayaran->save();
            Log::info("--> STATUS PEMBAYARAN ID {$pembayaran->id} DIUPDATE MENJADI 'berhasil'");

            // 3. Load relasi order beserta item dan produknya
            // Eager load untuk efisiensi
            $pembayaran->load('order.items.produk');
            $order = $pembayaran->order; // Ambil objek Order dari relasi
            Log::info("--> MENGAMBIL ORDER UNTUK PEMBAYARAN. ORDER ID: " . ($order ? $order->id : 'null'));

            // Pastikan order ditemukan
            if ($order) {
                // 4. Kurangi stok produk berdasarkan item di order
                if ($order->items && $order->items->count() > 0) {
                    Log::info("--> ORDER DITEMUKAN DAN MEMILIKI ITEM. JUMLAH ITEM: " . $order->items->count());
                    foreach ($order->items as $item) {
                        Log::info("--> MEMPROSES ORDER ITEM ID: {$item->id}, PRODUK ID: {$item->produk_id}, QTY: {$item->quantity}");
                        $produk = $item->produk; // Ambil objek Produk dari relasi item
                        Log::info("--> PRODUK DITEMUKAN? " . ($produk ? 'YA (ID: ' . ($produk->id ?? 'N/A') . ')' : 'TIDAK'));

                        // Pastikan produk ada dan memiliki kolom stok
                        // Ganti 'stok' jika nama kolom di tabel produk Anda berbeda
                        $stockColumnName = 'stok';
                        if ($produk && array_key_exists($stockColumnName, $produk->getAttributes())) {
                            $currentStock = (int) $produk->getAttribute($stockColumnName);
                            Log::info("--> STOK SEBELUM UPDATE: {$currentStock}. QTY DIBELI: {$item->quantity}");
                            $newStock = $currentStock - $item->quantity;

                            // Pencegahan stok negatif (opsional, sesuai logika bisnis)
                            if ($newStock < 0) {
                                Log::warning("--> STOK AKAN MENJADI NEGATIF UNTUK PRODUK ID {$produk->id}. DISET MENJADI 0.");
                                $newStock = 0; // Atau lemparkan error jika stok tidak boleh negatif
                                // throw new \Exception("Stok tidak mencukupi untuk produk ID: {$produk->id}");
                            }

                            $produk->setAttribute($stockColumnName, $newStock);
                            $produk->save(); // Simpan perubahan stok produk
                            Log::info("--> STOK PRODUK ID {$produk->id} SETELAH UPDATE: {$produk->{$stockColumnName}}");
                        } else {
                            // Handle jika produk tidak ditemukan atau tidak punya kolom stok
                            if (!$produk) {
                                Log::error("--> ERROR: PRODUK TIDAK DITEMUKAN UNTUK ORDER ITEM ID {$item->id}. PRODUK ID {$item->produk_id}");
                                throw new \Exception("Produk dengan ID {$item->produk_id} tidak ditemukan."); // Batalkan transaksi
                            } else {
                                Log::error("--> ERROR: ATRIBUT '{$stockColumnName}' TIDAK DITEMUKAN PADA PRODUK ID {$produk->id}");
                                throw new \Exception("Kolom stok '{$stockColumnName}' tidak ditemukan untuk produk ID: {$produk->id}"); // Batalkan transaksi
                            }
                        }
                    }
                    Log::info("--> SELESAI MEMPROSES SEMUA ITEM. STOK BERHASIL DIKURANGI UNTUK ORDER ID {$order->id}.");
                } else {
                    Log::warning("--> ORDER ID {$order->id} TIDAK MEMILIKI ITEM.");
                    // Pertimbangkan apakah ini error atau tidak. Jika order harus punya item, lemparkan exception.
                    // throw new \Exception("Order ID {$order->id} tidak memiliki item.");
                }

                // 5. <<< TAMBAHAN: Update status Order menjadi 'selesai' >>>
                // Pastikan model Order Anda memiliki kolom 'status' atau sesuaikan nama kolomnya
                Log::info("--> MENGUPDATE STATUS ORDER ID {$order->id} MENJADI 'selesai'");
                $order->status = 'selesai'; // Ganti 'selesai' jika Anda menggunakan nilai status lain
                $order->save(); // Simpan perubahan status order
                Log::info("--> STATUS ORDER ID {$order->id} BERHASIL DIUPDATE MENJADI 'selesai'");
            } else {
                // Handle jika order tidak ditemukan terkait pembayaran ini
                Log::error("--> CRITICAL ERROR: ORDER TIDAK DITEMUKAN UNTUK PEMBAYARAN ID {$pembayaran->id}.");
                // Jika Pembayaran harus selalu punya Order, ini adalah error serius.
                throw new \Exception("Order tidak ditemukan untuk Pembayaran ID: {$pembayaran->id}"); // Batalkan transaksi
            }

            // 6. Commit transaksi jika semua operasi berhasil
            DB::commit();
            Log::info("--> TRANSAKSI DATABASE BERHASIL DI-COMMIT. PEMBAYARAN ID {$pembayaran->id}");

            // 7. Tampilkan view sukses
            return view('user.success', compact('pembayaran'));
        } catch (\Exception $e) {
            // 8. Rollback transaksi jika terjadi error di manapun dalam blok try
            DB::rollBack();
            Log::error('--> FATAL ERROR DALAM PROSES SUCCESS PEMBAYARAN. TRANSAKSI DIBATALKAN: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaran->id ?? null,
                'order_id' => $pembayaran->order_id ?? ($order->id ?? null),
                'exception_class' => get_class($e),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                // 'trace' => $e->getTraceAsString(), // Aktifkan jika butuh trace lengkap
            ]);

            // Tampilkan view sukses tapi dengan pesan error
            // Pesan error sebaiknya lebih umum untuk user, detail ada di log
            return view('user.success', compact('pembayaran'))->with('error', 'Terjadi kesalahan saat memproses pesanan Anda setelah pembayaran berhasil. Silakan hubungi administrator.');
        }
    }

    public function pending(Pembayaran $pembayaran)
    {
        Log::info("--> METHOD PENDING DIPANGGIL UNTUK PEMBAYARAN ID: " . $pembayaran->id);

        // Tidak perlu mengubah status pembayaran di sini, karena sudah 'pending'
        // atau status dari Midtrans (misal 'pending' untuk VA) sudah tercatat jika pakai Notifikasi.
        // Cukup tampilkan view informasi pending.

        // Anda bisa memuat relasi order jika perlu menampilkan detailnya di view pending
        $pembayaran->load('order.items.produk');

        return view('user.pending', compact('pembayaran'));
    }

    /**
     * Menampilkan halaman ketika status pembayaran gagal atau dibatalkan.
     *
     * @param Pembayaran $pembayaran
     * @param Request $request // Tambahkan Request untuk cek parameter 'reason'
     * @return \Illuminate\View\View
     */
    public function failed(Pembayaran $pembayaran, Request $request)
    {
        Log::warning("--> METHOD FAILED DIPANGGIL UNTUK PEMBAYARAN ID: " . $pembayaran->id);

        // 1. Update status Pembayaran menjadi 'gagal' (atau 'error', 'cancelled')
        // Hanya update jika statusnya bukan 'berhasil'
        if ($pembayaran->status !== 'berhasil') {
            $pembayaran->status = 'gagal'; // Atau status lain yang sesuai
            $pembayaran->save();
            Log::info("--> STATUS PEMBAYARAN ID {$pembayaran->id} DIUPDATE MENJADI 'gagal'");
        } else {
            Log::info("--> PEMBAYARAN ID {$pembayaran->id} SUDAH 'berhasil', tidak diubah ke 'gagal'.");
        }

        // 2. Tidak perlu mengurangi stok atau mengubah status order di sini.

        // Ambil alasan dari query string (jika ada, dari onClose callback)
        $reason = $request->query('reason');
        $errorMessage = 'Pembayaran Anda gagal atau dibatalkan.';
        if ($reason === 'closed') {
            $errorMessage = 'Anda menutup jendela pembayaran sebelum transaksi selesai.';
        }

        // Anda bisa memuat relasi order jika perlu menampilkan detailnya di view failed
        $pembayaran->load('order.items.produk');

        // Tampilkan view gagal dengan pesan error
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
