<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        //Lấy ra biến thể có id là $variantId
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
        //Gán lại giỏ hàng vào session
        session()->put('cart', $cart);

        //Trả về thông báo thành công
       return redirect()->route('cart.show')->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');

    }
    //hiển thị giỏ hàng
    public function show()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        // dd($cart);
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
    //Hiển thị form thanh toán
    public function showCheckout()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        return view('clients.cart.checkout', compact('cart', 'totalPrice'));
    }
    //thanh toán
    public function checkout(Request $request)
    {
        // Xử lý thanh toán ở đây
        // Ví dụ: lưu thông tin đơn hàng vào cơ sở dữ liệu, gửi email xác nhận, v.v.
        // Lấy thông tin giỏ hàng từ session
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        // Kiểm tra xem giỏ hàng có rỗng không
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng rỗng!');
        }
        // Kiểm tra thông tin thanh toán
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        // Lưu thông tin thanh toán vào cơ sở dữ liệu (nếu cần)
        $order  = new Order();

        $order->email = $request->input('email');
        $order->phone = $request->input('phone');
        $order->address = $request->input('address');
        $order->user_id = Auth::id();
        $order->total = $totalPrice;
        $order->status = 0; // 0: đơn hàng mới
        $order->payment = $request->input('payment'); //Phương thức thanh toán
        $order->note = $request->input('note'); //Ghi chú
        $order->pay_amount = $totalPrice; //Số tiền thanh toán
        $order->save();
        // Lưu thông tin chi tiết đơn hàng vào cơ sở dữ liệu (nếu cần)
        foreach ($cart as $item) {
            OrderDetail::query()->create([
                'order_id' => $order->id,
                'product_variant_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        // Sau khi thanh toán thành công, xóa giỏ hàng
        session()->forget('cart');

        return redirect()->route('order.show', $order->id)
                 ->with('success', 'Thanh toán thành công!');
    }
}
