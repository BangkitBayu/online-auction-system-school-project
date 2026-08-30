<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /** @use HasFactory<\Database\Factories\LevelFactory> */
    use HasFactory;

    protected $table = 'tb_level';
    protected $primaryKey = 'id_level';

    protected $fillable = [
        'level'
    ];

    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'id_level');
    }
}
