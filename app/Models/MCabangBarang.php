<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCabangBarang extends Model
{
    protected $table = 'cabang_barangs';

    protected $fillable = [
        'id_cabang',
        'nama_barang',
        'kode_barang',
        'stok',
        'satuan',
        'keterangan',
    ];
}
