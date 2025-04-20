<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

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

Route::middleware('admin')->group(function () {
    Route::controller(ProdukController::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('input', 'inputView')->name('input');
        Route::get('dataProduk', 'produkView')->name('dataProduk');
        Route::post('input', 'storeInput')->name('input');
        Route::post('destroy', 'destroy')->name('destroy');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
    });
    Route::controller(DashboardController::class)->prefix('admin.')->name('admin.')->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
    });
});
