<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    /** @use HasFactory<\Database\Factories\KeranjangFactory> */
    use HasFactory;
    protected $table = "keranjang";
    protected $fillable = [
        'id',
        'user_id',
        'produk_id',
        'jumlah_produk',
        'total_harga',
        'created_at',
        'updated_at'
    ];
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
