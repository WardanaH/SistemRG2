<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('stok_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->double('banyak_stok')->default(0);
            $table->string('satuan', 50)->nullable();
            $table->boolean('stok_hitung_luas')->default(false);

            // Relasi bahan baku dan cabang
            $table->foreignId('bahanbaku_id')
                ->constrained('bahanbakus')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('cabang_id')
                ->constrained('cabangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_bahan_bakus');
    }
};
