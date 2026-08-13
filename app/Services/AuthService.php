<?php

namespace App\Services;

use App\Constants\AuthConstant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Handle user login and generate Sanctum token.
     */
    public function login(array $credentials): array
    {
        $user = User::with('role')->where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [AuthConstant::MSG_INVALID_CREDENTIALS],
            ]);
        }

        // Generate Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role ? $user->role->name : null,
            ],
        ];
    }

    /**
     * Handle user logout and revoke current token.
     */
    public function logout($user): bool
    {
        if ($user && $user->currentAccessToken()) {
            return $user->currentAccessToken()->delete();
        }

        return false;
    }
}