<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\katalog;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PembayaranController;
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
        Route::patch('update/{produk}', 'update')->name('update');
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
        Route::get('profile', 'profileView')->name('profile');
    });
    // Route::controller(OrderController::class)->prefix('user')->name('user.')->group(function () {
    //     Route::get('order', 'orderView')->name('order');
    // });
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

    Route::controller(PembayaranController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('order-form/{id}', action: 'orderView')->name('order-form');
        Route::post('order-form', 'mockCheckout')->name('order-store');
        Route::get('pembayaran/{pembayaran}', 'chekout')->name('pembayaran');
        Route::get('order-form/success/{pembayaran}', 'success')->name('order-success');
        Route::get('order/success', 'successView')->name('success');
        Route::get('order-form/pending/{pembayaran}', 'pending')->name('order-pending'); // <-- Route Baru Pending
        Route::get('order-form/failed/{pembayaran}', 'failed')->name('order-failed');

        Route::get('riwayat', 'riwayat')->name('riwayat');
    });
    Route::delete('/user/delete/{order}', [OrderController::class, 'cancelOrder'])->name('user.delete');






    // Rute untuk menampilkan form pemesanan (GET)
    // Route::get('/user/order', [PembayaranController::class, 'showOrderForm'])->name('user.order.create');

    // Rute untuk memproses form pemesanan (POST)
    // Route::post('/user/order', [PembayaranController::class, 'mockCheckout'])->name('user.order.store');

    // Rute untuk menampilkan halaman pembayaran dengan snapToken (GET)
    // Route::get('/user/order/{pembayaran}', [PembayaranController::class, 'chekout'])->name('user.order.view');
    // Route::get('/user/order/succes/{pembayaran}', [PembayaranController::class, 'success'])->name('user.order.success');

    // Rute untuk halaman sukses setelah pembayaran
    // Route::get('/user/success', [PembayaranController::class, 'successView'])->name('user.success');
});

// ROUTE UNTUK KURIR
Route::middleware('kurir')->group(function () {
    Route::controller(KurirController::class)
        ->prefix('kurir')
        ->name('kurir.')
        ->group(function () {
            Route::get('profile',        'profileKurir')->name('profile');
            Route::put('profile',        'updateProfile')->name('profile.update');
            Route::get('tugas',          'tugasView')->name('tugas');
            Route::post('ambil-tugas',  'ambilTugas')->name('ambil-tugas');
            Route::get('manajemen', 'manajemenTugasView')->name('manajemen');
            Route::patch('manajemen/{order}', 'updateTugas')->name('manajemen-update');

            // RIWAYAT
            Route::get('riwayat', 'riwayatKurir')->name('riwayat');
        });
});
