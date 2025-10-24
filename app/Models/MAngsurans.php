<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MAngsurans extends Model
{
    use SoftDeletes;

    protected $table = 'angsurans';

    protected $fillable = [
        'tanggal_angsuran',
        'nominal_angsuran',
        'sisa_angsuran',
        'metode_pembayaran',
        'user_id',
        'cabang_id',
        'transaksi_penjualan_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relasi ke Transaksi Penjualan
     */
    public function transaksiPenjualan()
    {
        return $this->belongsTo(MTransaksiPenjualans::class)->withTrashed();
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
