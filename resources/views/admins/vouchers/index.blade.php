@extends('admins.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Danh sách Voucher</h2>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success"> Tạo Voucher Mới</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị giảm</th>
                    <th>Đơn hàng tối thiểu</th>
                    <th>Giảm tối đa</th>
                    <th>Số lượng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->id }}</td>
                        <td>{{ $voucher->name }}</td>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->type == 'fixed' ? 'Cố định' : 'Phần trăm' }}</td>
                        <td>{{ $voucher->sale_price }}</td>
                        <td>{{ $voucher->min_order }}</td>
                        <td>{{ $voucher->max_price ?? 'Không giới hạn' }}</td>
                        <td>{{ $voucher->quantity }}</td>
                        <td>{{ \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.vouchers.show', $voucher) }}" class="btn btn-info btn-sm mb-1">Xem</a>
                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-primary btn-sm mb-1">Sửa</a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $vouchers->links() }}
</div>
@endsection
