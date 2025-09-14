@extends('admins.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Chi tiết Voucher: {{ $voucher->name }}</h2>

    <div class="mb-3 d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">← Quay lại danh sách voucher</a>
        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-primary">Chỉnh sửa</a>
        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa voucher này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xóa</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $voucher->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên voucher</th>
                        <td>{{ $voucher->name }}</td>
                    </tr>
                    <tr>
                        <th>Mã voucher</th>
                        <td><strong>{{ $voucher->code }}</strong></td>
                    </tr>
                    <tr>
                        <th>Loại voucher</th>
                        <td>{{ $voucher->type == 'fixed' ? 'Giảm cố định' : 'Giảm theo phần trăm' }}</td>
                    </tr>
                    <tr>
                        <th>Giá trị giảm</th>
                        <td>
                            @if($voucher->type == 'fixed')
                                {{ number_format($voucher->sale_price) }} VNĐ
                            @else
                                {{ $voucher->sale_price }}%
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Đơn hàng tối thiểu</th>
                        <td>{{ number_format($voucher->min_order) }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>Giảm tối đa</th>
                        <td>
                            @if($voucher->type == 'fixed')
                                {{ $voucher->max_price ? number_format($voucher->max_price) . ' VNĐ' : 'Không giới hạn' }}
                            @else
                                {{ $voucher->max_price ? $voucher->max_price . '%' : 'Không giới hạn' }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Số lượng</th>
                        <td>{{ $voucher->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Ngày bắt đầu</th>
                        <td>{{ \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Ngày kết thúc</th>
                        <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @php
                                $now = now();
                                $startDate = \Carbon\Carbon::parse($voucher->start_date);
                                $endDate = \Carbon\Carbon::parse($voucher->end_date);
                            @endphp
                            @if($voucher->quantity <= 0)
                                <span class="badge bg-danger">Hết hàng</span>
                            @elseif($now < $startDate)
                                <span class="badge bg-secondary">Chưa bắt đầu</span>
                            @elseif($now > $endDate)
                                <span class="badge bg-dark">Đã hết hạn</span>
                            @else
                                <span class="badge bg-success">Đang hoạt động</span>
                            @endif
                        </td>
                    </tr>
                    @if($voucher->description)
                    <tr>
                        <th>Mô tả</th>
                        <td>{{ $voucher->description }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $voucher->created_at ? $voucher->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>{{ $voucher->updated_at ? $voucher->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
