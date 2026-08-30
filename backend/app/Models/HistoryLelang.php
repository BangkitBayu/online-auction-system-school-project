<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryLelang extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryLelangFactory> */
    use HasFactory;

    protected $fillable = ['id_lelang', 'id_barang', 'id_user', 'penawaran_harga'];

    protected $table = "history_lelang";
    protected $primaryKey = 'id_history';

    public function masyarakats(): BelongsTo {
        return $this->belongsTo(User::class , 'id_user');
    }
    public function lelangs(): BelongsTo {
        return $this->belongsTo(Lelang::class , 'id_lelang');
    }
    public function barangs(): BelongsTo {
        return $this->belongsTo(Barang::class , 'id_barang');
    }


}
