<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialGoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workspace_id' => $this->workspace_id,
            'title' => $this->title,
            'icon' => $this->icon,
            'target_amount' => (float) $this->target_amount,
            'current_amount' => (float) $this->current_amount,
            'color' => $this->color,
            'deadline' => $this->deadline?->toDateString(),
            'notes' => $this->notes,
            'progress' => round($this->progress, 1),
            'remaining' => (float) $this->remaining,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
