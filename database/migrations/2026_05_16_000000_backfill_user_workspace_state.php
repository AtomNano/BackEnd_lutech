<?php

use App\Models\User;
use App\Services\WorkspaceLifecycleService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $lifecycle = app(WorkspaceLifecycleService::class);

        User::query()
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($lifecycle) {
                foreach ($users as $user) {
                    $defaultWorkspace = $lifecycle->ensureUserHasDefaultWorkspace($user);

                    $user->refresh();
                    if ($user->active_workspace_id === null) {
                        $lifecycle->syncActiveWorkspaceToDefault($user, $defaultWorkspace);
                    }
                }
            });
    }

    public function down(): void
    {
        // Backfill only; intentionally irreversible.
    }
};
