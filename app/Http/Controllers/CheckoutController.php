<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Voucher;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // 1) Validate
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'note'           => 'nullable|string|max:500',
            'payment_method' => 'required|in:0,1', // 0 = COD, 1 = MOMO
            'coupon_code'    => 'nullable|string|max:50',
        ]);

        // 2) Lấy giỏ hàng
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // 3) Tính tổng
        $total = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);

        // 4) Biến trạng thái
        $sale_price  = 0.0;
        $pay_amount  = $total;
        $voucherUsed = null;
        $voucher     = null;
        $userId      = Auth::id();

        try {
            DB::transaction(function () use (
                $validated, $cartItems, $total, $userId,
                &$sale_price, &$pay_amount, &$voucherUsed, &$voucher
            ) {
                // 5) Áp dụng voucher (nếu có)
                if (!empty($validated['coupon_code'])) {
                    // Khóa hàng để tránh race
                    $voucher = Voucher::where('code', $validated['coupon_code'])
                        ->lockForUpdate()
                        ->first();

                    if ($voucher && $voucher->isValidForOrder($total)) {
                        // Check user đã dùng chưa (khóa hàng để tránh race)
                        $alreadyUsed = DB::table('voucher_usages')
                            ->where('voucher_id', $voucher->id)
                            ->where('user_id', $userId ?? 0)
                            ->lockForUpdate()
                            ->exists();

                        if (!$alreadyUsed) {
                            // Tính giảm
                            $sale_price  = $voucher->calculateDiscount($total);
                            $pay_amount  = max(0, $total - $sale_price);
                            $voucherUsed = $voucher->code;

                            // Trừ lượt: chỉ trừ khi còn > 0
                            $affected = DB::table('vouchers')
                                ->where('id', $voucher->id)
                                ->where('quantity', '>', 0)
                                ->decrement('quantity');

                            if ($affected === 0) {
                                // Hết lượt ngay thời điểm này → hủy áp dụng
                                $voucher       = null;
                                $voucherUsed   = null;
                                $sale_price    = 0.0;
                                $pay_amount    = $total;
                            }
                        }
                    }
                }

                // 6) Tạo Order
                $order = Order::create([
                    'user_id'        => $userId,
                    'address'        => $validated['address'],
                    'phone'          => $validated['phone'],
                    'email'          => $validated['email'],
                    'status'         => 0,
                    'payment_method' => $validated['payment_method'],
                    'total'          => $total,
                    'voucher_code'   => $voucherUsed,      // đã đổi về voucher_code
                    'sale_price'     => $sale_price,
                    'pay_amount'     => $pay_amount,
                    'note'           => $validated['note'] ?? null,
                ]);

                // 7) Lưu item
                foreach ($cartItems as $variantId => $item) {
                    $order->orderDetails()->create([
                        'product_variant_id' => $variantId,
                        'price'              => $item['price'],
                        'quantity'           => $item['quantity'],
                    ]);
                }

                // 8) Ghi log usage (chỉ khi voucher thực sự áp dụng)
                if ($voucher && $voucherUsed && $userId) {
                    DB::table('voucher_usages')->insert([
                        'voucher_id' => $voucher->id,
                        'user_id'    => $userId,
                        'order_id'   => $order->id,
                        'used_at'    => now(),
                    ]);
                }
            });

        } catch (\Throwable $e) {
            return back()->with('error', 'Có lỗi khi đặt hàng: ' . $e->getMessage());
        }

        // 9) Xóa giỏ
        session()->forget('cart');

        return redirect()->route('home.index')->with('success', 'Đặt hàng thành công!');
    }
}
