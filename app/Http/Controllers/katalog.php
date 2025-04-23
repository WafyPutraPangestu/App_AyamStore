<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;

use App\Models\KeranjangsItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class katalog extends Controller
{
    public function katalogView()
    {
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

        if ($request->action == 'add_to_cart') {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
            }

            $userId = Auth::id();

            // ✅ Cek apakah user sudah punya keranjang
            $keranjang = Keranjang::firstOrCreate([
                'user_id' => $userId
            ]);

            // ✅ Cek apakah produk sudah ada dalam KeranjangsItems
            $item = KeranjangsItem::where('keranjang_id', $keranjang->id)
                ->where('produk_id', $request->produk_id)
                ->first();

            if ($item) {
                // Jika ada, tambahkan quantity-nya
                $item->update([
                    'quantity' => $item->quantity + $request->quantity
                ]);

                return redirect()->back()->with('success', 'Jumlah produk di keranjang diperbarui');
            } else {
                // Jika belum ada, buat item baru
                KeranjangsItem::create([
                    'keranjang_id' => $keranjang->id,
                    'produk_id' => $request->produk_id,
                    'quantity' => $request->quantity
                ]);

                return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
            }
        }

        return redirect()->back();
    }
}
