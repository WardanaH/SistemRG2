<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris_kantors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cabang_id');
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->integer('jumlah')->default(0);
            $table->string('kondisi')->default('Baik'); // Baik, Rusak, Perlu Perbaikan
            $table->string('lokasi')->nullable();
            $table->date('tanggal_input')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris_kantors');
    }
};
