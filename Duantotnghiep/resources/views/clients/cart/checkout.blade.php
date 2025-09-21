@extends('clients.layouts.master')
@section('title', 'Thanh toán')

@section('content')
    <div class="section py-5">
        <div class="container">
            <div class="row g-4">

                <!-- Cột trái: Thông tin khách hàng -->
                <div class="col-md-7">
                    <div class="card shadow-sm p-4 rounded-3">
                        <h4 class="mb-3">Thông tin thanh toán</h4>
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="coupon_code" id="coupon_code_hidden">

                            <div class="mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>

                            <h5 class="mt-4">Địa chỉ nhận hàng</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tỉnh/Thành phố *</label>
                                    <select name="province_id" id="province" class="form-select"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Quận/Huyện *</label>
                                    <select name="district_id" id="district" class="form-select"></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phường/Xã *</label>
                                    <select name="ward_code" id="ward" class="form-select"></select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ cụ thể *</label>
                                <input type="text" name="address" class="form-control" required>
                                <!-- Đổi từ name="note" thành name="address" -->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="note" rows="3"></textarea>
                            </div>

                            <h5 class="mt-4">Phương thức thanh toán</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                    value="0" checked>
                                <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo"
                                    value="1">
                                <label class="form-check-label" for="momo">Thanh toán MoMo</label>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-checkout w-100">Xác nhận thanh toán</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cột phải: Giỏ hàng -->
                <div class="col-md-5">
                    <div class="card shadow-sm p-4 rounded-3">
                        <h4 class="mb-3">Đơn hàng của bạn</h4>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <tbody>
                                    @foreach ($cart as $item)
                                        <tr>
                                            <td class="d-flex align-items-center">
                                                <img src="{{ $item['image'] ?? '/images/default.png' }}"
                                                    alt="{{ $item['name'] }}" width="60" class="me-2 rounded">
                                                <div>
                                                    <strong>{{ $item['name'] }}</strong><br>
                                                    <small>Màu: {{ $item['color'] }}, Size: {{ $item['size'] }}</small><br>
                                                    <small>x {{ $item['quantity'] }}</small>
                                                </div>
                                                <!-- hidden input -->
                                                <input type="hidden" name="products[{{ $loop->index }}][id]"
                                                    value="{{ $item['id'] }}">
                                                <input type="hidden" name="products[{{ $loop->index }}][quantity]"
                                                    value="{{ $item['quantity'] }}">
                                                <input type="hidden" name="products[{{ $loop->index }}][price]"
                                                    value="{{ $item['price'] }}">
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <div id="order-summary">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính</span>
                                <strong>{{ number_format($totalPrice, 0, ',', '.') }}đ</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tổng cộng</span>
                                <strong class="text-danger fs-5">{{ number_format($totalPrice, 0, ',', '.') }}đ</strong>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="coupon_code" class="form-label">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="coupon_code"
                                    placeholder="Nhập mã giảm giá">
                                <button type="button" class="btn btn-outline-primary" id="apply-coupon">Áp dụng</button>
                            </div>
                            <div id="coupon-message" class="small mt-1 text-muted"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- CSS tùy chỉnh --}}
    <style>
        .btn-checkout {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-checkout:hover {
            opacity: 0.9;
        }
    </style>

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
                msg.textContent = 'CSRF token không tìm thấy.';
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
                    document.getElementById('coupon_code_hidden').value = code.toUpperCase();

                    document.getElementById('order-summary').innerHTML = `
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <strong>${data.subtotal.toLocaleString()}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Giảm giá (${code.toUpperCase()})</span>
                        <strong class="text-success">- ${data.discount_value.toLocaleString()}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tổng cộng</span>
                        <strong class="text-danger fs-5">${data.final_price.toLocaleString()}đ</strong>
                    </div>
                `;
                }
            } catch (error) {
                msg.textContent = 'Không thể áp dụng mã. Vui lòng thử lại.';
                msg.className = 'small mt-1 text-danger';
            }
        });


        document.addEventListener("DOMContentLoaded", function() {
            const provinceSelect = document.getElementById("province");
            const districtSelect = document.getElementById("district");
            const wardSelect = document.getElementById("ward");
            const addressInput = document.querySelector('input[name="address"]');

            // Hàm load tỉnh/thành
            async function loadProvinces() {
                const res = await fetch("https://provinces.open-api.vn/api/p/");
                const provinces = await res.json();
                provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                provinces.forEach(p => {
                    provinceSelect.innerHTML += `<option value="${p.code}">${p.name}</option>`;
                });
            }

            // Khi chọn tỉnh => load huyện
            provinceSelect.addEventListener("change", async function() {
                const provinceCode = this.value;
                districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

                if (!provinceCode) return;

                const res = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
                const data = await res.json();
                data.districts.forEach(d => {
                    districtSelect.innerHTML += `<option value="${d.code}">${d.name}</option>`;
                });
            });

            // Khi chọn huyện => load xã
            districtSelect.addEventListener("change", async function() {
                const districtCode = this.value;
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

                if (!districtCode) return;

                const res = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
                const data = await res.json();
                data.wards.forEach(w => {
                    wardSelect.innerHTML += `<option value="${w.code}">${w.name}</option>`;
                });
            });

            // Khi chọn xã/phường => ghép địa chỉ
            wardSelect.addEventListener("change", function() {
                updateAddress();
            });

            // Cập nhật địa chỉ khi có thay đổi
            function updateAddress() {
                const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
                const district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
                const ward = wardSelect.options[wardSelect.selectedIndex]?.text || '';
                const specificAddress = addressInput.value.trim();

                // Ghép lại địa chỉ
                const fullAddress = `${specificAddress}, ${ward}, ${district}, ${province}`;

                // Đẩy vào input địa chỉ
                addressInput.value = fullAddress; // Đảm bảo trường này có name="address"
            }

            // Load tỉnh khi page ready
            loadProvinces();
        });
    </script>
@endsection
