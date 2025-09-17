@extends('clients.layouts.master')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Đơn hàng của tôi</h2>

    <div class="row">
        <!-- Bộ lọc bên trái -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4" style="position: sticky; top: 20px;">
                <div class="card-body">
                    <form method="GET" action="{{ route('orders.index') }}" class="row g-3">

                        <!-- Ô tìm kiếm chung -->
                        <div class="col-12">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                class="form-control form-control-sm" placeholder="Mã đơn hoặc tên sản phẩm">
                        </div>

                        <!-- Trạng thái -->
                        <div class="col-12">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang giao</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Hoàn tất</option>
                            </select>
                        </div>

                        <!-- Khoảng giá -->
                        <div class="col-12">
                            <label class="form-label">Khoảng giá (VNĐ)</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                    class="form-control form-control-sm" placeholder="Từ">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                    class="form-control form-control-sm" placeholder="Đến">
                            </div>
                        </div>

                        <!-- Ngày đặt -->
                        <div class="col-12">
                            <label class="form-label">Ngày đặt</label>
                            <div class="d-flex flex-column gap-4">
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                    class="form-control form-control-sm">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                    class="form-control form-control-sm">
                            </div>
                        </div>


                        <!-- Nút -->
                        <div class="col-12 mt-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-filter"></i> Lọc
                                </button>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách đơn hàng bên phải -->
        <div class="col-md-9">
            @if($orders->isEmpty())
                <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
            @else
                @foreach($orders as $order)
                    <div class="card mb-4 shadow-sm border rounded">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div><strong>Đơn hàng #{{ $order->id }}</strong></div>
                            <div>
                                @if($order->status == 0)
                                    <span class="badge bg-warning">Đang xử lý</span>
                                @elseif($order->status == 1)
                                    <span class="badge bg-info">Đang giao</span>
                                @elseif($order->status == 2)
                                    <span class="badge bg-success">Hoàn tất</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <table class=" table align-middle">
                                <thead>
                                    <tr>
                                        <th>Ảnh SP</th>
                                        <th>Tên SP</th>
                                        <th>Mô tả</th>
                                        <th>Giá</th>
                                        <th>SL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderDetails as $detail)
                                        <tr>
                                            <td style="width:100px">
                                                <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                     class="img-fluid rounded" style="max-height:80px; object-fit:cover;">
                                            </td>
                                            <td>{{ $detail->product->name }}</td>
                                            <td class="text-muted">{{ Str::limit($detail->product->description, 60) }}</td>
                                            <td class="text-danger fw-bold">{{ number_format($detail->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $detail->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <p class="text-muted mb-0" style="font-size: 0.70rem;">
                                    Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                                
                                <div class="d-flex align-items-baseline gap-1">
                                    <span style="font-size: 0.85rem; color: #555;">Thành tiền:</span>
                                    <span class="fw-bold text-danger" style="font-size: 1.1rem;">
                                        {{ number_format($order->pay_amount, 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $orders->appends(request()->query())->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<style>
/* Form lọc */
.card-body form .d-flex.gap-2 input {
    flex: 1;
}

/* Khoảng cách giữa hai ngày */
.card-body form .d-flex.gap-2 input[type="date"] {
    margin-right: 5px;
}

/* Table */
.table th, .table td {
    vertical-align: middle;
}

/* Tên sản phẩm dài xuống dòng */
.table td:nth-child(2) {
    word-break: break-word;
    max-width: 200px; /* điều chỉnh theo ý bạn */
}

/* Mô tả sản phẩm giới hạn số chữ */
.table td:nth-child(3) {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px; /* điều chỉnh theo ý bạn */
}

/* Ảnh sản phẩm */
.table td img {
    display: block;
    margin: 0 auto;
}
</style>
