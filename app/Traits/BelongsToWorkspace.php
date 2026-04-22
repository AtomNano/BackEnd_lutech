<?php

namespace App\Traits;

use App\Services\WorkspaceContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToWorkspace
{
    /**
     * Boot the trait to apply Global Scopes and Observers.
     */
    protected static function bootBelongsToWorkspace(): void
    {
        // 1. READ: Automatically filter ALL queries by the active workspace context
        static::addGlobalScope('workspace', function (Builder $builder) {
            $wsId = app(WorkspaceContext::class)->getWorkspaceId();
            if ($wsId) {
                // Prepend table name to avoid ambiguous column errors in joins
                $builder->where($builder->getQuery()->from . '.workspace_id', $wsId);
            }
        });

        // 2. WRITE: Automatically inject the workspace_id on record creation
        static::creating(function ($model) {
            $wsId = app(WorkspaceContext::class)->getWorkspaceId();
            if (empty($model->workspace_id) && $wsId) {
                $model->workspace_id = $wsId;
            }
        });
    }

    /**
     * Relationship to the Workspace.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Workspace::class);
    }
}
