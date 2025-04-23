<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class katalog extends Controller
{
    public function katalogView()
    {
        $katalog = Produk::latest()->paginate(10);
        return view('user.katalog', compact('katalog'));
    }
}
