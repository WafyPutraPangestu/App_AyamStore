<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\produkFactory> */
    use HasFactory;
    protected $table = "pembayaran";
    protected $fillable = [
        'id',
        'order_id',
        'atas_nama',
        'no_rek',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'created_at',
        'updated_at'
    ];
    public function order()
    {
        return $this->belongsTo(order::class, 'order_id');
    }
}
