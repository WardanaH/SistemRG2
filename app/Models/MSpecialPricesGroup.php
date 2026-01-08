<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSpecialPricesGroup extends Model
{
    use SoftDeletes;

    protected $table = 'specialpricesgroups';

    protected $fillable = [
        'jenispelanggan_id',
        'produk_id',
        'harga_khusus',
        'user_id',
    ];

    public function jenispelanggan()
    {
        return $this->belongsTo(MJenisPelanggan::class, 'jenispelanggan_id');
    }

    public function produk()
    {
        return $this->belongsTo(MProduks::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function rangePrices()
    {
        return $this->hasMany(
            MRangePriceGroup::class,
            'special_price_group_id'
        );
    }
}
