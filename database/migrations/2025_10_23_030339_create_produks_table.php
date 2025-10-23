<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            // 🔗 relasi ke tabel kategories
            $table->foreignId('kategori_id')
                  ->constrained('kategories')   
                  ->onDelete('cascade');      

            $table->string('nama_produk', 128);
            $table->string('satuan', 20);
            $table->double('harga_beli');
            $table->double('harga_jual');
            $table->boolean('hitung_luas')->default(false);
            $table->text('keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
