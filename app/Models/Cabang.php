<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// class Cabang extends Model
// {
//     use HasFactory, SoftDeletes;

//     protected $fillable = [
//         'kode',
//         'nama',
//         'slug',
//         'email',
//         'telepon',
//         'alamat',
//         'jenis'
//     ];

//     public function users()
//     {
//         return $this->hasMany(User::class, 'cabang_id');
//     }
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cabangs';      
    protected $primaryKey = 'id';      
    protected $fillable = [
        'kode',
        'nama',
        'slug',
        'email',
        'telepon',
        'alamat',
        'jenis'
    ];

    /**
     * Relasi ke model User
     * Satu cabang punya banyak user.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_cabang', 'id');
    }
}
