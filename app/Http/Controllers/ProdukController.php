<?php

namespace App\Http\Controllers;

use App\Models\produk;
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
        $produk = produk::latest()->simplePaginate(2);
        return view("admin.dataProduk", compact("produk"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeInput(Request $request)
    {
        // dd($request->all());
        $input = $request->validate([
            "nama" => ["required", "string"],
            "ayam" => ["required", "string", "in:hidup,potong"],
            "satuan" => ["required", "string", "in:kg,ekor"],
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
            "nama" => $input["nama"],
            "ayam" => $input["ayam"],
            "satuan" => $input["satuan"],
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
    public function show(produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(produk $produk)
    {
        $produk = produk::all();
        return view("admin.edit", compact("produk"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, produk $produk)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(produk $produk)
    {
        produk::destroy($produk->id);
        return redirect()->route("admin.dataProduk")->with("success", "Data produk berhasil dihapus");
    }
}
