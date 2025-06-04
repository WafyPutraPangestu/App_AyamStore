<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class UpdateKurirStatusOnLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if ($user && $user->role === 'kurir' && $user->kurir) {
            $currentStatus = $user->kurir->status;

            // Jika status sebelumnya 'tidak_aktif' maka ubah ke 'tersedia'
            if ($currentStatus === 'tidak_aktif') {
                $user->kurir->update(['status' => 'tersedia']);
            }
            // Kalau statusnya 'mengantar' atau lainnya, biarkan apa adanya
        }
    }
}
