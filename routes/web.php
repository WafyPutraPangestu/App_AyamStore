<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\katalog;
use App\Http\Controllers\KeranjangController;
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
    Route::controller(DashboardController::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', 'adminDashboard')->name('dashboard');
    });
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
    });
    // ROUTE UNTUK DETAIL ORDER

});

// ROUTE UNTUK USER
Route::middleware('user')->group(function () {
    Route::controller(DashboardController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('dashboard', 'dashboardUser')->name('dashboard');
    });
    Route::controller(OrderController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('order', 'orderView')->name('order');
    });
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

    Route::controller(katalog::class)->prefix('user')->name('user.')->group(function () {
        Route::get('katalog', 'katalogView')->name('katalog');
        Route::post('katalog', 'katalogCreate')->name('katalog');
    });
    Route::controller(KeranjangController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('keranjang', 'userKeranjang')->name('keranjang');
    });
    Route::put('/keranjang/update-quantity/{id}', [KeranjangController::class, 'updateQuantity'])->name('keranjang.updateQuantity');
    Route::delete('/keranjang/delete/{product_id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::delete('/keranjang/bulk-destroy', [KeranjangController::class, 'bulkDestroy'])->name('keranjang.bulkDestroy');
});
