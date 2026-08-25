<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Hiển thị màn hình đăng nhập.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập Web bằng Session.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Đăng xuất Web.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}