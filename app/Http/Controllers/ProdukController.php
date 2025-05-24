<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function homeView()
    {
        $produk = Produk::latest()->take(5)->get();
        return view("home", compact("produk"));
    }
    public function inputView()
    {
        return view("admin.input");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function produkView()
    {
        $produk = Produk::latest()->simplePaginate(5);
        return view("admin.dataProduk", compact("produk"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeInput(Request $request)
    {
        // Validasi input
        $input = $request->validate([
            "nama_produk" => ["required", "string"],
            "harga" => ["required", "regex:/^[0-9.]+$/"],
            "stok" => ["required", "integer"],
            "deskripsi" => ["required", "string"],
            "gambar" => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
        ], [
            "harga.required" => "harganya harus diisi",
            "harga.regex" => "Format harga harus angka",
        ]);

        // Mengambil harga dan menghapus titik
        $rawHarga = $input['harga'];
        $hargaBersih = str_replace('.', '', $rawHarga);

        // Validasi harga
        if (!is_numeric($hargaBersih)) {
            return back()->withErrors(['harga' => 'Format harga tidak valid.']);
        }

        // Konversi harga menjadi integer
        $hargaInt = (int) $hargaBersih;

        // Menyimpan gambar
        $image = $request->file("gambar");
        $imagePath = $image->store('images', 'public');

        // Menyimpan data produk ke database
        Produk::create([
            "user_id" => Auth::id(),
            "nama_produk" => $input["nama_produk"],
            "harga" => $hargaInt,  // Simpan harga sebagai integer
            "stok" => $input["stok"],
            "deskripsi" => $input["deskripsi"],
            "gambar" => basename($imagePath),
        ]);

        return redirect()->route("admin.dataProduk")->with("success", "Produk berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view("admin.edit", compact("produk"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        // Validasi input
        $validatedData = $request->validate([
            "nama_produk" => ["required", "string"],
            "harga" => ["required", "regex:/^[0-9.]+$/"],
            "stok" => ["required", "integer"],
            "deskripsi" => ["required", "string"],
            "gambar" => ["nullable", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
        ], [
            "harga.required" => "harganya harus diisi",
            "harga.regex" => "Format harga harus angka",
        ]);

        // Mengambil harga dan menghapus titik
        $rawHarga = $validatedData['harga'];
        $hargaBersih = str_replace('.', '', $rawHarga);

        // Validasi harga
        if (!is_numeric($hargaBersih)) {
            return back()->withErrors(['harga' => 'Format harga tidak valid.']);
        }

        // Konversi harga menjadi integer
        $hargaInt = (int) $hargaBersih;

        // Menyimpan gambar jika ada
        $imagePath = $produk->gambar;
        if ($request->hasFile("gambar")) {
            // Hapus gambar lama jika ada
            if ($imagePath) {
                Storage::disk('public')->delete("images/$imagePath");
            }
            // Simpan gambar baru
            $image = $request->file("gambar");
            $imagePath = $image->store('images', 'public');
        }

        // Memperbarui data produk
        $produk->update([
            "user_id" => Auth::id(),
            "nama_produk" => $validatedData["nama_produk"],
            "harga" => $hargaInt,  // Simpan harga sebagai integer
            "stok" => $validatedData["stok"],
            "deskripsi" => $validatedData["deskripsi"],
            "gambar" => basename($imagePath),
        ]);

        return redirect()->route("admin.dataProduk")->with("success", "Data produk berhasil diubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        if ($produk->keranjangItems()->exists()) {
            return redirect()->route("admin.dataProduk")
                ->with("error", "Produk tidak dapat dihapus karena masih ada di keranjang.");
        }

        if ($produk->orderItems()->exists()) {
            return redirect()->route("admin.dataProduk")
                ->with("error", "Produk tidak dapat dihapus karena masih ada di order item.");
        }

        try {
            // Hapus gambar terkait produk
            if ($produk->gambar && Storage::disk('public')->exists("images/$produk->gambar")) {
                Storage::disk('public')->delete("images/$produk->gambar");
            }

            // Hapus data produk
            $produk->delete();

            return redirect()->route("admin.dataProduk")->with("success", "Data produk berhasil dihapus.");
        } catch (\Exception $e) {
            Log::error('Error during product deletion: ' . $e->getMessage());
            return redirect()->route("admin.dataProduk")
                ->with("error", "Gagal menghapus produk: " . $e->getMessage());
        }
    }
}
