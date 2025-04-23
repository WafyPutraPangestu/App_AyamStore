<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\PembayaranFactory> */
    use HasFactory;
    protected $table = "pembayaran";
    protected $fillable = [
        'id',
        'order_id',
        'status',
        'created_at',
        'updated_at'
    ];
    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
