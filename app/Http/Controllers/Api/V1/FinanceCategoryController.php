<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\Workspace;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori dari sebuah workspace.
     */
    public function index(Request $request, Workspace $workspace)
    {
        // Pastikan workspace milik user
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = $workspace->financeCategories();

        if ($request->has('type')) {
            $query->where('type', $request->query('type'));
        }

        return response()->json($query->get());
    }

    /**
     * Simpan kategori baru untuk workspace.
     */
    public function store(Request $request, Workspace $workspace)
    {
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category = $workspace->financeCategories()->create($validated);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $category,
        ], 201);
    }

    /**
     * Hapus kategori dari workspace.
     */
    public function destroy(Request $request, Workspace $workspace, FinanceCategory $category)
    {
        if ($workspace->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Pastikan kategori milik workspace yang benar
        if ($category->workspace_id !== $workspace->id) {
            return response()->json(['message' => 'Kategori tidak valid'], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus.'
        ]);
    }
}
