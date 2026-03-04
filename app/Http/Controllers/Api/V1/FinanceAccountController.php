<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinanceAccount;
use App\Models\Workspace;
use Illuminate\Http\Request;

class FinanceAccountController extends Controller
{
    /**
     * Tampilkan daftar dompet/rekening dari sebuah workspace.
     */
    public function index(Request $request, Workspace $workspace)
    {
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = $workspace->financeAccounts();

        if ($request->has('type')) {
            $query->where('type', $request->query('type'));
        }

        return response()->json($query->get());
    }

    /**
     * Simpan dompet/rekening baru.
     */
    public function store(Request $request, Workspace $workspace)
    {
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,ewallet,cash',
            'account_number' => 'nullable|string|max:255',
            'balance' => 'numeric',
        ]);

        $account = $workspace->financeAccounts()->create($validated);

        return response()->json([
            'message' => 'Akun berhasil ditambahkan.',
            'data' => $account,
        ], 201);
    }

    /**
     * Update nama, tipe, atau saldo awal akun.
     */
    public function update(Request $request, Workspace $workspace, FinanceAccount $financeAccount)
    {
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($financeAccount->workspace_id !== $workspace->id) {
            return response()->json(['message' => 'Akun tidak valid'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:bank,ewallet,cash',
            'balance' => 'sometimes|numeric',
        ]);

        $financeAccount->update($validated);

        return response()->json([
            'message' => 'Akun berhasil diperbarui.',
            'data' => $financeAccount->fresh(),
        ]);
    }


    public function destroy(Request $request, Workspace $workspace, FinanceAccount $financeAccount)
    {
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($financeAccount->workspace_id !== $workspace->id) {
            return response()->json(['message' => 'Akun tidak valid'], 404);
        }

        $financeAccount->delete();

        return response()->json([
            'message' => 'Akun berhasil dihapus.'
        ]);
    }
}
