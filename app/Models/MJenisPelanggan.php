<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MJenisPelanggan extends Model
{
    protected $table = 'Jenispelanggans';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'jenis_pelanggan',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
