<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('pengiriman_gudangs', function (Blueprint $table) {
            $table->id();

            // Cabang asal 
            $table->unsignedBigInteger('cabang_asal_id');
            $table->unsignedBigInteger('cabang_tujuan_id');
            $table->unsignedBigInteger('bahanbaku_id');
            $table->unsignedBigInteger('user_id');

            $table->double('jumlah');
            $table->string('satuan', 50); 
            $table->string('tujuan_pengiriman')->nullable();
            $table->date('tanggal_pengiriman');

            $table->enum('status_pengiriman', [
                'Dikemas',
                'Dikirim',
                'Diterima'
            ])->default('Dikemas');

            $table->date('tanggal_diterima')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('bahanbaku_id')
                ->references('id')->on('bahanbakus')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('cabang_asal_id')
                ->references('id')->on('cabangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('cabang_tujuan_id')
                ->references('id')->on('cabangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_gudangs');
    }
};
