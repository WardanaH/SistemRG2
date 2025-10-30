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
        Schema::create('transaksi_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('bahanbaku_id')->constrained('bahanbakus')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('cabangdari_id')->constrained('cabangs')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('cabangtujuan_id')->constrained('cabangs')->restrictOnDelete()->cascadeOnUpdate();
            $table->double('banyak');
            $table->string('satuan', 20);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_bahan_bakus');
    }
};
