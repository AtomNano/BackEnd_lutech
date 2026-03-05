<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController
{
    /**
     * List all staff users (non-customers).
     * Only super_admin can access.
     */
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->role === 'super_admin', 403, 'Akses ditolak.');

        $users = User::select('id', 'name', 'email', 'role', 'avatar', 'created_at')
            ->whereIn('role', ['super_admin', 'admin', 'technician'])
            ->get();

        return response()->json($users);
    }

    /**
     * Create a new staff user.
     * Only super_admin can access.
     */
    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->role === 'super_admin', 403, 'Akses ditolak.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role' => 'required|in:admin,technician',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'User berhasil dibuat.',
            'user' => $user->only('id', 'name', 'email', 'role'),
        ], 201);
    }

    /**
     * Update a staff user's role or reset password.
     * Only super_admin can access.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        abort_unless($request->user()->role === 'super_admin', 403, 'Akses ditolak.');
        abort_if($user->id === $request->user()->id, 422, 'Tidak bisa mengubah diri sendiri dari sini.');

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:admin,technician',
            'password' => ['sometimes', Password::min(8)],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User berhasil diperbarui.',
            'user' => $user->only('id', 'name', 'email', 'role'),
        ]);
    }

    /**
     * Delete a staff user.
     * Only super_admin can access.
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        abort_unless($request->user()->role === 'super_admin', 403, 'Akses ditolak.');
        abort_if($user->id === $request->user()->id, 422, 'Tidak bisa menghapus diri sendiri.');

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
