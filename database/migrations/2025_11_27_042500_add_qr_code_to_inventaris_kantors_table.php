<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('inventaris_kantors', function (Blueprint $table) {
        $table->string('qr_code')->nullable();
    });
}

public function down()
{
    Schema::table('inventaris_kantors', function (Blueprint $table) {
        $table->dropColumn('qr_code');
    });
}

};
