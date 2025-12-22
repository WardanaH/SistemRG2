<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MBahanBakus extends Model
{
    use SoftDeletes;

    protected $table = 'bahanbakus';
    protected $fillable = [
        'kategori_id',
        'nama_bahan',
        'satuan',
        'harga',
        'batas_stok',
        'hitung_luas',
        'keterangan'
    ];

    public function kategori()
    {
        return $this->belongsTo(MKategories::class, 'kategori_id');
    }
}
