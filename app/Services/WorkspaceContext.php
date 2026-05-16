<?php

namespace App\Services;

use App\Models\User;

class WorkspaceContext
{
    protected ?int $workspaceId = null;
    protected ?int $resolvedWorkspaceId = null;

    /**
     * Set the active workspace ID.
     * Useful for background jobs or manual overrides.
     */
    public function setWorkspaceId(int|string $id): void
    {
        $this->workspaceId = (int) $id;
        $this->resolvedWorkspaceId = (int) $id;
    }

    /**
     * Get the active workspace ID.
     * Prioritizes manual overrides, then the authenticated user's active workspace,
     * then falls back to the user's default workspace without mutating persistence.
     */
    public function getWorkspaceId(): ?int
    {
        if ($this->workspaceId !== null) {
            return $this->workspaceId;
        }

        if ($this->resolvedWorkspaceId !== null) {
            return $this->resolvedWorkspaceId;
        }

        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        if ($user->active_workspace_id) {
            return $this->resolvedWorkspaceId = (int) $user->active_workspace_id;
        }

        $defaultWorkspace = $user->relationLoaded('workspaces')
            ? $user->workspaces->firstWhere('is_default', true)
            : $user->workspaces()->where('is_default', true)->first();

        if (!$defaultWorkspace) {
            return null;
        }

        return $this->resolvedWorkspaceId = (int) $defaultWorkspace->id;
    }
}
