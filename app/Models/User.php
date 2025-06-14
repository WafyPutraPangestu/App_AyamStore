<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = "users";
    protected $fillable = [
        'role',
        'name',
        'telepon',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
    public function kurir(): hasOne
    {
        // Menggunakan hasOne karena satu user hanya punya satu profil kurir
        // Laravel akan mencari foreign key 'user_id' di tabel 'kurirs' secara default
        return $this->hasOne(Kurir::class);
    }
    public function keranjangs(): HasMany
    {
        return $this->hasMany(Keranjang::class);
    }
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
