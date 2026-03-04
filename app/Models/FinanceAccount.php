<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceAccount extends Model
{
    protected $fillable = [
        'workspace_id',
        'name',
        'type',
        'account_number',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
