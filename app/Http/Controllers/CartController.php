<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        $variant = ProductVariant::with(['product', 'size', 'color'])
            ->findOrFail($variantId);

        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            $cart[$variantId] = [
                'id' => $variantId,
                'name' => $variant->product->name,
                'image' => $variant->image,
                'size' => $variant->size->name,
                'color' => $variant->color->name,
                'price' => $variant->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');
    }

    // Hiển thị giỏ hàng
    public function show()
    {
        $cart = session()->get('cart', []);
        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return view('clients.cart.show', compact('cart', 'totalPrice'));
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('cart.show')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }

        return redirect()->route('cart.show')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng!');
    }

    // Hiển thị form thanh toán
    public function showCheckout()
    {
        $cart = session()->get('cart', []);
        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return view('clients.cart.checkout', compact('cart', 'totalPrice'));
    }

    // Xử lý thanh toán
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng rỗng!');
        }

        $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // Validate thông tin khách hàng
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        // Map phương thức thanh toán: cod = 0, bank = 1
        $paymentMap = [
            'cod' => 0,
            'bank' => 1,
        ];
        $paymentMethod = $paymentMap[$request->input('payment')] ?? 0;

        // Tạo đơn hàng
       
$order = Order::create([
    'user_id'        => Auth::id(),
    'name'           => $request->name,
    'email'          => $request->email,
    'phone'          => $request->phone,
    'province_id'    => $request->province_id,
    'district_id'    => $request->district_id,
    'ward_id'        => $request->ward_id, // 
    'address'        => $request->address,
    'note'           => $request->note,
    'total'          => $totalPrice,
    'pay_amount'     => $totalPrice,
    'status'         => 0,
    'payment_method' => $paymentMethod,
    'payment_status' => 'unpaid', // 
]);


        // Lưu chi tiết đơn hàng
        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_variant_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Xóa giỏ hàng sau khi đặt
        session()->forget('cart');

        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Thanh toán thành công!');
    }
}
