<?php

use App\Models\Keranjang;
use App\Models\Produk;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kurir_id')->nullable()->constrained('kurirs')->nullOnDelete();
            $table->text('alamat_pengiriman')->nullable();
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'selesai', 'gagal'])->default('pending');
            $table->enum('status_pengiriman', [
                'mencari_kurir',      // Baru dibuat, belum ada kurir
                'menunggu_pickup',    // Kurir sudah ditugaskan, menunggu barang diambil
                'sedang_diantar',     // Barang sedang dalam perjalanan
                'terkirim',           // Barang sudah sampai tujuan
                'gagal_kirim'         // Pengiriman gagal (alasan bisa dicatat di tempat lain)
            ])
                ->default('mencari_kurir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
