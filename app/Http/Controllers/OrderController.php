<?php

namespace App\Http\Controllers;

use App\Models\Keranjang; // Pastikan model ini di-import jika digunakan di method lain
use App\Models\KeranjangsItem; // Pastikan model ini di-import jika digunakan di method lain
use App\Models\Order;
use App\Models\OrderItem; // Pastikan ini merujuk ke model yang benar (OrdersItem?)
use App\Models\Pembayaran; // Pastikan model ini di-import jika digunakan di method lain
use App\Models\Produk; // Pastikan model ini di-import jika digunakan di method lain
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Pastikan DB facade di-import
use Illuminate\Support\Facades\Schema; // Pastikan Schema facade di-import jika digunakan

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Admin Order Management)
     */
    public function index()
    {
        // Method ini untuk menampilkan daftar order di halaman admin.
        // Anda perlu mengambil data order di sini.
        // Contoh: $orders = Order::with(['user', 'items.produk', 'pembayaran', 'kurir'])->latest()->paginate(10);
        // return view("admin.manajemen", compact('orders'));

        // Saat ini hanya menampilkan view kosong untuk admin manajemen order.
        return view("admin.manajemen");
    }

    /**
     * Store a newly created resource in storage.
     * (Handles checkout from cart and checks for pending orders)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // --- START: Implementasi Cek Pesanan Pending (Hanya Cek Status Order) ---
        Log::debug("Checking for existing pending order (order status only) for user ID: " . Auth::id()); // <-- Log Awal Cek

        // Cari order user yang sedang login yang statusnya 'pending' di tabel orders.
        // KONDISI whereHas('pembayaran', ...) DIHAPUS agar hanya cek status order.
        $existingPendingOrder = Order::where('user_id', Auth::user()->id)
            ->where('status', 'pending') // Hanya cek status order 'pending'
            ->first(); // Ambil order pending pertama jika ada

        // Jika ditemukan pesanan pending (berdasarkan status order saja)
        if ($existingPendingOrder) {
            Log::info("User ID " . Auth::user()->id . " memiliki pesanan pending ID " . $existingPendingOrder->id . ". Mencegah checkout baru dan mengarahkan ke halaman order form pending."); // <-- Log Ditemukan Pending

            // Cari pembayaran terkait order ini jika ada, untuk mendapatkan ID pembayaran
            // Menggunakan relasi pembayaran() yang mungkin ada di model Order
            $pendingPayment = null;
            if (method_exists($existingPendingOrder, 'pembayaran')) {
                $pendingPayment = $existingPendingOrder->pembayaran()->first(); // Ambil pembayaran pertama terkait order ini
            }


            // Mengembalikan respons JSON yang memberitahu frontend (JS di keranjang) untuk redirect.
            // Status HTTP 409 Conflict digunakan untuk menandakan bahwa resource (order baru)
            // tidak dapat dibuat karena ada resource lain (order pending) yang berkonflik.
            return response()->json([
                'success' => false, // Tandai sebagai tidak berhasil membuat order baru
                'message' => 'Anda memiliki transaksi yang belum selesai. Mohon selesaikan pembayaran sebelumnya.',
                'redirect_to_payment' => true, // Flag untuk JS frontend agar tahu harus redirect
                'order_id' => $existingPendingOrder->id, // Kirim ID order pending untuk redirect
                // Kirim ID pembayaran jika ditemukan, jika tidak kirim null
                'pembayaran_id' => $pendingPayment ? $pendingPayment->id : null,
            ], 409); // 409 Conflict

        } else {
            Log::debug("No existing pending order found (order status only) for user ID: " . Auth::id()); // <-- Log Tidak Ditemukan Pending
        }
        // --- END: Implementasi Cek Pesanan Pending ---


        // --- START: Logika Pembuatan Order Baru (jika tidak ada pending) ---
        // Jika tidak ada pesanan pending (berdasarkan status order), lanjutkan proses pembuatan order baru.
        DB::beginTransaction(); // Mulai transaksi database untuk memastikan atomisitas operasi.

        try {
            // Cari keranjang belanja user yang sedang login.
            // Asumsikan keranjang selalu ada untuk user yang login.
            $keranjang = Keranjang::where('user_id', Auth::user()->id)->first();
            if (!$keranjang) {
                DB::rollBack(); // Rollback jika keranjang tidak ditemukan (kasus seharusnya jarang)
                return response()->json(['error' => 'Keranjang belanja Anda tidak ditemukan.'], 404); // Not Found
            }

            // Validasi format request (harus JSON).
            if (!$request->isJson()) {
                DB::rollBack(); // Rollback jika format request salah
                return response()->json(['error' => 'Invalid request. Expected JSON.'], 400); // Bad Request
            }

            // Ambil item yang dipilih dari request body (dikirim oleh JS frontend).
            $selectedItems = $request->input('selected_items');

            // Validasi apakah ada item yang dipilih.
            if (empty($selectedItems)) {
                DB::rollBack(); // Rollback jika tidak ada item dipilih
                return response()->json(['error' => 'Pilih minimal satu produk untuk checkout.'], 422); // Unprocessable Entity
            }

            // Hitung ulang total harga produk yang dipilih dari database untuk keamanan.
            $calculatedProductTotal = 0;
            $itemDetailsForOrder = []; // Array untuk menampung data item yang akan disimpan di tabel order_items.
            $itemIdsInCart = []; // Array untuk menampung ID item keranjang yang akan dihapus SETELAH order berhasil dibuat.

            // Loop melalui item yang dipilih dari frontend.
            foreach ($selectedItems as $itemData) {
                // Cari produk di database berdasarkan produk_id yang dikirim frontend.
                $produk = Produk::find($itemData['produk_id']);
                if (!$produk) {
                    DB::rollBack(); // Rollback jika produk tidak ditemukan di database (mungkin sudah dihapus).
                    return response()->json(['error' => 'Produk dengan ID ' . $itemData['produk_id'] . ' tidak ditemukan saat checkout.'], 404); // Not Found
                }

                // Ambil kuantitas dari request, pastikan > 0.
                $quantity = (int) $itemData['quantity'];
                if ($quantity <= 0) {
                    DB::rollBack(); // Rollback jika kuantitas tidak valid.
                    return response()->json(['error' => 'Kuantitas untuk produk ' . $produk->nama_produk . ' harus lebih dari 0.'], 422); // Unprocessable Entity
                }
                // Opsional: Cek stok produk di database jika diperlukan.
                // if ($quantity > $produk->stok) {
                //      DB::rollBack();
                //       return response()->json(['error' => 'Stok produk ' . $produk->nama_produk . ' tidak mencukupi.'], 422);
                // }

                // Ambil harga produk saat ini dari database (harga live).
                $harga = (float) preg_replace('/[^\d]/', '', $produk->harga); // Bersihkan format harga jika masih string.
                $subtotal = $harga * $quantity; // Hitung subtotal untuk item ini.
                $calculatedProductTotal += $subtotal; // Tambahkan ke total harga produk keseluruhan.

                // Siapkan data item untuk disimpan di tabel order_items.
                $itemDetailsForOrder[] = [
                    'produk_id' => $produk->id,
                    'quantity' => $quantity,
                    'price_per_item' => $harga, // Simpan harga produk saat order dibuat (harga snapshot).
                    'subtotal' => $subtotal, // Simpan subtotal item.
                    // 'orders_id' akan diisi otomatis oleh relasi hasMany saat createMany dipanggil.
                ];

                // Kumpulkan ID item keranjang yang sesuai untuk dihapus nanti.
                // Cari item di keranjang user berdasarkan produk_id.
                // Mengambil item pertama yang cocok.
                $cartItem = $keranjang->items()->where('produk_id', $produk->id)->first();

                if ($cartItem) {
                    $itemIdsInCart[] = $cartItem->id; // Kumpulkan KeranjangsItem ID.
                } else {
                    // Ini kasus aneh jika item tidak ditemukan di keranjang padahal dipilih di frontend.
                    Log::warning("Item keranjang tidak ditemukan untuk produk ID " . $produk->id . " saat checkout oleh user " . Auth::id() . ". Mungkin ada isu sinkronisasi frontend/backend.");
                    // Anda bisa memilih untuk mengabaikan item ini, error, atau log saja.
                    // Untuk saat ini, kita lanjutkan tapi log warning.
                }
            }

            // *** LOGIKA PENENTUAN BIAYA PENGIRIMAN ***
            // Anda perlu menambahkan logika untuk menghitung dan menambahkan biaya pengiriman di sini.
            // Biaya pengiriman ini akan dimasukkan ke total_harga order.
            $deliveryCost = 0; // Default biaya pengiriman adalah 0.
            // Contoh: biaya pengiriman flat rate
            // $deliveryCost = 15000;
            // Contoh: biaya berdasarkan alamat user, berat total order, pilihan kurir jika ada di checkout.
            // Anda perlu menerima data alamat dan/atau pilihan kurir dari frontend saat checkout.
            // $shippingAddressData = $request->input('shipping_address_data'); // Contoh data alamat dari frontend.
            // $chosenKurirId = $request->input('kurir_id'); // Contoh ID kurir jika dipilih di frontend.

            // Panggil method helper untuk menghitung biaya pengiriman jika logikanya kompleks.
            // $deliveryCost = $this->calculateDeliveryCost($shippingAddressData, $calculatedProductTotal, $itemDetailsForOrder, $chosenKurirId);


            // Hitung total akhir (total harga produk + biaya pengiriman).
            $finalTotal = $calculatedProductTotal + $deliveryCost;

            // Validasi total akhir.
            if ($finalTotal <= 0) {
                DB::rollBack(); // Rollback jika total tidak valid.
                return response()->json(['error' => 'Total harga pesanan tidak valid.'], 422); // Unprocessable Entity
            }

            // Buat record Order baru di tabel 'orders'.
            $order = Order::create([
                'user_id' => Auth::user()->id, // ID user yang sedang login.
                'total_harga' => $finalTotal, // Total harga termasuk ongkir.
                'status' => 'pending', // Status awal pesanan: pending pembayaran.
                // *** KOLOM PENGIRIMAN ***
                // Simpan data alamat pengiriman di sini jika belum ada di migrasi atau jika disimpan langsung.
                // Pastikan kolom-kolom ini ada di migrasi tabel 'orders'.
                // 'shipping_address' => $request->input('shipping_address'), // Perlu terima dari frontend.
                // 'shipping_city' => $request->input('shipping_city'), // Perlu terima dari frontend.
                // 'shipping_postal_code' => $request->input('shipping_postal_code'), // Perlu terima dari frontend.
                'delivery_cost' => $deliveryCost, // Simpan biaya pengiriman.
                // Simpan ID kurir jika dipilih di frontend saat checkout (jika pakai tabel kurirs).
                // 'kurir_id' => $chosenKurirId ?? null,
                // Status pengiriman awal (jika kolom delivery_status ada di migrasi orders).
                // if (Schema::hasColumn('orders', 'delivery_status')) {
                //     'delivery_status' => 'awaiting_payment', // Atau 'not_shipped'. Sesuaikan dengan enum Anda.
                // }
            ]);

            // Simpan item-item ke tabel order_items menggunakan relasi hasMany.
            // Pastikan relasi `items()` sudah didefinisikan di model Order.php
            // yang merujuk ke model OrderItem dan foreign key 'orders_id'.
            $order->items()->createMany($itemDetailsForOrder);


            // Hapus item dari keranjang yang berhasil dipindahkan ke order.
            if (!empty($itemIdsInCart)) {
                // Pastikan nama model KeranjangsItem sudah benar.
                // Menghapus item keranjang berdasarkan ID yang sudah dikumpulkan.
                KeranjangsItem::whereIn('id', $itemIdsInCart)->delete();
                Log::info("Menghapus item keranjang ID: " . implode(', ', $itemIdsInCart) . " untuk user ID " . Auth::user()->id . " setelah order ID " . $order->id . " dibuat.");
            }


            DB::commit(); // Commit transaksi database jika semua operasi berhasil.

            Log::info("Order baru ID " . $order->id . " berhasil dibuat untuk user ID " . Auth::user()->id);

            // Mengembalikan respons JSON yang memberitahu frontend untuk redirect ke halaman order-form baru.
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.',
                'order_id' => $order->id, // Kirim ID order yang baru dibuat untuk redirect.
                // Buat URL redirect ke halaman order-form untuk order yang baru dibuat.
                'redirect_url' => route('user.order-form', $order->id),
            ], 200); // OK


        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi database jika terjadi error.
            Log::error('Error creating order: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'stack' => $e->getTraceAsString(),
                // Log request data jika perlu debug.
                // 'request_data' => $request->all(),
            ]);
            // Mengembalikan respons error ke frontend.
            return response()->json(['error' => 'Terjadi kesalahan saat memproses pesanan.', 'details' => $e->getMessage()], 500); // Internal Server Error
        }
        // --- END: Logika Pembuatan Order Baru ---
    }

    /**
     * Handle the cancellation of a pending order by the user.
     * (Called from the "Batalkan Bayar" button on order-form)
     *
     * @param  \App\Models\Order $order The order instance resolved by route model binding.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelOrder(Order $order)
    {
        // --- START: Implementasi Pembatalan Order ---
        // Pastikan order milik user yang sedang login (Security Check).
        if ($order->user_id !== Auth::id()) {
            Log::warning("User ID " . Auth::id() . " attempted to cancel order ID " . $order->id . " which does not belong to them.");
            return redirect()->back()->with('error', 'Anda tidak berhak membatalkan pesanan ini.');
        }

        // Pastikan status order masih 'pending' atau status lain yang diizinkan untuk dibatalkan oleh user.
        // Sesuaikan status yang diizinkan sesuai alur bisnis Anda.
        // Misalnya, hanya izinkan jika status 'pending' (menunggu pembayaran).
        if ($order->status !== 'pending') {
            Log::warning("Attempted to cancel order ID " . $order->id . " with status '{$order->status}', but only 'pending' can be cancelled by user.");
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena statusnya bukan pending.');
        }

        DB::beginTransaction(); // Mulai transaksi database untuk memastikan atomisitas operasi hapus.

        try {
            // Hapus order.
            // Karena Anda sudah mengatur cascadeOnDelete di migrasi tabel order_items dan pembayaran,
            // semua item order (orders_items) dan pembayaran (pembayaran) yang terkait dengan order ini
            // akan otomatis terhapus ketika record order dihapus.
            $orderId = $order->id; // Simpan ID order sebelum dihapus untuk logging.

            // Lakukan penghapusan order.
            $order->delete();

            DB::commit(); // Commit transaksi jika penghapusan berhasil.

            Log::info("Order ID {$orderId} dan data terkait berhasil dibatalkan dan dihapus oleh user ID " . Auth::id());

            // Redirect user kembali ke halaman keranjang atau dashboard dengan pesan sukses.
            return redirect()->route('user.keranjang')->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi error.
            Log::error('Error cancelling order ID ' . ($order->id ?? 'N/A') . ': ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
                'order_id_attempted' => $order->id ?? 'N/A',
            ]);

            // Redirect user kembali ke halaman sebelumnya (order-form) dengan pesan error.
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
        // --- END: Implementasi Pembatalan Order ---
    }
}
