<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MPengirimanGudang extends Model
{
    use SoftDeletes;

    protected $table = 'pengiriman_gudangs';

    protected $fillable = [
        'cabang_asal_id',
        'cabang_tujuan_id',
        'bahanbaku_id',
        'jumlah',
        'satuan',
        'tujuan_pengiriman',
        'tanggal_pengiriman',
        'status_pengiriman',
        'tanggal_diterima',
        'keterangan',
        'user_id',
    ];

    // ===========================
    // RELASI KE BAHAN BAKU
    // ===========================
    public function bahanbaku()
    {
        return $this->belongsTo(MBahanBakus::class, 'bahanbaku_id');
    }

    // ===========================
    // RELASI KE CABANG ASAL
    // ===========================
    public function cabangAsal()
    {
        return $this->belongsTo(Cabang::class, 'cabang_asal_id');
    }

    // ===========================
    // RELASI KE CABANG TUJUAN
    // ===========================
    public function cabangTujuan()
    {
        return $this->belongsTo(Cabang::class, 'cabang_tujuan_id');
    }

    // ===========================
    // RELASI KE USER
    // ===========================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
