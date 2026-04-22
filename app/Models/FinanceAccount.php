<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceAccount extends Model
{
    use \App\Traits\BelongsToWorkspace;

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

    // Redundant workspace relation removed (provided by trait)


    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
