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
            $pendingPayment = null;
            if (method_exists($existingPendingOrder, 'pembayaran')) {
                $pendingPayment = $existingPendingOrder->pembayaran()->first(); // Ambil pembayaran pertama terkait order ini
            }

            // Mengembalikan respons JSON yang memberitahu frontend (JS di keranjang) untuk redirect.
            return response()->json([
                'success' => false, // Tandai sebagai tidak berhasil membuat order baru
                'message' => 'Anda memiliki transaksi yang belum selesai. Mohon selesaikan pembayaran sebelumnya.',
                'redirect_to_payment' => true, // Flag untuk JS frontend agar tahu harus redirect
                'order_id' => $existingPendingOrder->id, // Kirim ID order pending untuk redirect
                'pembayaran_id' => $pendingPayment ? $pendingPayment->id : null, // ID pembayaran
            ], 409); // 409 Conflict
        } else {
            Log::debug("No existing pending order found (order status only) for user ID: " . Auth::id()); // <-- Log Tidak Ditemukan Pending
        }
        // --- END: Implementasi Cek Pesanan Pending ---

        // --- START: Logika Pembuatan Order Baru ---
        DB::beginTransaction(); // Mulai transaksi database untuk memastikan atomisitas operasi.

        try {
            // Cari keranjang belanja user yang sedang login.
            $keranjang = Keranjang::where('user_id', Auth::user()->id)->first();
            if (!$keranjang) {
                DB::rollBack();
                return response()->json(['error' => 'Keranjang belanja Anda tidak ditemukan.'], 404); // Not Found
            }

            // Validasi format request (harus JSON).
            if (!$request->isJson()) {
                DB::rollBack();
                return response()->json(['error' => 'Invalid request. Expected JSON.'], 400); // Bad Request
            }

            // Ambil item yang dipilih dari request body.
            $selectedItems = $request->input('selected_items');

            // Validasi apakah ada item yang dipilih.
            if (empty($selectedItems)) {
                DB::rollBack();
                return response()->json(['error' => 'Pilih minimal satu produk untuk checkout.'], 422); // Unprocessable Entity
            }

            // Hitung total harga produk yang dipilih dari database.
            $calculatedProductTotal = 0;
            $itemDetailsForOrder = [];
            $itemIdsInCart = [];

            foreach ($selectedItems as $itemData) {
                $produk = Produk::find($itemData['produk_id']);
                if (!$produk) {
                    DB::rollBack();
                    return response()->json(['error' => 'Produk dengan ID ' . $itemData['produk_id'] . ' tidak ditemukan saat checkout.'], 404); // Not Found
                }

                $quantity = (int) $itemData['quantity'];
                if ($quantity <= 0) {
                    DB::rollBack();
                    return response()->json(['error' => 'Kuantitas untuk produk ' . $produk->nama_produk . ' harus lebih dari 0.'], 422); // Unprocessable Entity
                }

                // Hitung subtotal untuk item ini.
                $harga = (float) preg_replace('/[^\d]/', '', $produk->harga);
                $subtotal = $harga * $quantity;
                $calculatedProductTotal += $subtotal;

                // Simpan data item untuk disimpan di tabel order_items.
                $itemDetailsForOrder[] = [
                    'produk_id' => $produk->id,
                    'quantity' => $quantity,
                    'price_per_item' => $harga,
                    'subtotal' => $subtotal,
                ];

                // Kumpulkan ID item keranjang yang sesuai untuk dihapus setelah order berhasil dibuat.
                $cartItem = $keranjang->items()->where('produk_id', $produk->id)->first();
                if ($cartItem) {
                    $itemIdsInCart[] = $cartItem->id;
                }
            }

            // --- HITUNG ONGKIR --- (Langsung di sini)
            // Ambil data alamat pengiriman dari request.
            $shippingAddress = $request->input('shipping_address');
            $deliveryCost = 15000; // Biaya pengiriman default.

            // Jika ada alamat pengiriman, kita hitung ongkir lebih dinamis
            if ($shippingAddress) {
                // Misalnya, kita bisa menambah logika untuk menghitung ongkir berdasarkan alamat atau kurir
                // Contoh logika: biaya pengiriman berdasarkan alamat atau metode kurir
                $baseShippingCost = 15000; // Ongkir dasar
                $weightSurcharge = 0;

                // Tambah biaya tambahan berdasarkan berat produk atau wilayah pengiriman
                if ($shippingAddress['city'] == 'Jakarta') {
                    $baseShippingCost += 5000; // Tambah biaya untuk Jakarta
                }

                // Hitung total berat produk dalam order
                $totalWeight = 0;
                foreach ($itemDetailsForOrder as $item) {
                    $totalWeight += $item['quantity'] * $produk->weight; // Misalnya ada field 'weight' pada produk
                }

                if ($totalWeight > 10) {
                    $weightSurcharge = 10000; // Biaya tambahan jika berat > 10kg
                }

                $deliveryCost = $baseShippingCost + $weightSurcharge;
            }

            // Total akhir = harga produk + ongkir.
            $finalTotal = $calculatedProductTotal + $deliveryCost;

            if ($finalTotal <= 0) {
                DB::rollBack();
                return response()->json(['error' => 'Total harga pesanan tidak valid.'], 422); // Unprocessable Entity
            }

            // Buat order baru.
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'total_harga' => $calculatedProductTotal, // Total harga produk tanpa ongkir
                'total' => $finalTotal, // Total harga produk + ongkir
                'ongkir' => $deliveryCost, // Simpan ongkir
                'status' => 'pending',
                'status_pengiriman' => 'mencari_kurir', // Status pengiriman awal
            ]);

            // Simpan item ke tabel order_items.
            $order->items()->createMany($itemDetailsForOrder);

            // Hapus item dari keranjang.
            if (!empty($itemIdsInCart)) {
                KeranjangsItem::whereIn('id', $itemIdsInCart)->delete();
            }

            DB::commit();

            // Mengembalikan respons JSON untuk redirect.
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.',
                'order_id' => $order->id,
                'redirect_url' => route('user.order-form', $order->id),
            ], 200); // OK

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage(), ['user_id' => Auth::user()->id, 'stack' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat memproses pesanan.', 'details' => $e->getMessage()], 500);
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

            $orderId = $order->id; // Simpan ID order sebelum dihapus untuk logging.


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
