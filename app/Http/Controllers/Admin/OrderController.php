<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = Order::with('orderDetails');

        // Search functionality
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', $keyword)
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date . ' 00:00:00');
        } elseif ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Status filter
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        // Payment method filter
        if ($request->filled('payment_filter')) {
            $query->where('payment_method', $request->payment_filter);
        }

        // Payment status filter
        if ($request->filled('payment_status_filter')) {
            $query->where('payment_status', $request->payment_status_filter);
        }

        // Price sorting
        if ($request->filled('price_order')) {
            if ($request->price_order == 'asc') {
                $query->orderBy('total', 'asc');
            } elseif ($request->price_order == 'desc') {
                $query->orderBy('total', 'desc');
            }
        } else {
            $query->latest();
        }

        $orders = $query->paginate(15);

        // Statistics for dashboard
        $stats = [
            'total_orders'      => Order::count(),
            'pending_orders'    => Order::where('status', Order::STATUS_PENDING)->count(),
            'confirmed_orders'  => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'shipping_orders'   => Order::where('status', Order::STATUS_SHIPPING)->count(),
            'delivered_orders'  => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled_orders'  => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'total_revenue'     => Order::where('status', Order::STATUS_DELIVERED)->sum('pay_amount'),
            'today_orders'      => Order::whereDate('created_at', today())->count(),
        ];

        return view('admins.orders.index', compact('orders', 'stats'));
    }

    public function show($orderId)
    {
        $order = Order::with([
            'user',
            'orderDetails.productVariant.product',
            'orderDetails.productVariant.color',
            'orderDetails.productVariant.size'
        ])->findOrFail($orderId);

        return view('admins.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2,3,4,5,6',
            'note' => 'nullable|string|max:500'
        ]);

        try {
            $order = Order::findOrFail($orderId);
            $oldStatus = $order->status;
            $newStatus = (int) $request->status;

            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                return redirect()->back()->with('error', 'Không thể chuyển từ trạng thái hiện tại sang trạng thái đã chọn.');
            }

            $order->status = $newStatus;
            if ($request->filled('note')) {
                $order->admin_note = $request->note;
            }
            $order->save();

            if ($order->ghn_order_code) {
                $this->updateGHNStatus($order, $newStatus);
            }

            Log::info("Order #{$order->id} status changed from {$oldStatus} to {$newStatus} by admin " . auth()->id());

            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            Log::error("Error updating order status: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, $orderId)
    {
        $request->validate([
            'payment_status' => 'required|integer|in:0,1,2,3',
            'payment_note' => 'nullable|string|max:500'
        ]);

        try {
            $order = Order::findOrFail($orderId);
            $oldPaymentStatus = $order->payment_status;
            $newPaymentStatus = (int) $request->payment_status;

            if (!$this->isValidPaymentStatusTransition($oldPaymentStatus, $newPaymentStatus)) {
                return redirect()->back()->with('error', 'Không thể chuyển từ trạng thái thanh toán hiện tại sang trạng thái đã chọn.');
            }

            $order->payment_status = $newPaymentStatus;
            if ($request->filled('payment_note')) {
                $order->payment_note = $request->payment_note;
            }
            $order->save();

            Log::info("Order #{$order->id} payment status changed from {$oldPaymentStatus} to {$newPaymentStatus} by admin " . auth()->id());

            return redirect()->back()->with('success', 'Cập nhật trạng thái thanh toán thành công!');
        } catch (\Exception $e) {
            Log::error("Error updating payment status: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|integer|in:0,1,2,3,4,5,6'
        ]);

        try {
            $updatedCount = 0;
            foreach ($request->order_ids as $orderId) {
                $order = Order::find($orderId);
                if ($order && $this->isValidStatusTransition($order->status, $request->status)) {
                    $order->status = $request->status;
                    $order->save();
                    $updatedCount++;

                    if ($order->ghn_order_code) {
                        $this->updateGHNStatus($order, $request->status);
                    }
                }
            }

            return redirect()->back()->with('success', "Đã cập nhật {$updatedCount} đơn hàng thành công!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        return response()->json(['message' => 'Export functionality coming soon']);
    }

    public function destroy($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CANCELLED])) {
                return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng đang chờ xử lý hoặc đã hủy.');
            }

            $order->delete();
            Log::info("Order #{$order->id} deleted by admin " . auth()->id());

            return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
        } catch (\Exception $e) {
            Log::error("Error deleting order: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            Order::STATUS_PENDING => [Order::STATUS_CONFIRMED, Order::STATUS_CANCELLED],
            Order::STATUS_CONFIRMED => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_SHIPPING, Order::STATUS_CANCELLED],
            Order::STATUS_SHIPPING => [Order::STATUS_DELIVERED, Order::STATUS_RETURNED],
            Order::STATUS_DELIVERED => [Order::STATUS_RETURNED],
            Order::STATUS_CANCELLED => [],
            Order::STATUS_RETURNED => [],
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []) || $oldStatus === $newStatus;
    }

    private function isValidPaymentStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            Order::PAYMENT_STATUS_PENDING => [
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_FAILED
            ],
            Order::PAYMENT_STATUS_PAID => [
                Order::PAYMENT_STATUS_REFUNDED
            ],
            Order::PAYMENT_STATUS_FAILED => [
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_PENDING
            ],
            Order::PAYMENT_STATUS_REFUNDED => [],
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []) || $oldStatus === $newStatus;
    }

    private function updateGHNStatus($order, $status)
    {
        try {
            $token = config('services.ghn.token');
            $endpoint = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/switch-status';

            $ghnStatus = null;
            switch ($status) {
                case Order::STATUS_CONFIRMED:
                    $ghnStatus = 'ready_to_pick';
                    break;
                case Order::STATUS_CANCELLED:
                    $ghnStatus = 'cancel';
                    break;
            }

            if ($ghnStatus) {
                $response = Http::withHeaders([
                    'Token' => $token,
                    'Content-Type' => 'application/json'
                ])->timeout(30)->post($endpoint, [
                    'order_codes' => [$order->ghn_order_code],
                    'status' => $ghnStatus
                ]);

                if ($response->failed()) {
                    Log::warning("GHN API error for order {$order->id}: " . $response->body());
                }
            }
        } catch (\Exception $e) {
            Log::error("Error updating GHN status: " . $e->getMessage());
        }
    }
}
