<?php

namespace App\Http\Controllers;

use App\Models\LogUser;
use App\Models\MNotifications;

abstract class Controller
{
    public function log($log, $category)
    {
        $table = new LogUser();
        $table->user_id = auth()->user()->id;
        $table->log = $log;
        $table->jenis_log = $category;
        $table->save();
    }

    /* ==============================
       GLOBAL NOTIFICATION HELPER
    ============================== */
    protected function createNotification(
        string $type,
        string $title,
        string $message,
        string $targetRole = null,
        int $relatedId = null
    ) {
        MNotifications::create([
            'type'        => $type,
            'title'       => $title,
            'message'     => $message,
            'target_role' => $targetRole,
            'related_id'  => $relatedId,
        ]);
    }
}
