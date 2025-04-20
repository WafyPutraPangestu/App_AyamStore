<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_detail extends Model
{
    /** @use HasFactory<\Database\Factories\produkFactory> */
    use HasFactory;
    protected $table = "order_details";
    protected $fillable = [
        'id',
        'user_id',
        'order_id',
        'produk_id',
        'nama_produk',
        'jumlah_produk',
        'harga',
        'total_harga',
        'created_at',
        'updated_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
