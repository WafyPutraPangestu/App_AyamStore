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
    public function inputView()
    {
        return view("admin.input");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function produkView()
    {
        $produk = Produk::latest()->simplePaginate(2);
        return view("admin.dataProduk", compact("produk"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeInput(Request $request)
    {
        // dd($request->all());
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
        $rawHarga = $input['harga'];

        $hargaBersih = str_replace('.', '', $rawHarga);

        if (!is_numeric($hargaBersih)) {
            return back()->withErrors(['harga' => 'Format harga tidak valid.']);
        }
        $image = $request->file("gambar");
        $imagePath = $image->store('images', 'public');

        Produk::create([
            "user_id" => Auth::id(),
            "nama_produk" => $input["nama_produk"],
            "harga" => $input["harga"],
            "stok" => $input["stok"],
            "deskripsi" => $input["deskripsi"],
            "gambar" => basename($imagePath),
        ]);
        return redirect()->route("admin.dataProduk")->with("success", "");
    }



    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {


        $produk = Produk::where("id", $produk->id)->first();

        return view("admin.edit", compact("produk"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validatedData = $request->validate([
            "nama" => ["required", "string"],
            "ayam" => ["required", "string", "in:hidup,potong"],
            "satuan" => ["required", "string", "in:kg,ekor"],
            "harga" => ["required", "regex:/^[0-9.]+$/"],
            "stok" => ["required", "integer"],
            "deskripsi" => ["required", "string"],
            "gambar" => ["nullable", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
        ], [
            "harga.required" => "harganya harus diisi",
            "harga.regex" => "Format harga harus angka",
        ]);

        $rawHarga = $validatedData['harga'];
        $hargaBersih = str_replace('.', '', $rawHarga);
        if (!is_numeric($hargaBersih)) {
            return back()->withErrors(['harga' => 'Format harga tidak valid.']);
        }

        $imagePath = $produk->gambar;
        if ($request->hasFile("gambar")) {
            if ($imagePath) {
                Storage::disk('public')->delete("images/$imagePath");
            }
            $image = $request->file("gambar");
            $imagePath = $image->store('images', 'public');
        }

        $produk->update([
            "user_id" => Auth::id(),
            "nama" => $validatedData["nama"],
            "ayam" => $validatedData["ayam"],
            "satuan" => $validatedData["satuan"],
            "harga" => $validatedData["harga"],
            "stok" => $validatedData["stok"],
            "deskripsi" => $validatedData["deskripsi"],
            "gambar" => basename($imagePath),
        ]);
        return redirect()->route("admin.dataProduk")->with("success", "Data produk berhasil diubah");
    }


    public function destroy(Produk $produk)
    {
        // $produk = Produk::latest()->where("id", $produk->id)->first();

        if ($produk->Keranjang()->exists()) {
            return back()->with('error', 'Produk tidak bisa dihapus karena masih ada di keranjang pengguna.');
        }
        if ($produk->order_details()->exists()) {
            return redirect()->back()->with('error', 'Produk tidak bisa dihapus karena sedang digunakan dalam order.');
        }

        try {

            if ($produk->gambar && Storage::disk('public')->exists("images/$produk->gambar")) {
                $deleteGambar = Storage::disk('public')->delete("images/$produk->gambar");
                if (!$deleteGambar) {
                    Log::error('Gagal menghapus gambar: ' . $produk->gambar);
                    throw new \Exception("Gagal menghapus gambar produk.");
                }
            }


            $isDeleted = $produk->delete();
            if (!$isDeleted) {
                Log::error('Gagal menghapus produk: ' . $produk->id);
                throw new \Exception("Gagal menghapus data produk.");
            }

            return redirect()->route("admin.dataProduk")->with("success", "Data produk berhasil dihapus");
        } catch (\Exception $e) {
            Log::error('Error during product deletion: ' . $e->getMessage());
            return redirect()->route("admin.dataProduk")
                ->with("error", "Gagal menghapus data produk: " . $e->getMessage());
        }
    }
}
