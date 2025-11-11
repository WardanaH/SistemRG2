<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSubTransaksiPenjualans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_transaksi_penjualans';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nama_produk',
        'harga_satuan',
        'panjang',
        'lebar',
        'kuantitas',
        'satuan',
        'keterangan',
        'sub_totalpenjualan',
        'status_sub_transaksi',
        'produk_id',
        'penjualan_id',
        'user_id',
        'cabang_id',
    ];

    /**
     * Relasi ke transaksi penjualan utama
     */
    public function penjualan()
    {
        return $this->belongsTo(MTransaksiPenjualans::class, 'penjualan_id');
    }

    /**
     * Relasi ke produk (termasuk produk yang dihapus soft)
     */
    public function produk()
    {
        return $this->belongsTo(MProduks::class, 'produk_id', 'id')->withTrashed();
    }

    /**
     * Relasi ke user (optional)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke cabang (optional)
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}
