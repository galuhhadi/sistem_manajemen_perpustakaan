<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member'
        ]);
        return response()->json(['message' => 'Registrasi Sukses'], 201);
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Login Gagal'], 401);
        }
        return response()->json(['token' => $token]);
    }

    /**
     * Get the authenticated User.
     * Ditambahkan untuk memperbaiki error "Method me does not exist"
     */
    public function me()
    {
        // auth('api')->user() akan mengambil data user berdasarkan token di Header
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
