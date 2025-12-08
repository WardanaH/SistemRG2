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
        Schema::create('m_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('value_projects');
            $table->enum('status', ['Pending', 'Ongoing', 'Selesai'])->default('Pending');
            $table->enum('paid_status', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');

            $table->foreignId('m_company_id')
                ->constrained('m_companies')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_projects');
    }
};
