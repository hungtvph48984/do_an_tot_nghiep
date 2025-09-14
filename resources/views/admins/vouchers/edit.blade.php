@extends('admins.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">Chỉnh sửa Voucher: {{ $voucher->name }}</h2>

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Tên Voucher</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $voucher->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mã Voucher</label>
            <input type="text" class="form-control" value="{{ $randomCode }}" disabled>
            <input type="hidden" name="random_code" value="{{ $randomCode }}">
            <small class="text-muted">Mã voucher được sinh ngẫu nhiên khi cập nhật</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại Voucher</label>
            <select name="type" class="form-select" required>
                <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Giảm giá cố định</option>
                <option value="percent" {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>Giảm giá theo phần trăm</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá trị giảm</label>
            <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $voucher->sale_price) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Đơn hàng tối thiểu</label>
            <input type="number"  name="min_order" class="form-control" value="{{ old('min_order', $voucher->min_order) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá trị giảm tối đa</label>
            <input type="number"  name="max_price" class="form-control" value="{{ old('max_price', $voucher->max_price) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $voucher->quantity) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $voucher->start_date->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày kết thúc</label>
            <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $voucher->end_date->format('Y-m-d\TH:i')) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật Voucher</button>
    </form>
</div>
@endsection
