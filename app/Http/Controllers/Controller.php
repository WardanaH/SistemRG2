<?php

namespace App\Http\Controllers;

use App\Models\LogUser;

abstract class Controller
{
    public function log($log, $category)
    {
        $table = new LogUser();
        $table->log = $log;
        $table->jenis_log = $category;
        $table->save();
    }
}
