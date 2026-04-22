<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinanceRequest;
use App\Http\Resources\FinanceResource;
use App\Models\Finance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * GET /api/v1/finances
     * Daftar transaksi dengan filter opsional (type, bulan, tahun).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        // Global Scope automatically handles workspace isolation
        $query = Finance::query()
            ->when($request->type, fn($q, $v) => $q->where('type', $v))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->month, fn($q, $v) => $q->whereMonth('transaction_date', $v))
            ->when($request->year, fn($q, $v) => $q->whereYear('transaction_date', $v))
            ->latest('transaction_date');

        return FinanceResource::collection($query->paginate(20));
    }

    /**
     * POST /api/v1/finances
     */
    public function store(StoreFinanceRequest $request): FinanceResource
    {
        $finance = DB::transaction(function () use ($request) {
            $data = array_merge($request->validated(), [
                'user_id' => Auth::id(),
                'status' => $request->status ?? 'approved',
                'source' => $request->source ?? 'web',
            ]);

            // Workspace ID is automatically injected by BelongsToWorkspace trait
            return Finance::create($data); 
        });

        return new FinanceResource($finance);
    }

    /**
     * PUT /api/v1/finances/{finance}
     */
    public function update(StoreFinanceRequest $request, Finance $finance): FinanceResource
    {
        // Global scope ensures $finance belongs to the active workspace
        $finance->update($request->validated());
        $finance->refresh();

        return new FinanceResource($finance);
    }

    /**
     * DELETE /api/v1/finances/{finance}
     */
    public function destroy(Finance $finance): JsonResponse
    {
        $finance->delete();
        return response()->json(['message' => 'Transaksi dihapus.']);
    }

    /**
     * GET /api/v1/finances/summary
     * Rekap total income & expense untuk bulan/tahun tertentu.
     */
    public function summary(Request $request): JsonResponse
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $totals = Finance::query()
            ->where('status', 'approved')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $income = (float) ($totals['income'] ?? 0);
        $expense = (float) ($totals['expense'] ?? 0);

        return response()->json([
            'month' => $month,
            'year' => $year,
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ]);
    }
}
