<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('range_price_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('special_price_group_id')
                ->constrained('specialpricesgroups')
                ->cascadeOnDelete();

            $table->integer('nilai_awal');
            $table->integer('nilai_akhir');

            $table->double('harga_khusus');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('range_price_groups');
    }
};
