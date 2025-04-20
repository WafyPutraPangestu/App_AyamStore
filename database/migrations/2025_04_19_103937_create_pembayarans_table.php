<?php

use App\Models\order;
use App\Models\order_detail;
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
            $table->foreignIdFor(order::class)->constrained()->cascadeOnDelete();
            $table->string('atas_nama');
            $table->string('no_rek');
            $table->enum('metode_pembayaran', ['Dana', 'Gopay', 'bank', 'cash']);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'dibayar'])->default('pending');
            $table->decimal('total_pembayaran', 12, 2)->default('menunggu');
            $table->enum('keterangan', ['menunggu konfirmasi', 'berhasil', 'gagal'])->default('menunggu konfirmasi');
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
