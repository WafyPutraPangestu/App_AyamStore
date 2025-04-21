<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\produk;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->string('atas_nama');
            $table->string('no_rek');
            $table->enum('metode_pembayaran', ['Dana', 'Gopay', 'Bank', 'Cash']);
            $table->string('bukti_pembayaran')->nullable();
            $table->decimal('total_pembayaran', 12, 2)->default(0);
            $table->enum('keterangan', ['menunggu konfirmasi', 'dibayar', 'berhasil', 'gagal', 'kadaluarsa'])->default('menunggu konfirmasi');
            $table->datetime('tanggal_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
