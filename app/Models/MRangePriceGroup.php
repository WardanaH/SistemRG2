<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MRangePriceGroup extends Model
{
    use SoftDeletes;

    protected $table = 'range_price_groups';

    protected $fillable = [
        'special_price_group_id',
        'produk_id',
        'nilai_awal',
        'nilai_akhir',
        'harga_khusus',
        'user_id',
    ];


    public function specialPricesGroup()
    {
        return $this->belongsTo(
            MSpecialPricesGroup::class,
            'special_price_group_id'
        )->withTrashed();
    }

    public function produk()
    {
        return $this->belongsTo(MProduks::class, 'produk_id');
    }

}
