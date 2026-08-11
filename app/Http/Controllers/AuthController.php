<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Log in and generate a Bearer Token for the User
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Check if the User exists and the password is correct
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không chính xác.'
            ], 401);
        }

        // Create token via Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Đăng nhập thành công',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role ? $user->role->name : null,
            ],
        ], 200);
    }

    /**
     * Log out and revoke the current Token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ], 200);
    }
}
