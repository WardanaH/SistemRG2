<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MStokBahanBakus extends Model
{
    use SoftDeletes;

    protected $table = 'stok_bahan_bakus';
    protected $fillable = [
        'banyak_stok',
        'satuan',
        'stok_hitung_luas',
        'bahanbaku_id',
        'cabang_id',
    ];

    // RELASI
    public function bahanbaku()
    {
        return $this->belongsTo(MBahanBakus::class, 'bahanbaku_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}
