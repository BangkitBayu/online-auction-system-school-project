<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBarang extends Model
{
    use HasFactory;
    protected $table = 'tb_kategori_barang';
    protected $primaryKey = 'id_kategori_barang';

    protected $fillable = ['nama_kategori_barang'];


    public function barangs(): HasMany
    {
        return $this->hasMany(KategoriBarang::class, 'id_kategori_barang');
    }
}
