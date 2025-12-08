<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTasks extends Model
{
    protected $table = 'm_tasks';

    protected $fillable = [
        'm_company_id',
        'name',
        'description',
        'input_type',
    ];

    public function mCompany()
    {
        return $this->belongsTo(MCompany::class);
    }

    public function mProjectTaskProgress()
    {
        return $this->hasMany(MProjectTaskProgress::class);
    }
}
