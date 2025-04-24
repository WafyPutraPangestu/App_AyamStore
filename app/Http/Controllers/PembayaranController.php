<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function orderView($id)
    {
        $order = Order::findOrFail($id);


        return view('user.order-form', compact('order'));
        // Tampilkan form pemesanan
    }


    public function mockCheckout(Request $request)
    {

        // dd($request->all());
        $transaction = Pembayaran::create([
            'order_id' => $request->order_id,
            "status" => 'pending',
        ]);

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $request->order_id, // Gunakan order_id yang sama
                'gross_amount' => $request->total_harga, // Total harga dari order,
            ),
            'customer_details' => array( // Perbaiki typo "costumer" menjadi "customer"
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ),

        );
        // dd($params);
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
        $pembayaran->status = 'lunas';
        $pembayaran->save();
        return view('user.success', compact('pembayaran'));
    }

    public function successView()
    {
        return view('user.success');
    }
}
