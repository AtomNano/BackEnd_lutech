<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Google\Client as GoogleClient;

class AuthController extends Controller
{
    /**
     * POST /api/login — Standard email/password login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean', // Optional flag for session persistence
        ]);

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json(['message' => __('auth.failed')], 401);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->load('activeWorkspace');
        
        // Handle Session Persistence via Sanctum TTL
        $expiration = ($request->boolean('remember')) ? now()->addDays(30) : now()->addHours(2);
        $token = $user->createToken('auth-token', ['*'], $expiration)->plainTextToken;

        return response()->json([
            'message' => __('auth.login_success'),
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'active_workspace_id' => $user->active_workspace_id,
                'active_workspace' => $user->activeWorkspace,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/google — Secure Google ID Token Verification & JIT Provisioning
     */
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'remember' => 'boolean'
        ]);

        $client = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->id_token);

        if (!$payload) {
            return response()->json(['message' => __('auth.google_invalid')], 422);
        }

        // Security Check 1: Verified Email
        if (!$payload['email_verified']) {
            return response()->json(['message' => __('auth.google_unverified')], 422);
        }

        // Security Check 2: Domain Restriction (HD = Hosted Domain)
        $authorizedDomain = env('GOOGLE_AUTHORIZED_DOMAIN', 'lutech.com');
        if (isset($payload['hd']) && $payload['hd'] !== $authorizedDomain) {
             return response()->json(['message' => __('auth.google_domain_restricted', ['domain' => "@$authorizedDomain"])], 403);
        }

        $user = User::where('email', $payload['email'])->first();

        try {
            if (!$user) {
                // JIT Provisioning with Atomicity
                $user = DB::transaction(function () use ($payload) {
                    return User::create([
                        'name' => $payload['name'],
                        'email' => $payload['email'],
                        'google_id' => $payload['sub'],
                        'avatar' => $payload['picture'],
                        'password' => Hash::make(str()->random(32)),
                        'role' => 'technician', // Default Access Level
                    ]);
                });
            } else {
                // Link account if Google ID matches or if we trust the verified email
                $user->update([
                    'google_id' => $payload['sub'],
                    'avatar' => $user->avatar ?? $payload['picture']
                ]);
            }

            $user->load('activeWorkspace');

            // Handle Session Persistence via Sanctum TTL
            $expiration = ($request->boolean('remember')) ? now()->addDays(30) : now()->addHours(2);
            $token = $user->createToken('auth-token', ['*'], $expiration)->plainTextToken;

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                    'active_workspace_id' => $user->active_workspace_id,
                    'active_workspace' => $user->activeWorkspace,
                ],
                'token' => $token
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => __('auth.google_sync_failed')], 500);
        }
    }

    /**
     * POST /api/logout — Revoke current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => __('auth.logout_success')]);
    }
}
