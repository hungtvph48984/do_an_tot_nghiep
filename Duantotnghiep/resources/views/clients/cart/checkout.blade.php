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
                    <form action="{{ route('cart.checkout.post') }}" method="POST">
                        @csrf
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
                        {{-- <div class="form-group mb-3">
                            <input type="text" class="form-control" name="address" required
                                placeholder="Địa chỉ nhận hàng *">
                        </div> --}}
                        {{-- Địa chỉ nhận hàng --}}
                    <div class="checkout-section">
                        <h5 class="section-title">Địa chỉ nhận hàng</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố *</label>
                                <select name="province_id" id="province" class="form-select" required></select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">Quận/Huyện *</label>
                                <select name="district_id" id="district" class="form-select" required></select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">Phường/Xã *</label>
                                <select name="ward_code" id="ward" class="form-select" required></select>
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
                        <div class="form-group mb-3">
                            <label class="form-label d-block">Phương thức thanh toán</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id="cod" value="cod"
                                    checked>
                                <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="payment" id="bank" value="bank">
                                <label class="form-check-label" for="bank">Chuyển khoản ngân hàng</label>

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
                                            <td>{{ number_format($item['price'] * $item['quantity'], 2, ',') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Tổng cộng</th>
                                        <td>{{ number_format($totalPrice, 2, ',') }}đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        const fetchProvinces = async () => {
        try {
            const response = await fetch('{{ route("shipping.provinces") }}');
            if (!response.ok) throw new Error('Lỗi mạng khi tải tỉnh/thành');
            const result = await response.json();
            if (result && Array.isArray(result.data)) {
                provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                result.data.forEach(p => provinceSelect.innerHTML += `<option value="${p.ProvinceID}">${p.ProvinceName}</option>`);
            }
        } catch (error) { console.error(error); }
    };

    const fetchDistricts = async (provinceId) => {
        districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        if (!provinceId) return;
        try {
            const response = await fetch(`{{ route("shipping.districts") }}?province_id=${provinceId}`);
            const result = await response.json();
            if (result && Array.isArray(result.data)) {
                result.data.forEach(d => districtSelect.innerHTML += `<option value="${d.DistrictID}">${d.DistrictName}</option>`);
            }
        } catch (error) { console.error(error); }
    };

    const fetchWards = async (districtId) => {
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        if (!districtId) return;
        try {
            const response = await fetch(`{{ route("shipping.wards") }}?district_id=${districtId}`);
            const result = await response.json();
            if (result && Array.isArray(result.data)) {
                result.data.forEach(w => wardSelect.innerHTML += `<option value="${w.WardCode}">${w.WardName}</option>`);
            }
        } catch (error) { console.error(error); }
    };
    </script> --}}
@endsection
