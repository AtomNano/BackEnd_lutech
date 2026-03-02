<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // POST /login: Validasi kredensial, return Session Cookie
    public function login(Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Cegah Session Fixation
            return response()->json(['message' => 'Login sukses', 'user' => Auth::user()]);
        }
        return response()->json(['message' => 'Kredensial salah'], 401);
    }

    // POST /logout: Invalidate session
    public function logout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logout sukses']);
    }

    // GET /api/user: Return user login (Bisa ditaruh di routes langsung)
}
