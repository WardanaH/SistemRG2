<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'users'; // pastikan konsisten

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'telepon',
        'gaji',
        'alamat',
        'cabang_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'gaji' => 'decimal:2',
        'email_verified_at' => 'datetime',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id')->withTrashed();
    }
}
