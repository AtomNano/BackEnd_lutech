<?php

namespace App\Observers;

use App\Models\Workspace;
use App\Services\WorkspaceLifecycleService;

class WorkspaceObserver
{
    public function created(Workspace $workspace): void
    {
        $user = $workspace->user()->first();
        if (!$user) {
            return;
        }

        $isFirstWorkspace = Workspace::where('user_id', $user->id)->count() === 1;
        if ($isFirstWorkspace || $workspace->is_default) {
            app(WorkspaceLifecycleService::class)->syncActiveWorkspaceToDefault($user, $workspace);
        }
    }

    public function updated(Workspace $workspace): void
    {
        if (!$workspace->wasChanged('is_default') || !$workspace->is_default) {
            return;
        }

        $user = $workspace->user()->first();
        if (!$user) {
            return;
        }

        app(WorkspaceLifecycleService::class)->syncActiveWorkspaceToDefault($user, $workspace);
    }
}
