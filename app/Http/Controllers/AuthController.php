<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    private const MSG_INVALID_CREDENTIALS = 'Email hoặc mật khẩu không chính xác.';
    private const MSG_LOGIN_SUCCESS = 'Đăng nhập thành công';
    private const MSG_LOGOUT_SUCCESS = 'Đăng xuất thành công';

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
                'message' => self::MSG_INVALID_CREDENTIALS
            ], 401);
        }

        // Create token via Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => self::MSG_LOGIN_SUCCESS,
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
            'message' => self::MSG_LOGOUT_SUCCESS
        ], 200);
    }
}
