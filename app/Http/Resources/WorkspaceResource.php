<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_default' => $this->is_default,
            'has_pin' => !is_null($this->pin_hash),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
