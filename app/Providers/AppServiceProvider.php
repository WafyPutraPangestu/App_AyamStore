<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
        Gate::define('user', function ($user) {
            return $user->role === 'user';
        });
        Gate::define('kurir', function ($user) {
            return $user->role === 'kurir';
        });

        // Aktifkan logging query SQL hanya di environment lokal untuk debugging
        if ($this->app->environment('local')) {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,       // Query SQL yang dieksekusi
                    $query->bindings,  // Parameter yang di-bind ke query
                    $query->time       // Waktu eksekusi query (ms)
                );
            });
        }
    }
}
