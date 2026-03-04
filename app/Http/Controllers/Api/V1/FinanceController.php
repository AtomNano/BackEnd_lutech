<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinanceRequest;
use App\Http\Resources\FinanceResource;
use App\Models\Finance;
use App\Models\FinanceAccount;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Authorization helper: pastikan workspace milik user yang login.
     */
    private function authorizeWorkspace(Workspace $workspace): void
    {
        if ($workspace->user_id !== Auth::id()) {
            abort(403, 'Forbidden');
        }
    }

    /**
     * Helper: ubah saldo akun sebesar $delta (positif = tambah, negatif = kurang).
     */
    private function adjustAccountBalance(int $accountId, float $delta): void
    {
        FinanceAccount::where('id', $accountId)->increment('balance', $delta);
    }

    /**
     * Helper: hitung delta perubahan saldo berdasarkan tipe transaksi.
     */
    private function balanceDelta(string $type, float $amount): float
    {
        return $type === 'income' ? $amount : -$amount;
    }

    /**
     * GET /api/v1/workspaces/{workspace}/finances
     * Daftar transaksi dengan filter opsional (type, bulan, tahun).
     */
    public function index(Request $request, Workspace $workspace): AnonymousResourceCollection
    {
        $this->authorizeWorkspace($workspace);

        $query = Finance::forWorkspace($workspace->id)
            ->when($request->type, fn($q, $v) => $q->where('type', $v))
            ->when($request->month, fn($q, $v) => $q->whereMonth('transaction_date', $v))
            ->when($request->year, fn($q, $v) => $q->whereYear('transaction_date', $v))
            ->latest('transaction_date');

        return FinanceResource::collection($query->paginate(20));
    }

    /**
     * POST /api/v1/workspaces/{workspace}/finances
     * Tambah transaksi baru dan update saldo akun jika dipilih.
     */
    public function store(StoreFinanceRequest $request, Workspace $workspace): FinanceResource
    {
        $this->authorizeWorkspace($workspace);

        $finance = DB::transaction(function () use ($request, $workspace) {
            $data = array_merge($request->validated(), [
                'workspace_id' => $workspace->id,
                'user_id' => Auth::id(),
            ]);

            return Finance::create($data);
        });

        return new FinanceResource($finance);
    }

    /**
     * PUT /api/v1/workspaces/{workspace}/finances/{finance}
     * Edit transaksi dan rekonsiliasi saldo akun.
     */
    public function update(StoreFinanceRequest $request, Workspace $workspace, Finance $finance): FinanceResource
    {
        $this->authorizeWorkspace($workspace);

        if ($finance->workspace_id !== $workspace->id) {
            abort(403, 'Forbidden');
        }

        $finance = DB::transaction(function () use ($request, $finance) {
            $finance->update($request->validated());
            $finance->refresh();

            return $finance;
        });

        return new FinanceResource($finance->fresh());
    }

    /**
     * DELETE /api/v1/workspaces/{workspace}/finances/{finance}
     * Soft delete dan balik efek saldo pada akun.
     */
    public function destroy(Workspace $workspace, Finance $finance): JsonResponse
    {
        $this->authorizeWorkspace($workspace);

        if ($finance->workspace_id !== $workspace->id) {
            abort(403, 'Forbidden');
        }

        DB::transaction(function () use ($finance) {
            $finance->delete();
        });

        return response()->json(['message' => 'Transaksi dihapus.']);
    }

    /**
     * GET /api/v1/workspaces/{workspace}/finances/summary
     * Rekap total income & expense untuk bulan/tahun tertentu.
     */
    public function summary(Request $request, Workspace $workspace): JsonResponse
    {
        $this->authorizeWorkspace($workspace);

        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $totals = Finance::forWorkspace($workspace->id)
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

