<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MProduks extends Model
{
    use SoftDeletes;

    protected $table = 'produks';

    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'satuan',
        'harga_beli',
        'harga_jual',
        'hitung_luas',
        'keterangan',
    ];

    protected $dates = ['deleted_at'];

    // Relasi ke tabel kategori
    public function kategori()
    {
        return $this->belongsTo(MKategories::class, 'kategori_id');
    }
}
