<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MProjectTaskProgress extends Model
{
    protected $table = 'm_project_task_progress';

    protected $fillable = [
        'm_project_id',
        'm_task_id',
        'status',
        'file_bukti'
    ];

    public function mProject()
    {
        return $this->belongsTo(MProjects::class);
    }

    public function mTask()
    {
        return $this->belongsTo(MTasks::class);
    }
}
