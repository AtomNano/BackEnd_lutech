<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceCategory extends Model
{
    use \App\Traits\BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'name',
        'type',
    ];

    // Redundant workspace relation removed (provided by trait)

}
