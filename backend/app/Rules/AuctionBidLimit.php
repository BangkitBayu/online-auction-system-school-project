<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class AuctionBidLimit implements ValidationRule
{
    protected int | string $id_lelang;

    public function __construct(int | string $id_lelang)
    {
        $this->id_lelang = $id_lelang;
    }
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $limit_price = DB::table('tb_lelang as lelang')
            ->where('lelang.id_lelang', '=', $this->id_lelang)
            ->join('tb_barang as barang', 'barang.id_barang', '=', 'lelang.id_barang')
            ->value('barang.harga_awal');

        if ($value < $limit_price) {
            $fail('Penawaran harga harus diatas harga minimum!');
        }
    }
}
