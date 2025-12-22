<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cabang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'slug',
        'email',
        'telepon',
        'alamat',
        'jenis'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cabang) {
            if (empty($cabang->slug)) {
                $cabang->slug = Str::slug($cabang->nama);
            }
        });
    }

        public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

}

