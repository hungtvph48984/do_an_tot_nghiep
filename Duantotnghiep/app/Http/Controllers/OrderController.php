<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('clients.order.index', compact('orders'));
    }
    public function show($id)
    {
        // Lấy order theo id + load chi tiết
        $order = Order::with(['orderDetails.productVariant.product', 'orderDetails.productVariant.size', 'orderDetails.productVariant.color'])
            ->findOrFail($id);

        // Đảm bảo chỉ cho phép chủ sở hữu đơn hàng hoặc admin xem
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        return view('clients.order.show', compact('order'));
    }
}
