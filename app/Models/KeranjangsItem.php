<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeranjangsItem extends Model
{
    protected $fillable = [
        'keranjang_id',
        'produk_id',
        'quantity',
        'created_at',
        'updated_at'
    ];
    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class);
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
