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
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor_nota', 128)->unique();
            $table->string('hp_pelanggan', 13)->nullable();
            $table->string('nama_pelanggan', 100)->nullable();

            // Relasi ke pelanggan (pakai unsignedInteger agar cocok dengan increments di pelanggans)
            $table->unsignedInteger('pelanggan_id')->nullable();
            $table->foreign('pelanggan_id')
                ->references('id')
                ->on('pelanggans')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->date('tanggal')->index();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('pajak', 15, 2)->default(0);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->decimal('jumlah_pembayaran', 15, 2)->default(0);
            $table->decimal('sisa_tagihan', 15, 2)->default(0);
            $table->text('reason_on_delete')->nullable();
            $table->text('reason_on_edit')->nullable();

            // Relasi ke user dan cabang (biarkan bigInt karena id-nya juga bigInt)
            $table->foreignId('user_id')
                ->constrained('users')
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
        Schema::dropIfExists('transaksi_penjualans');
    }
};
