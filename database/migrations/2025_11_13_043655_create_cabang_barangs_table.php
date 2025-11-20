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
    Schema::create('cabang_barangs', function (Blueprint $table) {
        $table->id();

        // Cabang FK
        $table->unsignedBigInteger('id_cabang');

        // Data Barang
        $table->string('nama_barang');
        $table->string('kode_barang')->nullable();
        $table->integer('stok')->default(0);
        $table->string('satuan')->nullable(); // pcs, box, unit

        // Optional
        $table->text('keterangan')->nullable();

        $table->timestamps();

        // Foreign Key
        $table->foreign('id_cabang')->references('id')->on('cabangs')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('cabang_barangs');
}

};
