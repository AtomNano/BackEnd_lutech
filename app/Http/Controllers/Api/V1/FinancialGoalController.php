<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FinancialGoalResource;
use App\Models\FinancialGoal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialGoalController extends Controller
{
    /**
     * GET /api/v1/financial-goals
     */
    public function index()
    {
        $goals = FinancialGoal::query()
            ->orderBy('deadline')
            ->get();

        return FinancialGoalResource::collection($goals);
    }

    /**
     * POST /api/v1/financial-goals
     */
    public function store(Request $request): FinancialGoalResource
    {
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
            'user_id' => Auth::id(),
        ]));

        return new FinancialGoalResource($goal);
    }

    /**
     * PUT /api/v1/financial-goals/{goal}
     */
    public function update(Request $request, FinancialGoal $goal): FinancialGoalResource
    {
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
     * DELETE /api/v1/financial-goals/{goal}
     */
    public function destroy(FinancialGoal $goal): JsonResponse
    {
        $goal->delete();
        return response()->json(['message' => 'Goal deleted.']);
    }
}
