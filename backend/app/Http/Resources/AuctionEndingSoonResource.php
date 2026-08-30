<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionEndingSoonResource extends JsonResource
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
            'thumbnail_url' => asset('storage/' . $this->thumbnail_url),
            'nama_lot' => $this->nama_lot,
            'tgl_selesai' => $this->tgl_selesai
        ];
    }
}
