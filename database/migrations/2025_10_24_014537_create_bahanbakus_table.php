<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel bahanbakus.
     */
    public function up(): void
    {
        Schema::create('bahanbakus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('kategori_id');
            $table->string('nama_bahan', 200);
            $table->string('satuan', 20);
            $table->double('harga');
            $table->integer('batas_stok');
            $table->boolean('hitung_luas')->default(false);
            $table->text('keterangan')->nullable();

            // Relasi ke tabel kategories
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('kategories')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Undo migrasi tabel bahanbakus.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahanbakus');
    }
};
