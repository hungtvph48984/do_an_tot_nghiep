<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Carbon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $code = trim($request->input('coupon_code'));
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => ' Mã giảm giá không tồn tại.']);
        }

        if ($voucher->expires_at && Carbon::now()->gt($voucher->expires_at)) {
            return response()->json(['success' => false, 'message' => ' Mã giảm giá đã hết hạn.']);
        }

        return response()->json([
            'success' => true,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
            'message' => ' Áp dụng mã thành công!'
        ]);
    }
}
