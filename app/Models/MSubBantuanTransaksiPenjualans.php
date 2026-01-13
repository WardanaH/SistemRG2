<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MSubBantuanTransaksiPenjualans extends Model
{
    use SoftDeletes;

    protected $table = 'm_sub_bantuan_transaksi_penjualans';

    protected $fillable = [
        'bantuan_penjualan_id',
        'produk_id',
        'harga_satuan',
        'panjang',
        'lebar',
        'banyak',
        'keterangan',
        'user_id',
        'subtotal',
        'diskon',
        'finishing',
        'satuan',
        'no_spk',
        'status_sub_transaksi'
    ];

    // Relasi balik ke Transaksi Bantuan Utama
    public function transaksiUtama(): BelongsTo
    {
        return $this->belongsTo(MBantuanTransaksiPenjualans::class, 'bantuan_penjualan_id');
    }

    // Relasi ke Produk
    public function produk(): BelongsTo
    {
        return $this->belongsTo(MProduks::class, 'produk_id');
    }
}
