<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MJenisPelanggan extends Model
{
    use SoftDeletes;

    // ✅ Gunakan nama tabel dengan huruf kecil dan plural
    protected $table = 'jenispelanggans';

    protected $fillable = [
        'jenis_pelanggan',
    ];

    protected $dates = ['deleted_at'];

    // ✅ Relasi ke pelanggan
    public function pelanggans()
    {
        return $this->hasMany(MPelanggans::class, 'jenispelanggan_id');
    }
}
