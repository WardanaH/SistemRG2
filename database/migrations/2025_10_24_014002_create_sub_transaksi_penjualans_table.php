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
        Schema::create('sub_transaksi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            // Detail barang/jasa yang dijual
            $table->string('nama_produk', 200);
            $table->double('harga_satuan');
            $table->double('panjang')->nullable();
            $table->double('lebar')->nullable();
            $table->double('kuantitas');
            $table->string('satuan', 15)->nullable();
            $table->longText('keterangan')->nullable();
            $table->double('sub_totalpenjualan');
            $table->text('reason_on_edit')->nullable();

            // Relasi ke user, cabang, dan transaksi penjualan utama
            $table->unsignedBigInteger('penjualan_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cabang_id');

            // Foreign keys
            $table->foreign('penjualan_id')->references('id')->on('transaksi_penjualans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_transaksi_penjualans');
    }
};
