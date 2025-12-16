<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MProjects extends Model
{
    protected $table = 'm_projects';

    protected $fillable = [
        'name',
        'description',
        'value_projects',
        'status',
        'paid_status',
        'm_company_id',
    ];

    public function mCompany()
    {
        return $this->belongsTo(MCompany::class);
    }

    public function mTasks()
    {
        return $this->hasMany(MTasks::class);
    }

    public function mProjectTaskProgress()
    {
        return $this->hasMany(MProjectTaskProgress::class);
    }
}
