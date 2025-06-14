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
        'kurir_id',
        'alamat_pengiriman',
        'ongkir',
        'total_harga',
        'total',
        'status',
        'status_pengiriman',
        'bukti_pengiriman',
        'waktu_mulai_antar',
        'waktu_selesai_antar',
    ];
    protected $guarded = ['total_harga'];

    // Relasi menggunakan camelCase

    public function pembayaran() // Diubah ke camelCase
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function kurir()
    {
        return $this->belongsTo(kurir::class, 'kurir_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'orders_id');
    }
}
