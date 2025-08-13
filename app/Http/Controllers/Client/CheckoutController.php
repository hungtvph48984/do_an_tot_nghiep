<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\Order;
use App\Models\Client\Coupon; // Model coupon
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            'note'            => 'nullable|string|max:500',
            'payment_method'  => 'required|in:0,1', // 0 = COD, 1 = MOMO
            'coupon_code'     => 'nullable|string|max:50',
        ]);

        // Giả sử tổng tiền từ giỏ hàng (TODO: thay bằng tính từ session/cart)
        $total = 349000;

        $sale_price = 0;
        $pay_amount = $total;

        // Nếu có nhập mã giảm giá
        if (!empty($validated['coupon_code'])) {
            $coupon = Coupon::where('code', $validated['coupon_code'])
                            ->where('is_active', 1)
                            ->first();

            if ($coupon) {
                // Giảm giá theo phần trăm hoặc số tiền cố định
                if ($coupon->type == 0) {
                    $sale_price = $total * ($coupon->discount / 100);
                } else {
                    $sale_price = $coupon->discount;
                }

                $pay_amount = max($total - $sale_price, 0);
            }
        }

        // Tạo đơn hàng
        Order::create([
            'user_id'         => Auth::id(),
            'name'            => $validated['name'],
            'address'         => $validated['address'],
            'phone'           => $validated['phone'],
            'email'           => $validated['email'],
            'note'            => $validated['note'] ?? null,
            'status'          => 0,
            'payment_method'  => $validated['payment_method'],
            'total'           => $total,
            'vorcher_code'    => $validated['coupon_code'] ?? null,
            'sale_price'      => $sale_price,
            'pay_amount'      => $pay_amount,
        ]);

        return redirect()->route('client.index')->with('success', 'Đặt hàng thành công!');
    }
}
