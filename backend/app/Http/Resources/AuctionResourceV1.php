<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResourceV1 extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_lelang,
            'periode' => [
                'mulai' => $this->tgl_mulai_lelang,
                'selesai' => $this->tgl_akhir_lelang,
            ],
            'barang' => [
                'id' => $this->id_barang,
                'nama' => $this->nama_barang,
                'thumbnail_url' => $this->thumbnail_url ? asset('storage/' . $this->thumbnail_url) : null,
                'deskripsi' => $this->deskripsi_barang,
                'harga_awal' => (float) $this->harga_awal,
            ],
        ];
    }
}
