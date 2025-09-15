<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $users = User::query();

        if ($keyword) {
            $users->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%");
            });
        }

        $users = $users->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admins.user.index', compact('users', 'keyword'));
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admins.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,user',
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'phone', 'role', 'status']));

        return redirect()->route('admin.user.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    // public function orders($id)
    // {
    //     $user = User::findOrFail($id);
    //     $orders = Order::where('user_id', $id)->latest()->get();

    //     return view('admins.users.orders', compact('user', 'orders'));
    // }



}