<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MInventarisKantor extends Model
{
    use HasFactory, SoftDeletes;

    // Table sesuai dengan database
    protected $table = 'inventaris_kantors';

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_cabang',
        'kode_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'kondisi',
        'keterangan',
        'qrcode_path',
        'tanggal_input'
    ];

    public $timestamps = true;

    // Relasi ke tabel cabang (foreign key = id_cabang)
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id');
    }
}
