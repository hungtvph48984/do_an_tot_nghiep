@extends('clients.layouts.master')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4">
    <h2>Danh sách đơn hàng</h2>

    @if($orders->isEmpty())
        <p>Bạn chưa có đơn hàng nào.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($order->status == 0)
                            <span class="badge bg-warning">Đang xử lý</span>
                        @elseif($order->status == 1)
                            <span class="badge bg-info">Đang giao</span>
                        @elseif($order->status == 2)
                            <span class="badge bg-success">Hoàn tất</span>
                        @endif
                    </td>
                    <td>{{ number_format($order->pay_amount, 0, ',', '.') }} đ</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
