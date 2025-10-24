<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MRelasiBahanBaku extends Model
{
    use SoftDeletes;

    protected $table = 'relasibahanbakus';
    protected $fillable = ['produk_id', 'bahanbaku_id', 'qtypertrx'];

    public function produk()
    {
        return $this->belongsTo(MProduks::class, 'produk_id');
    }

    public function bahanbaku()
    {
        return $this->belongsTo(MBahanBakus::class, 'bahanbaku_id');
    }
}
