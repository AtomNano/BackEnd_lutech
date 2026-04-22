<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{
    /**
     * GET /api/v1/finance-categories
     */
    public function index(): JsonResponse
    {
        return response()->json(FinanceCategory::all());
    }

    /**
     * POST /api/v1/finance-categories
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:income,expense',
        ]);

        $category = FinanceCategory::create($validated);

        return response()->json($category, 201);
    }

    /**
     * DELETE /api/v1/finance-categories/{category}
     */
    public function destroy(FinanceCategory $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted.']);
    }
}
