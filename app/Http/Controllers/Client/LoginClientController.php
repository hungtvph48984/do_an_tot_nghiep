<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginClientController extends Controller
{
    public function showLoginForm()
    {
        return view('clients.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // ✅ Chỉ cho đăng nhập nếu là client
            if ($user->role !== 'user') {
                Auth::logout();
                return back()->with('error', 'Tài khoản không thuộc quyền truy cập của khách hàng.');
            }

            // ✅ Kiểm tra nếu tài khoản bị khóa (status = 0)
            if ($user->status == 0) {
                Auth::logout();
                return back()->with('error', 'Tài khoản của bạn đã bị khóa.');
            }

            $request->session()->regenerate();
            return redirect()->intended('/')-> with('success', 'Đăng nhập thành công!');
        }

        return back()->with('error', 'Sai thông tin đăng nhập!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login/client');
    }
}

