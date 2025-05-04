<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function pengirimanView()
    {
        return view("user.pengiriman");
    }
}
