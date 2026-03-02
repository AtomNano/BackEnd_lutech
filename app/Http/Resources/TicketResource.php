<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'description' => $this->description,
            'jenis_device' => $this->jenis_device,
            'merk_device' => $this->merk_device,
            'estimasi' => $this->estimasi,
            'biaya_final' => $this->biaya_final,
            'status' => $this->status,
            'priority' => $this->priority,
            'customer' => $this->whenLoaded('customer', fn() => [
                'id' => $this->customer->id,
                'nama' => $this->customer->nama,
                'whatsapp' => $this->customer->whatsapp,
            ]),
            'technician' => $this->whenLoaded('technician', fn() => [
                'id' => $this->technician?->id,
                'name' => $this->technician?->name,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
