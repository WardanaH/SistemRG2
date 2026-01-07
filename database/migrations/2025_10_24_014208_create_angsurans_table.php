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
        Schema::create('angsurans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota', 128)->unique();
            $table->date('tanggal_angsuran');
            $table->double('nominal_angsuran');
            $table->double('sisa_angsuran')->default(0);
            $table->string('metode_pembayaran', 30)->nullable();
            $table->text('reason_on_delete')->nullable();
            $table->text('reason_on_edit')->nullable();

            // Foreign keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->foreignId('transaksi_penjualan_id')
                ->constrained('transaksi_penjualans')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsurans');
    }
};
