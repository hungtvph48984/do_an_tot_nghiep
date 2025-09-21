<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;


class CouponController extends Controller
{
    public function apply(Request $request)
    {
        try {
            // 1) Validate input
            $request->validate([
                'coupon_code'  => 'required|string|max:50',
                'order_amount' => 'nullable|numeric|min:0',
            ]);

            // 2) Nếu route này để trong middleware('auth') thì đoạn dưới có thể bỏ.
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn cần đăng nhập để áp dụng mã.',
                ], 401);
            }

            // 3) Chuẩn hoá & lấy amount
            $code   = strtoupper(trim((string) $request->input('coupon_code')));
            $amount = $request->input('order_amount');

            if ($amount === null) {
                $cart   = session('cart', []);
                $amount = (float) collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            } else {
                $amount = (float) $amount;
            }

            // 4) Tìm voucher (so khớp case-insensitive)
            $voucher = Voucher::whereRaw('UPPER(code) = ?', [$code])->first();
            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại.',
                ], 404);
            }

            // 5) Nếu có bảng voucher_usages thì mới kiểm tra "đã dùng"
            if (Schema::hasTable('voucher_usages')) {
                $alreadyUsed = DB::table('voucher_usages')
                    ->where('voucher_id', $voucher->id)
                    ->where('user_id', Auth::id())
                    ->exists();

                if ($alreadyUsed) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn đã sử dụng mã này rồi.',
                    ], 422);
                }
            }

            // 6) Kiểm tra hiệu lực/min_order/quantity (viết trong Model)
            if (!$voucher->isValidForOrder($amount)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',
                ], 422);
            }

            // 7) Tính giảm
            $discount   = (float) $voucher->calculateDiscount($amount);
            $finalPrice = max(0, $amount - $discount);

            return response()->json([
                'success'        => true,
                'discount_type'  => $voucher->type,       // fixed | percent
                'discount_value' => $discount,            // số tiền VND
                'subtotal'       => $amount,
                'final_price'    => $finalPrice,
                'message'        => 'Áp dụng mã thành công!',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('apply-coupon error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ.',
            ], 500);
        }

    }
}
