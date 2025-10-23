<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MPelanggans extends Model
{
    use SoftDeletes;

    // ✅ Gunakan huruf kecil dan plural
    protected $table = 'pelanggans';

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

    protected $dates = ['deleted_at'];

    // ✅ Tambahkan relasi ke Jenis Pelanggan
    public function jenisPelanggan()
    {
        return $this->belongsTo(MJenisPelanggan::class, 'jenispelanggan_id');
    }
}
