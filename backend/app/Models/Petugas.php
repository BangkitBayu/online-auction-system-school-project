<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Petugas extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\PetugasFactory> */
    use HasFactory, HasApiTokens;

    protected $table = 'tb_petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'nama_petugas',
        'username',
        'password',
        'telp',
        'id_level'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function lelangs(): HasMany
    {
        return $this->hasMany(Lelang::class, 'id_petugas');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'id_level');
    }
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_petugas');
    }
}
