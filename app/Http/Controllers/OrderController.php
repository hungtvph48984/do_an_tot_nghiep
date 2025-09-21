<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Danh sách đơn hàng của user, kèm cả lọc
    public function index(Request $request)
    {
        $query = Order::query()
            ->where('user_id', auth()->id())
            ->with('orderDetails.product');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                ->orWhereHas('orderDetails.product', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('pay_amount', [$request->min_price, $request->max_price]);
        } elseif ($request->filled('min_price')) {
            $query->where('pay_amount', '>=', $request->min_price);
        } elseif ($request->filled('max_price')) {
            $query->where('pay_amount', '<=', $request->max_price);
        }

        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate(5)
                        ->appends($request->query());

        return view('clients.order.index', compact('orders'));
    }


    // Chi tiết 1 đơn hàng
    public function show($id)
    {
        $order = Order::with([
                'orderDetails.productVariant.product',
                'orderDetails.productVariant.size',
                'orderDetails.productVariant.color'
            ])->findOrFail($id);

        // Chỉ cho phép user sở hữu đơn hàng hoặc admin xem
        if (Auth::id() !== $order->user_id && !(Auth::user() && Auth::user()->is_admin)) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('clients.order.show', compact('order'));
    }

    // Danh sách đơn hàng của user (dành cho trang profile)
    public function profileOrders(Request $request)
    {
        $query = Order::query()->where('user_id', auth()->id());

        // Tìm kiếm theo mã đơn hàng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%$search%");
        }

        // Lọc theo ngày
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = $request->date_from . ' 00:00:00';
            $to   = $request->date_to . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        // Lọc theo trạng thái đơn hàng (nếu muốn)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán (nếu muốn)
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Sắp xếp theo tổng tiền (total) hoặc số tiền đã thanh toán (pay_amount)
        if ($request->sort_price == 'asc') {
            $query->orderBy('pay_amount', 'asc');
        } elseif ($request->sort_price == 'desc') {
            $query->orderBy('pay_amount', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(5)->appends($request->query());

        return view('clients.profile.profile', compact('orders'));
    }

    public function confirmReceived($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status == Order::STATUS_SHIPPING) {
            $order->update([
                'status' => Order::STATUS_DELIVERED,
                'delivered_at' => now(), // bạn nên thêm cột này trong db
            ]);
        }

        return back()->with('success', 'Xác nhận đã nhận hàng thành công!');
    }

    public function requestReturn($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status == Order::STATUS_DELIVERED && $order->updated_at->diffInDays(now()) <= 7) {
            $order->update(['status' => Order::STATUS_RETURNED]);
        }

        return back()->with('success', 'Yêu cầu trả hàng/hoàn tiền đã được gửi.');
    }



}
