<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\KeranjangsItem;
use App\Models\order;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.manajemen");
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $keranjang = Keranjang::where('user_id', Auth::user()->id)->first();
            if (!$request->isJson()) {
                return response()->json(['error' => 'Invalid request. Expected JSON.'], 400);
            }

            $selectedItems = $request->input('selected_items');
            $totalPrice = $request->input('total');

            if (empty($selectedItems)) {
                return response()->json(['error' => 'Pilih minimal satu produk untuk checkout.'], 422);
            }
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'total_harga' => $totalPrice,
            ]);

            for ($i = 0; $i < count($selectedItems); $i++) {
                $order->items()->create([
                    'produk_id' => $selectedItems[$i]['produk_id'],
                    'quantity' => $selectedItems[$i]['quantity'],
                    'orders_id' => $order->id,
                ]);
                $keranjang->items()->where('produk_id', $selectedItems[$i]['produk_id'])
                    ->where('keranjang_id', $keranjang->id)
                    ->delete();
            }
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses pesanan.', 'stack' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'items' => $selectedItems, 'total' => $totalPrice, 'order_id' => $order->id], 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
