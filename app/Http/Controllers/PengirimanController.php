<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    public function pengirimanView()
    {
        $order = Order::with('kurir')
            ->where('user_id', Auth::user()->id)
            ->first();
        return view("user.pengiriman");
    }
}
