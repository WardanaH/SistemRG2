<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCompany extends Model
{
    protected $table = 'm_companies';

    protected $fillable = [
        'name',
        'email',
        'descriptions',
    ];

    public function mTasks()
    {
        return $this->belongsTo(MTasks::class);
    }

    public function mProjects()
    {
        return $this->hasMany(MProjects::class);
    }
}
