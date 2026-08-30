<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Barang extends Model
{
    /** @use HasFactory<\Database\Factories\BarangFactory> */
    use HasFactory;

    protected $table = "tb_barang";
    protected $primaryKey = 'id_barang';

    protected $fillable = ['nama_barang', 'tgl', 'harga_awal', 'deskripsi_barang', 'thumbnail', 'id_kategori_barang', 'id_petugas'];

    public function history_lelang(): HasMany
    {
        return $this->hasMany(HistoryLelang::class, 'id_barang');
    }
    public function lelang(): HasOne
    {
        return $this->hasOne(Lelang::class, 'id_barang');
    }

    public function kategori_barang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori_barang');
    }


  public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
