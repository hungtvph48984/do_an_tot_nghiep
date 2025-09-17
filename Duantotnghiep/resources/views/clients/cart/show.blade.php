@extends('clients.layouts.master')

@section('title', 'Giỏ hàng')

@section('content')

    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Giỏ hàng của bạn</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Giỏ hàng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- START MAIN CONTENT -->
    <div class="main_content">
        <div class="section">
            <div class="container">
                <form action="#" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive shop_cart_table">
                                <table class="table">

                                    <thead>
                                        <tr>
                                            <th>
                                                <!-- Chọn tất cả sản phẩm -->
                                                <div class="stardust-checkbox-wrapper mb-4">
                                                    <label class="stardust-checkbox stardust-checkbox--checked">
                                                        <input class="stardust-checkbox__input" type="checkbox"
                                                            aria-checked="true" tabindex="0" role="checkbox"
                                                            aria-label="Chọn tất cả sản phẩm trong giỏ hàng"
                                                            id="select-all-checkbox">
                                                        <div class="stardust-checkbox__box"></div> Chọn tất cả
                                                    </label>
                                                </div>
                                            </th>
                                            <th class="product-thumbnail">Hình ảnh</th>
                                            <th class="product-name">Sản phẩm</th>
                                            <th class="product-price">Giá</th>
                                            <th class="product-quantity">Số lượng</th>
                                            <th class="product-subtotal">Thành tiền</th>
                                            <th class="product-remove">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart as $product)
                                            <tr>
                                                <td class="product-remove">
                                                    <!-- Checkbox của từng sản phẩm -->
                                                    <input type="checkbox" class="product-checkbox"
                                                        data-product-id="{{ $product['id'] }}">

                                                </td>


                                                <td class="product-thumbnail">
                                                    <img src="{{ asset('storage/products/' . ($product['image'] ?? 'default.jpg')) }}"
                                                        alt="{{ $product['name'] }}" style="width: 80px;">
                                                </td>
                                                <td class="product-name">
                                                    {{ $product['name'] }}<br>
                                                    <small>Màu: <span
                                                            class="badge bg-primary">{{ $product['color'] }}</span></small><br>
                                                    <small>Kích thước: {{ $product['size'] }}</small>
                                                </td>
                                                <td class="product-price">{{ number_format($product['price']) }} đ</td>
                                                <td class="product-quantity">
                                                    <div class="quantity">
                                                        <input type="button" value="-" class="minus">
                                                        <input type="number" name="quantity[{{ $product['id'] }}]"
                                                            value="{{ $product['quantity'] }}" min="1" class="qty"
                                                            size="4">
                                                        <input type="button" value="+" class="plus">
                                                    </div>
                                                </td>
                                                <td class="product-subtotal"
                                                    data-subtotal="{{ $product['price'] * $product['quantity'] }}">
                                                    {{ number_format($product['price'] * $product['quantity']) }} đ
                                                </td>
                                                <td class="product-remove">
                                                    <a href="{{ route('cart.remove', $product['id']) }}"><i
                                                            class="ti-close"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="px-0">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-lg-8 col-md-6 text-start text-md-end">
                                                        {{-- Chỉ hiển thị nút Thanh toán khi có sản phẩm --}}
                                                        @if (!empty($cart) && count($cart) > 0)
                                                            <a href="{{ route('cart.checkout') }}"
                                                                class="btn btn-success btn-sm">Thanh toán</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- Chỉ hiện tổng tiền khi có sản phẩm --}}
                                        @if (!empty($cart) && count($cart) > 0)
                                            <tr>
                                                <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                                                <td><strong id="total-price">{{ number_format($totalPrice) }} đ</strong>
                                                </td>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-4">
                    <a href="{{ route('home.index') }}" class="btn btn-outline-secondary">Tiếp tục mua hàng</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== Script xử lý thay đổi trạng thái checkbox ====== --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectAllCheckbox = document.querySelector('#select-all-checkbox');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const totalPriceElement = document.getElementById('total-price');
            let totalPrice = 0;

            // Cập nhật tổng tiền khi một sản phẩm được chọn
            function updateTotalPrice() {
                totalPrice = 0;

                // Lặp qua tất cả các checkbox của sản phẩm và cộng tổng tiền cho các sản phẩm đã chọn
                productCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const row = checkbox.closest('tr');
                        const subtotal = parseInt(row.querySelector('.product-subtotal').getAttribute(
                            'data-subtotal'));
                        totalPrice += subtotal;
                    }
                });

                // Cập nhật giá trị tổng tiền
                totalPriceElement.textContent = totalPrice.toLocaleString() + ' đ';
            }

            // Lắng nghe sự kiện thay đổi của checkbox "Chọn tất cả"
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = selectAllCheckbox.checked;

                // Lặp qua tất cả các checkbox sản phẩm và thay đổi trạng thái
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });

                // Cập nhật lại tổng tiền
                updateTotalPrice();
            });

            // Nếu tất cả sản phẩm được chọn, checkbox "Chọn tất cả" sẽ được tự động chọn
            productCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(productCheckboxes).every(checkbox => checkbox
                        .checked);
                    selectAllCheckbox.checked = allChecked;

                    // Cập nhật lại tổng tiền sau khi thay đổi
                    updateTotalPrice();
                });
            });

            // Cập nhật tổng tiền khi trang web được tải lần đầu
            updateTotalPrice();
        });
    </script>

@endsection
