<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Hiển thị trang cá nhân
public function show(Request $request)
{
    $query = Auth::user()->orders();

    // Tìm kiếm theo mã đơn hàng
    if ($request->filled('search')) {
        $query->where('id', 'like', "%{$request->search}%");
    }

    // Lọc theo ngày
    if ($request->filled('date_from') && $request->filled('date_to')) {
        $from = $request->date_from . ' 00:00:00';
        $to   = $request->date_to . ' 23:59:59';
        $query->whereBetween('created_at', [$from, $to]);
    }

    // Sắp xếp theo giá
    if ($request->sort_price == 'asc') {
        $query->orderBy('total', 'asc');
    } elseif ($request->sort_price == 'desc') {
        $query->orderBy('total', 'desc');
    }else {
    // Mặc định: ngày tạo mới nhất
    $query->orderBy('created_at', 'desc');
    }

    // sắp xếp theo ngày tạo mới nhất
    $orders = $query->paginate(5)->appends($request->query());

    return view('clients.profile.profile', compact('orders'));
}


    // Cập nhật thông tin cá nhân
    public function update(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Đổi mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'          => 'required',
            'new_password'              => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}

