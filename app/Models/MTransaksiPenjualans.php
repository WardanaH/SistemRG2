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
        'status_transaksi',
        'user_id',
        'cabang_id',
        'designer_id',
    ];

    /**
     * Relasi ke detail penjualan (sub transaksi)
     */
    public function subTransaksi()
    {
        return $this->hasMany(MSubTransaksiPenjualans::class, 'penjualan_id');
    }

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
     * Relasi ke user (designer)
     */
    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id')->withTrashed();
    }

    /**
     * Relasi ke cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    /**
     * Format total harga menjadi Rupiah
     */
    public function getTotalHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Format sisa tagihan jadi rupiah
     */
    public function getSisaTagihanFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    /**
     * Cek apakah transaksi sudah lunas
     */
    public function getIsLunasAttribute(): bool
    {
        return $this->sisa_tagihan <= 0;
    }
}
