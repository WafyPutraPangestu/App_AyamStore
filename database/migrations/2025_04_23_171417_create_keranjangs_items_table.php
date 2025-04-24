<?php


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
        Schema::create('keranjangs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keranjang_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjangs_items');
    }
};
