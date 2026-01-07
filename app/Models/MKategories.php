<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MKategories extends Model
{
    use SoftDeletes;

    protected $table = 'kategories';

    protected $fillable = [
        'Nama_Kategori',
        'Keterangan',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    public function produks()
    {
        return $this->hasMany(MProduks::class, 'kategori_id');
    }
}
