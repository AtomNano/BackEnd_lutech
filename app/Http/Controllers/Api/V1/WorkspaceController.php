<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    /**
     * GET /api/v1/workspaces
     * List semua workspace milik user yang sedang login.
     */
    public function index(): AnonymousResourceCollection
    {
        $workspaces = Workspace::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        return WorkspaceResource::collection($workspaces);
    }

    /**
     * POST /api/v1/workspaces
     * Buat workspace baru.
     */
    public function store(StoreWorkspaceRequest $request): WorkspaceResource
    {
        $isFirst = !Workspace::where('user_id', Auth::id())->exists();

        $workspace = Workspace::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type,
            'is_default' => $isFirst, // Otomatis default kalau ini yang pertama
        ]);

        return new WorkspaceResource($workspace);
    }

    /**
     * PATCH /api/v1/workspaces/{workspace}/default
     * Set workspace ini sebagai default.
     */
    public function setDefault(Workspace $workspace): JsonResponse
    {
        // Authorization: hanya pemilik
        if ($workspace->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $workspace->markAsDefault();

        return response()->json([
            'message' => 'Workspace default diperbarui.',
            'workspace' => new WorkspaceResource($workspace->fresh()),
        ]);
    }

    /**
     * DELETE /api/v1/workspaces/{workspace}
     * Hapus workspace (tidak bisa hapus default).
     */
    public function destroy(Workspace $workspace): JsonResponse
    {
        if ($workspace->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($workspace->is_default) {
            return response()->json([
                'message' => 'Tidak bisa menghapus workspace default. Ganti default ke workspace lain terlebih dahulu.',
            ], 422);
        }

        $workspace->delete();

        return response()->json(['message' => 'Workspace dihapus.']);
    }
}
