<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MTransaksiBahanBakus extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi_bahan_bakus'; // pakai nama tabel lama
    protected $fillable = [
        'tanggal',
        'bahanbaku_id',
        'cabangdari_id',
        'cabangtujuan_id',
        'banyak',
        'satuan',
        'keterangan',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | 🔗 Relasi
    |--------------------------------------------------------------------------
    */

    // Relasi ke Bahan Baku
    public function bahanbaku()
    {
        return $this->belongsTo(MBahanBakus::class, 'bahanbaku_id');
    }

    // Relasi ke Cabang asal
    public function cabangDari()
    {
        return $this->belongsTo(Cabang::class, 'cabangdari_id');
    }

    // Relasi ke Cabang tujuan
    public function cabangTujuan()
    {
        return $this->belongsTo(Cabang::class, 'cabangtujuan_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
