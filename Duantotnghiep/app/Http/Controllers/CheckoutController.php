<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{

    public function paymentSuccess()
    {
        return view('clients.payment.success')
            ->with('success_message', 'Thanh toán thành công! Đơn hàng của bạn đang được xử lý.');
    }

    public function paymentFailed()
    {
        return view('clients.payment.failed')
            ->with('error_message', 'Thanh toán không thành công. Vui lòng thử lại hoặc chọn phương thức khác.');
    }
    public function store(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'note'           => 'nullable|string|max:500',
            'payment_method' => 'required|in:0,1', // 0 = COD, 1 = MOMO
            'coupon_code'    => 'nullable|string|max:50',
        ]);

        // 2. Lấy giỏ hàng
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // 3. Tính tổng tiền
        $total = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);

        $sale_price  = 0.0;
        $pay_amount  = $total;
        $voucherUsed = null;
        $voucher     = null;
        $userId      = Auth::id();

        try {
            DB::transaction(function () use (
                $validated,
                $cartItems,
                $total,
                $userId,
                &$sale_price,
                &$pay_amount,
                &$voucherUsed,
                &$voucher,
                &$order
            ) {
                // 4. Áp dụng voucher (nếu có)
                if (!empty($validated['coupon_code'])) {
                    $voucher = Voucher::where('code', $validated['coupon_code'])
                        ->lockForUpdate()
                        ->first();

                    if ($voucher && $voucher->isValidForOrder($total)) {
                        $alreadyUsed = DB::table('voucher_usages')
                            ->where('voucher_id', $voucher->id)
                            ->where('user_id', $userId ?? 0)
                            ->lockForUpdate()
                            ->exists();

                        if (!$alreadyUsed) {
                            $sale_price  = $voucher->calculateDiscount($total);
                            $pay_amount  = max(0, $total - $sale_price);
                            $voucherUsed = $voucher->code;

                            DB::table('vouchers')
                                ->where('id', $voucher->id)
                                ->where('quantity', '>', 0)
                                ->decrement('quantity');
                        }
                    }
                }

                // 5. Tạo Order
                $order = Order::create([
                    'user_id'        => $userId,
                    'address'        => $validated['address'],  // Địa chỉ từ form
                    'phone'          => $validated['phone'],
                    'email'          => $validated['email'],
                    'status'         => 0,
                    'payment_method' => $validated['payment_method'],
                    'total'          => $total,
                    'voucher_code'   => $voucherUsed,
                    'sale_price'     => $sale_price,
                    'pay_amount'     => $pay_amount,
                    'note'           => $validated['note'] ?? null,
                ]);

                // 6. Lưu item
                foreach ($cartItems as $variantId => $item) {
                    $order->orderDetails()->create([
                        'product_variant_id' => $variantId,
                        'price'              => $item['price'],
                        'quantity'           => $item['quantity'],
                    ]);
                }

                // 7. Lưu voucher usage
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

        // 8. Nếu MoMo thì redirect sang MoMo
        if ($request->payment_method == 1) {
            return $this->momoPayment($order);
        }

        // 9. Nếu COD thì xóa giỏ và báo thành công
        session()->forget('cart');
        return redirect()->route('home.index')->with('success', 'Đặt hàng thành công!');
    }


    //thanh toan momo
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function momoPayment(Order $order)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey   = 'klm05TvNBzhg7h7j';
        $secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo   = "Thanh toán đơn hàng #" . $order->id;
        $amount      = (string) number_format($order->pay_amount, 0, '', '');

        // Tạo orderId duy nhất bằng cách nối thêm timestamp hoặc random
        $orderId     = $order->id . '_' . time(); // hoặc dùng uniqid()
        $baseUrl     = env('APP_URL');
        $redirectUrl = $baseUrl . '/payment/success';
        $ipnUrl      = $baseUrl . '/payment/momo/ipn';
        $extraData   = base64_encode(json_encode(['order_id' => $order->id]));

        $requestId   = (string) time();
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test Store",
            'storeId'     => "MomoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId, // Đã sửa
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature
        ];

        // Debug trước khi gửi
        \Log::info('MoMo Request Data:', $data);
        \Log::info('Raw Hash:', ['rawHash' => $rawHash]);

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        Log::info('MoMo Response:', $jsonResult);

        if (isset($jsonResult['resultCode']) && $jsonResult['resultCode'] == 0) {
            return redirect()->to($jsonResult['payUrl']);
        } else {
            return back()->with('error', 'Lỗi thanh toán MoMo: ' . ($jsonResult['message'] ?? 'Unknown error'));
        }
    }
    public function momoIPN(Request $request)
    {
        $partnerCode = 'MOMOBKUN20180529';
        $secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $input = $request->all();

        // Tạo rawHash để verify signature
        $receiveDate = $input["date"];
        $orderId = $input["orderId"];
        $amount = $input["amount"];
        $extraData = $input["extraData"];
        $message = $input["message"];
        $orderType = $input["orderType"];

        $rawHash = "partnerCode=" . $partnerCode . "&orderId=" . $orderId . "&requestId=" . $input["requestId"] . "&amount=" . $amount . "&orderType=" . $orderType . "&transId=" . $input["transId"] . "&resultCode=" . $input["resultCode"] . "&message=" . $message . "&payType=" . $input["payType"] . "&orderInfo=" . $input["orderInfo"] . "&extraData=" . $extraData . "&requestDate=" . $receiveDate;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Verify signature
        if ($signature !== $input["signature"]) {
            Log::error('MoMo IPN Signature verification failed');
            http_response_code(400);
            return response()->json(['message' => 'Invalid signature']);
        }

        // Kiểm tra resultCode
        if ($input["resultCode"] == 0) {
            // Thanh toán thành công
            $extraData = json_decode(base64_decode($extraData), true);
            $orderId = $extraData['order_id'] ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->status == 0) {
                    $order->update([
                        'status' => 1, // Đã thanh toán
                        'momo_trans_id' => $input["transId"]
                    ]);

                    // Xóa giỏ hàng
                    session()->forget('cart');
                }
            }
        }

        http_response_code(200);
        return response()->json(['message' => 'OK']);
    }
}
