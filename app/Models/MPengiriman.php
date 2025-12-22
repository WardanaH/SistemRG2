<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MPengiriman extends Model
{
    protected $table = 'pengirimans';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_gudang',
        'id_barang',
        'jumlah',
        'tujuan_pengiriman',
        'tanggal_pengiriman',
        'status_pengiriman',
        'status_penerimaan'
    ];

        public function barang()
    {
        return $this->belongsTo(\App\Models\MBahanBakus::class, 'id_barang', 'id');
    }
    public function stok()
    {
        return $this->belongsTo(\App\Models\MStokBahanBakus::class, 'id_stok', 'id');
    }
}
