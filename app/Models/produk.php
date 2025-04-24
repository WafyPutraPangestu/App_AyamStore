<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukFactory> */
    use HasFactory;
    protected $fillable = [
        'id',
        'nama_produk',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
        'created_at',
        'updated_at'
    ];


    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'order_id');
    }
    public function keranjangItems()
    {
        return $this->hasMany(KeranjangsItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'produk_id');
    }
}
