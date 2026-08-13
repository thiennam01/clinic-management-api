<?php

namespace App\Http\Controllers;

use App\Constants\AuthConstant;
use App\Http\Requests\Auth\LoginRequest; // Hoặc dùng Request trực tiếp nếu chưa tạo FormRequest
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Authenticate user and generate a Bearer Token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $result = $this->authService->login($credentials);

        return $this->successResponse(
            $result,
            AuthConstant::MSG_LOGIN_SUCCESS
        );
    }

    /**
     * Revoke the current user's token (Logout).
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->successResponse(
            null,
            AuthConstant::MSG_LOGOUT_SUCCESS
        );
    }
}