<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MPelanggans extends Model
{
    protected $table = 'Pelanggans';
    public $timestamps = true;

    protected $fillable = [
        'jenispelanggan_id',
        'nama_perusahaan',
        'nama_pemilik',
        'telpon_pelanggan',
        'hp_pelanggan',
        'email_pelanggan',
        'alamat_pelanggan',
        'tempo_pelanggan',
        'limit_pelanggan',
        'norek_pelanggan',
        'keterangan_pelanggan',
        'ktp',
        'status_pelanggan',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
