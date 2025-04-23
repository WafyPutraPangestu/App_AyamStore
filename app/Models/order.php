<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\models\Pembayaran;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_order',
        'total',
        'status'
    ];

    // Relasi menggunakan camelCase
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order_details() // Diubah ke camelCase
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function pembayaran() // Diubah ke camelCase
    {
        return $this->hasMany(Pembayaran::class);
    }
}
