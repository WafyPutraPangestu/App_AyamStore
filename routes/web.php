<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// ROUTE UNTUK TAMU
Route::middleware('guest')->group(function () {
    Route::controller(SessionController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::get('login', 'loginView')->name('login');
        Route::get('register', 'registerView')->name('register');
        // ROUTE UNTUK MENGAKTIFKAN ATAU MEMFUNGSIKAN LOGIN DAN REGISTER
        Route::post('login', 'loginStore')->name('login');
        Route::post('register', 'registerStore')->name('register');
    });
});
Route::middleware('auth')->group(function () {
    Route::post('logout', [SessionController::class, 'logout'])->name('auth.logout');
});
// ROUTE UNTUK ADMIN
Route::middleware('admin')->group(function () {
    Route::controller(ProdukController::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('input', 'inputView')->name('input');
        Route::get('dataProduk', 'produkView')->name('dataProduk');
        Route::post('input', 'storeInput')->name('input');
        Route::delete('destroy/{produk}', 'destroy')->name('destroy');
        Route::get('edit/{produk}', 'edit')->name('edit');
        Route::put('update/{produk}', 'update')->name('update');
    });
    // ROUTE UNTUK ORDER
    Route::controller(OrderController::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('manajemen', 'index')->name('manajemen');
        // Route::get('order/{order}', 'show')->name('order.show');
        // Route::put('order/{order}', 'update')->name('order.update');
        // Route::delete('order/{order}', 'destroy')->name('order.destroy');
    });
    // ROUTE UNTUK DETAIL ORDER
    Route::controller(OrderDetailController::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('orderDetail', 'index')->name('orderDetail');
    });
    // ROUTE UNTUK DASHBOARD
    Route::controller(DashboardController::class)->prefix('admin.')->name('admin.')->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
});
