<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
    return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
}

if (Auth::user()->role !== 'admin') {
    abort(403, 'Bạn không có quyền truy cập trang này!');
}
        return $next($request);
    }
}
