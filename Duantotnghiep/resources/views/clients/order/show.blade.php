@extends('clients.layouts.master')
@section('title', 'Chi tiết đơn hàng')
@section('content')

<div class="container py-5">
    <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

    <div class="mb-4">
        <p><strong>Người đặt:</strong> {{ $order->user->name ?? 'Khách' }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>SĐT:</strong> {{ $order->phone }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
        <p><strong>Phương thức thanh toán:</strong> {{ $order->payment }}</p>
        <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
        <p><strong>Trạng thái:</strong>
            @if($order->status == 0)
                <span class="badge bg-warning">Chờ xử lý</span>
            @elseif($order->status == 1)
                <span class="badge bg-info">Đang giao</span>
            @else
                <span class="badge bg-success">Hoàn thành</span>
            @endif
        </p>
    </div>

    <h4>Sản phẩm trong đơn hàng</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Màu</th>
                <th>Kích thước</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->productVariant->product->name }}</td>
                    <td>{{ $detail->productVariant->color->name }}</td>
                    <td>{{ $detail->productVariant->size->name }}</td>
                    <td>{{ number_format($detail->price) }} đ</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->price * $detail->quantity) }} đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="text-end">Tổng tiền: <strong>{{ number_format($order->pay_amount) }} đ</strong></h4>
    <div class="mt-4 text-end">
    <a href="{{ route('orders.index') }}" class="btn btn-primary">Quay lại danh sách đơn hàng</a>
</div>
</div>


@endsection
