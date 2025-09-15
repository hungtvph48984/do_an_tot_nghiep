<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
public function store(Request $request)
{
    // Validate dữ liệu
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'address'        => 'required|string|max:255',
        'phone'          => 'required|string|max:20',
        'email'          => 'required|email|max:255',
        'note'           => 'nullable|string|max:500',
        'payment_method' => 'required|in:0,1', // 0 = COD, 1 = MOMO
        'coupon_code'    => 'nullable|string|max:50',
    ]);

    // ✅ Giữ nguyên tên biến cũ
    $cartItems = session('cart', []);

    if (empty($cartItems)) {
        return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    // Tính tổng tiền
    $total = collect($cartItems)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    $sale_price = 0;
    $pay_amount = $total;

    // Xử lý mã giảm giá (nếu có)
    if (!empty($validated['coupon_code'])) {
        $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])
            ->where('is_active', 1)
            ->first();

        if ($coupon) {
            if ($coupon->type == 0) {
                $sale_price = $total * ($coupon->discount / 100);
            } else {
                $sale_price = $coupon->discount;
            }
            $pay_amount = max($total - $sale_price, 0);
        }
    }

    // ✅ Tạo Order
    $order = \App\Models\Order::create([
        'user_id'        => Auth::id(),
        'address'        => $validated['address'],
        'phone'          => $validated['phone'],
        'email'          => $validated['email'],
        'status'         => 0,
        'payment_method' => $validated['payment_method'],
        'total'          => $total,
        'vorcher_code'   => $validated['coupon_code'] ?? null,
        'sale_price'     => $sale_price,
        'pay_amount'     => $pay_amount,
    ]);

    // ✅ Lưu chi tiết đơn hàng
    foreach ($cartItems as $variantId => $item) {
        $order->orderDetails()->create([
            'product_variant_id' => $variantId,   // Giữ nguyên key variantId trong giỏ
            'price'              => $item['price'],
            'quantity'           => $item['quantity'],
        ]);
    }

    // ✅ Xóa giỏ hàng
    session()->forget('cart');

    return redirect()->route('home.index')->with('success', 'Đặt hàng thành công!');
}

}
