<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'email',
        'telepon',
        'alamat',
        'jenis'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }
}
