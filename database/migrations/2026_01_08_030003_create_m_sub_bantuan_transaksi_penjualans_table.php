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
        Schema::create('m_sub_bantuan_transaksi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            // Relasi ke transaksi utama
            $table->unsignedBigInteger('bantuan_penjualan_id');

            // Detail produk yang dijual
            $table->unsignedBigInteger('produk_id');
            $table->double('harga_satuan');
            $table->double('panjang')->default(0);
            $table->double('lebar')->default(0);
            $table->integer('banyak')->default(1);
            $table->longText('keterangan')->nullable();

            // Informasi tambahan
            $table->unsignedBigInteger('user_id');
            $table->double('subtotal')->default(0);
            $table->double('diskon')->default(0);
            $table->string('finishing', 100)->nullable();
            $table->string('satuan', 20)->nullable();
            $table->integer('no_spk')->unique()->nullable();
            $table->enum('status_sub_transaksi', ['proses', 'selesai', 'cancel'])->default('proses');

            // Foreign keys (opsional)
            $table->foreign('bantuan_penjualan_id')
                ->references('id')
                ->on('m_bantuan_transaksi_penjualans')
                ->onDelete('cascade');

            $table->foreign('produk_id')
                ->references('id')
                ->on('produks')
                ->onDelete('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_sub_bantuan_transaksi_penjualans');
    }
};
