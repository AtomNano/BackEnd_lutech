<?php

namespace App\Services;

class WorkspaceContext
{
    protected ?string $workspaceId = null;

    /**
     * Set the active workspace ID.
     * Useful for background jobs or manual overrides.
     */
    public function setWorkspaceId(string $id): void
    {
        $this->workspaceId = $id;
    }

    /**
     * Get the active workspace ID.
     * Prioritizes manually set context, fallback to authenticated user preference.
     */
    public function getWorkspaceId(): ?string
    {
        return $this->workspaceId ?? auth()->user()?->active_workspace_id;
    }
}
