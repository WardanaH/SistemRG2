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
        Schema::create('m_project_task_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_project_id')
                ->constrained('m_projects')
                ->onDelete('cascade');
            $table->foreignId('m_task_id')
                ->constrained('m_tasks')
                ->onDelete('cascade');
            $table->enum('status_progress', ['Pending', 'Ongoing', 'Selesai'])->default('Pending');
            $table->string('file_bukti')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_project_task_progress');
    }
};
