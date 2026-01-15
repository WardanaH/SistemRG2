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
        Schema::create('m_bantuan_transaksi_penjualans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nomor_nota', 128)->unique();
            $table->string('nomor_nota_transaksi', 13)->unique();
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
            $table->enum('status_transaksi', ['proses', 'selesai', 'cancel'])->default('proses');
            $table->enum('status_bantuan_transaksi', ['proses', 'selesai', 'cancel'])->default('proses');
            $table->enum('status_persetujuan_bantuan_transaksi', ['pending', 'acc', 'tolak'])->default('pending');

            // Relasi ke user dan cabang (biarkan bigInt karena id-nya juga bigInt)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('cabang_id')
                ->constrained('cabangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('bantuan_cabang_id')
                ->constrained('cabangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignID('designer_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_bantuan_transaksi_penjualans');
    }
};
