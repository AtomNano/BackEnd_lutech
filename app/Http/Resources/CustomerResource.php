<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'address' => $this->address,
            'notes' => $this->notes,
            'points' => $this->points,
            'tier' => $this->tier,
            'total_spent' => (float) $this->total_spent,
            'service_count' => $this->service_count,
            'last_service_at' => $this->last_service_at?->toIso8601String(),
            'tickets_count' => $this->whenCounted('tickets'),
            'tickets' => $this->whenLoaded(
                'tickets',
                fn() =>
                $this->tickets->map(fn($t) => [
                    'id' => $t->id,
                    'subject' => $t->subject,
                    'status' => $t->status,
                    'jenis_device' => $t->jenis_device,
                    'merk_device' => $t->merk_device,
                    'biaya' => $t->biaya,
                    'created_at' => $t->created_at->toIso8601String(),
                ])
            ),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
