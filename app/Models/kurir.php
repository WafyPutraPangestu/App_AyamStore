<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kurir extends Model
{
    protected $fillable = [
        'user_id',
        'kendaraan_info',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
