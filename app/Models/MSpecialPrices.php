<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSpecialPrices extends Model
{
    use SoftDeletes;

    protected $table = 'spesialprices';

    protected $fillable = [
        'pelanggan_id',
        'produk_id',
        'harga_khusus',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    // relasi ke pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(MPelanggans::class, 'pelanggan_id');
    }

    // relasi ke produk
    public function produk()
    {
        return $this->belongsTo(MProduks::class, 'produk_id');
    }
}
