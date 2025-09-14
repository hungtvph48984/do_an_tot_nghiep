<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
<<<<<<< HEAD
        return view('client.register');
=======
        return view('clients.register');
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
<<<<<<< HEAD
            'role'     => 'user', 
=======
            'role'     => 'user',
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
        ]);

        Auth::login($user); // đăng nhập luôn sau khi đăng ký

<<<<<<< HEAD
        return redirect('/client')->with('success', 'Đăng ký thành công!');
=======
        return redirect('/')->with('success', 'Đăng ký thành công!');
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    }
}
