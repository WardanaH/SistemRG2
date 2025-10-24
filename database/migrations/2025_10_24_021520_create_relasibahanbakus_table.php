<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relasibahanbakus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('bahanbaku_id');
            $table->integer('qtypertrx');

            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
            $table->foreign('bahanbaku_id')->references('id')->on('bahanbakus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relasibahanbakus');
    }
};
