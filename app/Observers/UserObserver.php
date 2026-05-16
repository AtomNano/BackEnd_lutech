<?php

namespace App\Observers;

use App\Models\User;
use App\Services\WorkspaceLifecycleService;

class UserObserver
{
    public function created(User $user): void
    {
        app(WorkspaceLifecycleService::class)->ensureUserHasDefaultWorkspace($user);
    }
}
