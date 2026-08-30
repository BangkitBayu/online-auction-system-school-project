<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lelang extends Model
{
    /** @use HasFactory<\Database\Factories\LelangFactory> */
    use HasFactory;

    protected $table = "tb_lelang";
    protected $primaryKey = 'id_lelang';

    protected $fillable = ['id_barang', 'tgl_mulai_lelang', 'tgl_akhir_lelang', 'harga_akhir', 'id_user', 'id_petugas', 'status'];

    public function winner(): HasOne
    {
        return $this->hasOne(HistoryLelang::class, 'id_lelang' , 'id_lelang')->ofMany('penawaran_harga', 'max');
    }

    public function masyarakats(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
    public function history_lelangs(): HasMany
    {
        return $this->hasMany(HistoryLelang::class, 'id_lelang');
    }
}
