<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionHistoryResources extends JsonResource
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
            'nama_lot' => $this->nama_barang,
            'tgl_selesai' => $this->tgl_selesai,
            'status_lelang' => $this->status_lelang,
            'penawaran_tertinggi_saat_ini' => (float) $this->penawaran_tertinggi_saat_ini,
            'penawaran_peserta' => (float) $this->penawaran_peserta
        ];
    }
}
