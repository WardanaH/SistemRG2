<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    Schema::create('spesialprices', function (Blueprint $table) {
        $table->id();

        $table->unsignedInteger('pelanggan_id');

        $table->unsignedBigInteger('produk_id');

        $table->double('harga_khusus');
        $table->unsignedBigInteger('user_id');

        $table->timestamps();
        $table->softDeletes();

        $table->foreign('pelanggan_id')
            ->references('id')
            ->on('pelanggans');

        $table->foreign('produk_id')
            ->references('id')
            ->on('produks');

        $table->foreign('user_id')
            ->references('id')
            ->on('users');

        $table->unique(['pelanggan_id', 'produk_id']);
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('spesialprices');
    }
};

