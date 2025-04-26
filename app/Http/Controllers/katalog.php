<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangsItem; // Pastikan namespace benar
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan Log jika ingin debug

class katalog extends Controller
{
    public function katalogView()
    {
        // Ambil produk yang stoknya lebih dari 0 atau semua produk (tergantung preferensi)
        // Opsi 1: Hanya tampilkan produk yang ada stok
        // $katalog = Produk::where('stok', '>', 0)->latest()->paginate(10);

        // Opsi 2: Tampilkan semua produk (nanti di view diberi tanda habis)
        $katalog = Produk::latest()->paginate(10);

        return view('user.katalog', compact('katalog'));
    }

    public function katalogCreate(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|string'
        ]);

        // Hanya proses jika action adalah 'add_to_cart'
        if ($request->action !== 'add_to_cart') {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        $userId = Auth::id();
        $produkId = $request->produk_id;
        $requestedQuantity = (int) $request->quantity;

        // Ambil data produk untuk cek stok
        $produk = Produk::find($produkId);

        // --- PENGECEKAN STOK ---
        if (!$produk) {
            // Seharusnya tidak terjadi karena ada validasi exists:produks,id
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Cek apakah stok produk 0
        if ($produk->stok <= 0) {
            return redirect()->back()->with('error', 'Stok produk ini sedang habis.');
        }

        // Dapatkan atau buat keranjang user
        $keranjang = Keranjang::firstOrCreate(['user_id' => $userId]);

        // Cari item yang sudah ada di keranjang
        $item = KeranjangsItem::where('keranjang_id', $keranjang->id)
            ->where('produk_id', $produkId)
            ->first();

        $currentQuantityInCart = $item ? $item->quantity : 0;
        $newTotalQuantity = $currentQuantityInCart + $requestedQuantity;

        // Cek apakah total quantity yang diminta melebihi stok
        if ($newTotalQuantity > $produk->stok) {
            $availableToAdd = $produk->stok - $currentQuantityInCart;
            if ($availableToAdd <= 0) {
                return redirect()->back()->with('error', "Stok produk tidak mencukupi. Anda sudah memiliki {$currentQuantityInCart} item di keranjang.");
            } else {
                return redirect()->back()->with('error', "Stok produk tidak mencukupi. Anda hanya dapat menambahkan {$availableToAdd} item lagi.");
            }
        }
        // --- AKHIR PENGECEKAN STOK ---


        // Proses penambahan/update item
        if ($item) {
            // Jika ada, update quantity-nya
            $item->update(['quantity' => $newTotalQuantity]);
            return redirect()->back()->with('success', 'Jumlah produk di keranjang diperbarui.');
        } else {
            // Jika belum ada, buat item baru
            KeranjangsItem::create([
                'keranjang_id' => $keranjang->id,
                'produk_id' => $produkId,
                'quantity' => $requestedQuantity // Gunakan requestedQuantity karena ini item baru
            ]);
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }

        // Fallback redirect (seharusnya tidak tercapai jika logika di atas benar)
        // return redirect()->back(); Dihapus karena sudah ada redirect di dalam if/else
    }
}
