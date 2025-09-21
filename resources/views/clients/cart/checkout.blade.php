@extends('clients.layouts.master')
@section('title', 'Thanh toán')

@section('content')
    <div class="section">
        <div class="container my-5">
            <div class="row">
                <!-- Thông tin thanh toán -->
                <div class="col-md-6">
                    <div class="heading_s1">
                        <h4>Thông tin thanh toán</h4>
                    </div>

                        <form action="{{ route('checkout.store') }}" method="POST">                        
                        @csrf

                        {{-- Hidden để nhận mã giảm giá khi submit --}}
                        <input type="hidden" name="coupon_code" id="coupon_code_hidden">

                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="name" required
                                   value="{{ Auth::user()->name }}" placeholder="Họ và tên *">
                        </div>
                        <div class="form-group mb-3">
                            <input type="email" class="form-control" name="email" required
                                   value="{{ Auth::user()->email }}" placeholder="Email *">
                        </div>
                        <div class="form-group mb-3">
                            <input type="tel" class="form-control" name="phone" required
                                   placeholder="Số điện thoại *">
                        </div>

                        {{-- Địa chỉ nhận hàng --}}
                        <div class="checkout-section">
                            <h5 class="section-title">Địa chỉ nhận hàng</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="province" class="form-label">Tỉnh/Thành phố *</label>
                                    <select name="province_id" id="province" class="form-select"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="district" class="form-label">Quận/Huyện *</label>
                                    <select name="district_id" id="district" class="form-select"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ward" class="form-label">Phường/Xã *</label>
                                    <select name="ward_code" id="ward" class="form-select"></select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ cụ thể (số nhà, tên đường) *</label>
                                <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <textarea class="form-control" name="note" rows="3" placeholder="Ghi chú"></textarea>
                        </div>

                        {{-- Phương thức thanh toán: KHỚP controller --}}
                        <div class="form-group mb-3">
                            <label class="form-label d-block">Phương thức thanh toán</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="0" checked>
                                <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank" value="1">
                                <label class="form-check-label" for="bank">Chuyển khoản/MoMo</label>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-fill-out btn-block">Xác nhận thanh toán</button>
                        </div>
                    </form>
                </div>

                <!-- Thông tin đơn hàng -->
                <div class="col-md-6">
                    <div class="order_review">

                        {{-- Ô nhập mã giảm giá (ngoài form) --}}
                        <div class="form-group mb-3">
                            <label for="coupon_code" class="form-label">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="coupon_code" placeholder="Nhập mã giảm giá">
                                <button type="button" class="btn btn-outline-primary" id="apply-coupon">Áp dụng</button>
                            </div>
                            <div id="coupon-message" class="small mt-1 text-muted"></div>
                        </div>

                        <div class="heading_s1">
                            <h4>Đơn hàng của bạn</h4>
                        </div>

                        <div class="table-responsive order_table">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Tổng</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($cart as $item)
                                    <tr>
                                        <td>
                                            {{ $item['name'] }} <br>
                                            <small>Màu: {{ $item['color'] }}, Size: {{ $item['size'] }}</small><br>
                                            <small>x {{ $item['quantity'] }}</small>
                                        </td>
                                        <td>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                                </tbody>

                                {{-- Tóm tắt đơn hàng --}}
                                <tfoot id="order-summary">
                                    <tr>
                                        <th>Tạm tính</th>
                                        <td>{{ number_format($totalPrice, 0, ',', '.') }}đ</td>
                                    </tr>
                                    @if (session('coupon'))
                                        <tr>
                                            <th>Giảm giá ({{ session('coupon.code') }})</th>
                                            <td>-{{ number_format(session('coupon.discount'), 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Tổng cộng</th>
                                        <td>
                                            @php
                                                $discount = session('coupon.discount') ?? 0;
                                                $final = max(0, $totalPrice - $discount);
                                            @endphp
                                            {{ number_format($final, 0, ',', '.') }}đ
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JS áp dụng mã --}}
<script>
document.getElementById('apply-coupon').addEventListener('click', async () => {
    const code = document.getElementById('coupon_code').value.trim();
    const tokenElement = document.querySelector('meta[name="csrf-token"]');
    const msg = document.getElementById('coupon-message');

    const rawSubtotal = {{ $totalPrice ?? 0 }};

    if (!code) {
        msg.textContent = 'Vui lòng nhập mã.';
        msg.className = 'small mt-1 text-danger';
        return;
    }

    const token = tokenElement ? tokenElement.getAttribute('content') : null;
    if (!token) {
        msg.textContent = 'CSRF token không tìm thấy. Kiểm tra thẻ <meta> trong layout.';
        msg.className = 'small mt-1 text-danger';
        return;
    }

    try {
        const res = await fetch("{{ route('cart.applyCoupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                coupon_code: code,
                order_amount: rawSubtotal
            })
        });

        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (e) {
            console.error('Server không trả JSON. Raw:', raw);
            msg.textContent = 'Có lỗi máy chủ, vui lòng thử lại.';
            msg.className = 'small mt-1 text-danger';
            return;
        }

        if (!res.ok || !data.success) {
            msg.textContent = data.message || 'Áp mã thất bại.';
            msg.className = 'small mt-1 text-danger';
        } else {
            msg.textContent = data.message || 'Áp dụng mã thành công!';
            msg.className = 'small mt-1 text-success';

            // ✅ Gán mã vào input ẩn để gửi kèm khi submit form
            document.getElementById('coupon_code_hidden').value = code.toUpperCase();

            // Cập nhật tóm tắt đơn hàng
            document.getElementById('order-summary').innerHTML = `
                <tr><th>Tạm tính</th><td>${data.subtotal.toLocaleString()}đ</td></tr>
                <tr><th>Giảm giá (${code.toUpperCase()})</th><td>- ${data.discount_value.toLocaleString()}đ</td></tr>
                <tr><th>Tổng cộng</th><td>${data.final_price.toLocaleString()}đ</td></tr>
            `;
        }

    } catch (error) {
        console.error('Fetch error:', error);
        msg.textContent = 'Không thể áp dụng mã. Vui lòng thử lại.';
        msg.className = 'small mt-1 text-danger';
    }
});
</script>


@endsection
