<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;

class WorkspaceLifecycleService
{
    public function ensureUserHasDefaultWorkspace(User $user): Workspace
    {
        $workspaces = $user->workspaces()
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        if ($workspaces->isEmpty()) {
            return Workspace::create([
                'user_id' => $user->id,
                'name' => 'Business Workspace',
                'type' => 'business',
                'is_default' => true,
            ]);
        }

        $defaultWorkspace = $workspaces->firstWhere('is_default', true);
        if ($defaultWorkspace) {
            Workspace::where('user_id', $user->id)
                ->where('id', '!=', $defaultWorkspace->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);

            return $defaultWorkspace->fresh();
        }

        $fallbackWorkspace = $workspaces->first();
        $fallbackWorkspace->markAsDefault();

        return $fallbackWorkspace->fresh();
    }

    public function syncActiveWorkspaceToDefault(User $user, ?Workspace $preferred = null): ?Workspace
    {
        $preferred = $preferred && (int) $preferred->user_id === (int) $user->id
            ? $preferred->fresh()
            : null;

        if ($preferred && $preferred->is_default) {
            $targetWorkspace = $preferred;
        } else {
            $defaultWorkspace = $user->workspaces()
                ->where('is_default', true)
                ->orderBy('created_at')
                ->orderBy('id')
                ->first();

            $targetWorkspace = $defaultWorkspace;

            if (!$targetWorkspace && $preferred) {
                $preferred->markAsDefault();
                $targetWorkspace = $preferred->fresh();
            }

            if (!$targetWorkspace) {
                $targetWorkspace = $this->ensureUserHasDefaultWorkspace($user);
            }
        }

        if (!$targetWorkspace) {
            return null;
        }

        if ((int) $user->active_workspace_id !== (int) $targetWorkspace->id) {
            $user->forceFill([
                'active_workspace_id' => $targetWorkspace->id,
            ])->saveQuietly();
        }

        return $targetWorkspace;
    }
}
