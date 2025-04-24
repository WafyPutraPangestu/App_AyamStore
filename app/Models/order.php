<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\models\Pembayaran;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'produk_id',
        'keranjang_id',
        'status',
        'user_id',
        'total_harga',
    ];
    protected $guarded = ['total_harga'];

    // Relasi menggunakan camelCase

    public function pembayaran() // Diubah ke camelCase
    {
        return $this->hasMany(Pembayaran::class);
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function keranjangs()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'orders_id');
    }
}
