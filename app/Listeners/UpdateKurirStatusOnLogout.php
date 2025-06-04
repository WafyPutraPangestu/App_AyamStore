<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateKurirStatusOnLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if ($user && $user->role === 'kurir') {
            // Coba log-informasi user dan relasi kurir
            Log::info('User logout:', ['id' => $user->id, 'role' => $user->role]);
            Log::info('Relasi kurir:', ['kurir' => $user->kurir]);

            if ($user->kurir) {
                $user->kurir->update(['status' => 'tidak_aktif']);
                Log::info("Status kurir {$user->name} berhasil diubah jadi tidak_aktif.");
            } else {
                Log::warning("User {$user->name} tidak punya relasi kurir.");
            }
        }
    }
}
