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


    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi
    protected $fillable = [
        'cabang_id',
        'kode_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'kondisi',
        'keterangan',
        'qr_code',
        'tanggal_input'
    ];

    public $timestamps = true;

    // Relasi ke tabel cabang (foreign key = id_cabang)
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id');
    }
}
