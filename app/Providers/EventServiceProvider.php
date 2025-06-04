<?php

namespace App\Providers;

use App\Listeners\UpdateKurirStatusOnLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Logout;
use App\Listeners\UpdateKurirStatusOnLogout;
use Illuminate\Auth\Events\Login;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Logout::class => [
            UpdateKurirStatusOnLogout::class,
        ],
        Login::class => [
            UpdateKurirStatusOnLogin::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
