<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MRangePricePelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'range_price_pelanggans';

    protected $fillable = [
        'specialprice_id',
        'nilai_awal',
        'nilai_akhir',
        'harga_khusus',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    // relasi ke special price
    public function specialprice()
    {
        return $this->belongsTo(
            MSpecialPrices::class,
            'specialprice_id'
        );
    }
}
