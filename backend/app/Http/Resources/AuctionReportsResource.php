<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionReportsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_lelang' => $this->id_lelang,
            'nama_lot' => $this->nama_lot,
            'kategori_lot' => $this->kategori_lot,
            'tgl_selesai' => $this->tgl_selesai,
            'pemenang' => $this->pemenang ?? 'kosong',
            'harga_akhir' => (float) $this->harga_akhir,
        ];
    }
}
