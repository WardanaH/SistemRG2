<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('nama_supplier', 68);
            $table->string('pemilik_supplier', 68);
            $table->string('telpon_supplier', 13);
            $table->string('email_supplier', 160);
            $table->text('alamat_supplier');
            $table->string('rekening_suppliers', 30);
            $table->mediumText('keterangan_suppliers')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
