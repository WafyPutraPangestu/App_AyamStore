<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class produk extends Model
{
    /** @use HasFactory<\Database\Factories\produkFactory> */
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

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function order_detail(): HasMany
    {
        return $this->hasMany(order_detail::class, 'produk_id');
    }
    public function keranjangs(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'produk_id');
    }
}
