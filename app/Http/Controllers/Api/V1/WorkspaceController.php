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
use Illuminate\Support\Facades\Hash;

class WorkspaceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return WorkspaceResource::collection(
            Workspace::where('user_id', Auth::id())
                ->orderByDesc('is_default')
                ->orderBy('name')
                ->get()
        );
    }

    public function store(StoreWorkspaceRequest $request): WorkspaceResource
    {
        $isFirst = !Workspace::where('user_id', Auth::id())->exists();
        $workspace = Workspace::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type,
            'is_default' => $isFirst,
        ]);
        return new WorkspaceResource($workspace);
    }

    /**
     * PATCH /api/v1/workspaces/{workspace}
     * Rename workspace.
     */
    public function update(Request $request, Workspace $workspace): JsonResponse
    {
        if ($workspace->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $workspace->update(['name' => $request->name]);

        return response()->json([
            'message' => 'Workspace diperbarui.',
            'workspace' => new WorkspaceResource($workspace->fresh()),
        ]);
    }

    public function setDefault(Workspace $workspace): JsonResponse
    {
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
     * POST /api/v1/workspaces/{workspace}/verify-pin
     */
    public function verifyPin(Request $request, Workspace $workspace): JsonResponse
    {
        if ($workspace->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        if (!$workspace->pin_hash) {
            return response()->json(['message' => 'Workspace tidak diproteksi.', 'verified' => true]);
        }
        $request->validate(['pin' => 'required|string|min:4|max:8']);
        if (!Hash::check($request->pin, $workspace->pin_hash)) {
            return response()->json(['message' => 'PIN salah.', 'verified' => false], 401);
        }
        return response()->json(['verified' => true, 'message' => 'PIN benar.']);
    }

    /**
     * POST /api/v1/workspaces/{workspace}/set-pin
     */
    public function setPin(Request $request, Workspace $workspace): JsonResponse
    {
        if ($workspace->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $request->validate([
            'pin' => 'nullable|string|min:4|max:8',
            'current_pin' => 'nullable|string',
        ]);
        if ($workspace->pin_hash && $request->filled('pin')) {
            if (!$request->filled('current_pin') || !Hash::check($request->current_pin, $workspace->pin_hash)) {
                return response()->json(['message' => 'PIN saat ini salah.'], 401);
            }
        }
        $workspace->update([
            'pin_hash' => $request->filled('pin') ? Hash::make($request->pin) : null,
        ]);
        return response()->json([
            'message' => $request->filled('pin') ? 'PIN berhasil diatur.' : 'PIN dihapus.',
            'has_pin' => $workspace->fresh()->pin_hash !== null,
        ]);
    }

    public function destroy(Workspace $workspace): JsonResponse
    {
        if ($workspace->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        if ($workspace->is_default) {
            return response()->json(['message' => 'Tidak bisa menghapus workspace default.'], 422);
        }
        $workspace->delete();
        return response()->json(['message' => 'Workspace dihapus.']);
    }
}
