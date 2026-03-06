<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureN8nIpIsWhitelisted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil IP yang diizinkan dari .env, default 127.0.0.1 (localhost)
        $whitelistedIps = explode(',', env('N8N_WHITELISTED_IP', '127.0.0.1'));

        // Cek apakah IP request saat ini ada di dalam daftar whitelist
        // if (!in_array($request->ip(), $whitelistedIps)) {
        //     // Bisa menggunakan abort(403) atau kembalikan response JSON khusus
        //     return response()->json([
        //         'message' => 'Forbidden. Access restricted to whitelisted n8n servers only.',
        //         'ip_detected' => $request->ip()
        //     ], 403);
        // }

        return $next($request);
    }
}
