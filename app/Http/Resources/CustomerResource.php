<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            // Sembunyikan field rahasia, format tanggal ISO-8601 agar gampang diparsing JS (date-fns)
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
