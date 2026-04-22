<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinanceAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinanceAccountController extends Controller
{
    /**
     * GET /api/v1/finance-accounts
     */
    public function index(): JsonResponse
    {
        return response()->json(FinanceAccount::all());
    }

    /**
     * POST /api/v1/finance-accounts
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'balance' => 'required|numeric',
        ]);

        $account = FinanceAccount::create($validated);

        return response()->json($account, 201);
    }

    /**
     * PATCH /api/v1/finance-accounts/{financeAccount}
     */
    public function update(Request $request, FinanceAccount $financeAccount): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'balance' => 'sometimes|numeric',
        ]);

        $financeAccount->update($validated);

        return response()->json($financeAccount);
    }

    /**
     * DELETE /api/v1/finance-accounts/{financeAccount}
     */
    public function destroy(FinanceAccount $financeAccount): JsonResponse
    {
        $financeAccount->delete();
        return response()->json(['message' => 'Account deleted.']);
    }
}
