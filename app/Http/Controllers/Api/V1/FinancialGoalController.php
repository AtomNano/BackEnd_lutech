<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FinancialGoalResource;
use App\Models\FinancialGoal;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialGoalController extends Controller
{
    private function authorizeWorkspace(Workspace $workspace): void
    {
        if ($workspace->user_id !== Auth::id()) {
            abort(403, 'Forbidden');
        }
    }

    /**
     * GET /api/v1/workspaces/{workspace}/financial-goals
     */
    public function index(Workspace $workspace)
    {
        $this->authorizeWorkspace($workspace);

        $goals = FinancialGoal::where('workspace_id', $workspace->id)
            ->orderBy('deadline')
            ->get();

        return FinancialGoalResource::collection($goals);
    }

    /**
     * POST /api/v1/workspaces/{workspace}/financial-goals
     */
    public function store(Request $request, Workspace $workspace): FinancialGoalResource
    {
        $this->authorizeWorkspace($workspace);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'icon' => 'nullable|string|max:50',
            'target_amount' => 'required|numeric|min:1',
            'current_amount' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:50',
            'deadline' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $goal = FinancialGoal::create(array_merge($validated, [
            'workspace_id' => $workspace->id,
            'user_id' => Auth::id(),
        ]));

        return new FinancialGoalResource($goal);
    }

    /**
     * PUT /api/v1/workspaces/{workspace}/financial-goals/{goal}
     */
    public function update(Request $request, Workspace $workspace, FinancialGoal $goal): FinancialGoalResource
    {
        $this->authorizeWorkspace($workspace);

        if ($goal->workspace_id !== $workspace->id) {
            abort(403, 'Forbidden');
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:150',
            'icon' => 'nullable|string|max:50',
            'target_amount' => 'sometimes|required|numeric|min:1',
            'current_amount' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:50',
            'deadline' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $goal->update($validated);
        $goal->refresh();

        return new FinancialGoalResource($goal);
    }

    /**
     * DELETE /api/v1/workspaces/{workspace}/financial-goals/{goal}
     */
    public function destroy(Workspace $workspace, FinancialGoal $goal): JsonResponse
    {
        $this->authorizeWorkspace($workspace);

        if ($goal->workspace_id !== $workspace->id) {
            abort(403, 'Forbidden');
        }

        $goal->delete();

        return response()->json(['message' => 'Goal deleted.']);
    }
}
