<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{
    use SoftDeletes;

    protected $table = 'suppliers';
    protected $fillable = [
        'nama_supplier',
        'pemilik_supplier',
        'telpon_supplier',
        'email_supplier',
        'alamat_supplier',
        'rekening_suppliers',
        'keterangan_suppliers',
        'user_id',
    ];

    protected $dates = ['deleted_at'];
}
