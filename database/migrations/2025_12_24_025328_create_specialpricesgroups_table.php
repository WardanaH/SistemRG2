<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('specialpricesgroups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('jenispelanggan_id');
            $table->foreign('jenispelanggan_id')
                  ->references('id')
                  ->on('jenispelanggans')
                  ->cascadeOnDelete();
            $table->foreignId('produk_id')
                  ->constrained('produks')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->double('harga_khusus');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialpricesgroups');
    }
};
