<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {

                $user = Auth::user();

            // Kiểm tra nếu tài khoản bị khóa
            if ($user->status == 0) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa.',
                ]);
            }
            
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác',
        ]);
    }
}

