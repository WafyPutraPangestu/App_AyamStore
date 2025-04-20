<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    /** @use HasFactory<\Database\Factories\produkFactory> */
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        'id',
        'user_id',
        'tanggal_order',
        'total',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function order_detail()
    {
        return $this->hasMany(order_detail::class, 'order_id');
    }
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'order_id');
    }
}
