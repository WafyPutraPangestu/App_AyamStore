<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class produk extends Model
{
    protected $table = "produk";
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

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
