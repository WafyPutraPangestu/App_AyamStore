<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangsItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class katalog extends Controller
{
    public function katalogView(request $request)
    {
        $query = Produk::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_produk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('sort')) {
            $sortOption = $request->input('sort');
            switch ($sortOption) {
                case 'harga_asc':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'harga_desc':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'nama_asc':
                    $query->orderBy('nama_produk', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('nama_produk', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $katalog = $query->paginate(12);

        $katalog->appends($request->only(['search', 'sort']));

        return view('user.katalog', compact('katalog'));
    }

    public function katalogCreate(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|string'
        ]);

        if ($request->action !== 'add_to_cart') {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        $userId = Auth::id();
        $produkId = $request->produk_id;
        $requestedQuantity = (int) $request->quantity;

        $produk = Produk::find($produkId);

        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        if ($produk->stok <= 0) {
            return redirect()->back()->with('error', 'Stok produk ini sedang habis.');
        }

        $keranjang = Keranjang::firstOrCreate(['user_id' => $userId]);

        $item = KeranjangsItem::where('keranjang_id', $keranjang->id)
            ->where('produk_id', $produkId)
            ->first();

        $currentQuantityInCart = $item ? $item->quantity : 0;
        $newTotalQuantity = $currentQuantityInCart + $requestedQuantity;

        if ($newTotalQuantity > $produk->stok) {
            $availableToAdd = $produk->stok - $currentQuantityInCart;
            if ($availableToAdd <= 0) {
                return redirect()->back()->with('error', "Stok produk tidak mencukupi. Anda sudah memiliki {$currentQuantityInCart} item di keranjang.");
            } else {
                return redirect()->back()->with('error', "Stok produk tidak mencukupi. Anda hanya dapat menambahkan {$availableToAdd} item lagi.");
            }
        }

        if ($item) {
            $item->update(['quantity' => $newTotalQuantity]);
            return redirect()->back()->with('success', 'Jumlah produk di keranjang diperbarui.');
        } else {
            KeranjangsItem::create([
                'keranjang_id' => $keranjang->id,
                'produk_id' => $produkId,
                'quantity' => $requestedQuantity
            ]);
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }
    }
}
