<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('pengirimans', function (Blueprint $table) {
        $table->id('id_pengiriman');
        $table->unsignedBigInteger('id_gudang');
        $table->unsignedBigInteger('id_barang');
        $table->integer('jumlah');
        $table->string('tujuan_pengiriman');
        $table->date('tanggal_pengiriman');
        $table->enum('status_pengiriman', ['Dikemas','Dikirim','Diterima'])->default('Dikemas');
        $table->enum('status_penerimaan', ['Diterima', 'Ditolak'])->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};
