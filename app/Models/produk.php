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
    protected $table = "table_produk";
    protected $fillable = [
        'id',
        'user_id',
        'nama',
        'ayam',
        'satuan',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
        'created_at',
        'updated_at'
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'produk_id');
    }
    public function Keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'produk_id');
    }
}
