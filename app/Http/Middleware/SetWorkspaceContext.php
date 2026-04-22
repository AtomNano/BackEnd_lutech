<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\WorkspaceContext;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class SetWorkspaceContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspaceContext = app(WorkspaceContext::class);
        $user = auth()->user();

        // 1. Skip context setting for workspace management routes
        // These routes must be accessible even if the current workspace session is invalid
        if ($request->is('api/v1/workspaces*') || $request->is('api/v1/user/active-workspace')) {
            return $next($request);
        }

        // 2. Prioritize Device-Specific Header (X-Workspace-Id)
        $targetWsId = $request->header('X-Workspace-Id');

        // 3. Fallback to Authenticated User's Global Preference (Backend default)
        if (!$targetWsId && $user) {
            $targetWsId = $user->active_workspace_id;
        }

        // 4. Security Check & Context Binding
        if ($targetWsId && $user) {
            $hasAccess = DB::table('workspaces')
                ->where('id', $targetWsId)
                ->where('user_id', $user->id)
                ->exists();

            if ($hasAccess) {
                $workspaceContext->setWorkspaceId($targetWsId);
            }
            // If No Access: We fail gracefully. 
            // The Global Scope on models will simply return no results,
            // which is safer and prevents 403 deadlocks during workspace switching.
        }

        return $next($request);
    }

}
