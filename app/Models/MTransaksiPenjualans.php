<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MTransaksiPenjualans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_penjualans';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nomor_nota',
        'hp_pelanggan',
        'nama_pelanggan',
        'pelanggan_id',
        'tanggal',
        'total_harga',
        'diskon',
        'pajak',
        'metode_pembayaran',
        'jumlah_pembayaran',
        'sisa_tagihan',
        'user_id',
        'cabang_id',
    ];

    /**
     * Relasi ke detail penjualan (sub transaksi)
     */
    public function subPenjualans()
    {
        return $this->hasMany(MSubTransaksiPenjualans::class, 'penjualan_id');
    }

    // /**
    //  * Relasi ke tabel angsuran
    //  */
    // public function angsurans()
    // {
    //     return $this->hasMany(Angsuran::class, 'penjualan_id');
    // }

    /**
     * Relasi ke pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(MPelanggans::class, 'pelanggan_id');
    }

    /**
     * Relasi ke user (kasir / admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}
