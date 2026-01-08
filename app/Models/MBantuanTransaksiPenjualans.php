<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MBantuanTransaksiPenjualans extends Model
{
    use SoftDeletes;

    protected $table = 'm_bantuan_transaksi_penjualans';

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
        'status_bantuan_transaksi',
        'status_persetujuan_bantuan_transaksi',
        'user_id',
        'cabang_id',
        'bantuan_cabang_id',
        'designer_id'
    ];

    // Relasi ke Cabang Asal (A)
    public function cabangAsal(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    // Relasi ke Cabang Bantuan (B)
    public function cabangBantuan(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'bantuan_cabang_id');
    }

    // Relasi ke Sub Transaksi (Barang-barangnya)
    public function subBantuan(): HasMany
    {
        return $this->hasMany(MSubBantuanTransaksiPenjualans::class, 'bantuan_penjualan_id');
    }

    // Relasi ke Admin yang membuat (Cabang A)
    public function adminPembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
