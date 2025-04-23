<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangsItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // BUAT USER
    public function userKeranjang()
    {
        $keranjangs = Keranjang::where('user_id', Auth::user()->id)->first();
        return view('user.keranjang', compact('keranjangs'));
    }
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item = \App\Models\KeranjangsItem::findOrFail($id);
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['success' => true]);
    }
    public function destroy($produk_id)
    {

        $keranjang = Keranjang::where('user_id', Auth::user()->id)->first();
        if (!$keranjang) {
            return redirect()->back()->with('error', 'Keranjang tidak ditemukan');
        }
        KeranjangsItem::where('keranjang_id', $keranjang->id)
            ->where('produk_id', $produk_id)
            ->delete();
        return redirect()->back()->with('success', 'Keranjang berhasil dihapus');
    }
    public function bulkDestroy(Request $request)
    {
        $ids = explode(',', $request->input('selected_ids'));

        $keranjang = Keranjang::where('user_id', Auth::id())->first();

        if (!$keranjang) {
            return redirect()->back()->with('error', 'Keranjang tidak ditemukan');
        }

        KeranjangsItem::where('keranjang_id', $keranjang->id)
            ->whereIn('id', $ids)
            ->delete();

        return redirect()->back()->with('success', 'Item yang dipilih berhasil dihapus');
    }






    // BUAT ADMIN
}
