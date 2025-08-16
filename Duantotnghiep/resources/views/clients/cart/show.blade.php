@extends('clients.layouts.master')
@section('title', 'Giỏ hàng')
@section('content')

    <!-- LOADER -->
    <div class="preloader">
        <div class="lds-ellipsis"><span></span><span></span><span></span></div>
    </div>

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
                                                <td class="product-thumbnail">
                                                    <img src="{{ $product['image'] ?? 'default.jpg' }}"
                                                        alt="{{ $product['name'] }}" style="width: 80px;">
                                                </td>
                                                <td class="product-name">{{ $product['name'] }}<br>
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
                                                <td class="product-subtotal">
                                                    {{ number_format($product['price'] * $product['quantity']) }} đ</td>
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
                                                    <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                                        <div class="coupon field_form input-group">

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-6 text-start text-md-end">
                                                        <button class="btn btn-line-fill btn-sm" type="submit">Cập nhật giỏ
                                                            hàng</button>

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
                                                <td><strong>{{ number_format($totalPrice) }} đ</strong></td>
                                            </tr>
                                        @endif
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="mt-4">
                    <a href="#" class="btn btn-outline-secondary">Tiếp tục mua hàng</a>
                </div>
            </div>
        </div>
    </div>

@endsection
