<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MNotifications extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'title',
        'message',
        'target_role',
        'related_id',
        'is_read',
    ];
}

