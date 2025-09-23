@extends('admins.layouts.master')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách đơn hàng</h3>
                    <a href="{{ url('/admin') }}" class="btn btn-secondary float-right">
                      <i class="fas fa-arrow-left"></i> Quay lại trang chủ Admin
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form tìm kiếm -->
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo Mã ĐH, tên, email, SĐT..." value="{{ request('keyword') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" title="Từ ngày">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" title="Đến ngày">
                            </div>
                            <div class="col-md-2">
                                <select name="payment_status_filter" class="form-control" title="Trạng thái thanh toán">
                                    <option value="">-- Trạng thái TT --</option>
                                    <option value="0" {{ request('payment_status_filter') == '0' ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="1" {{ request('payment_status_filter') == '1' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="2" {{ request('payment_status_filter') == '2' ? 'selected' : '' }}>Thất bại</option>
                                    <option value="3" {{ request('payment_status_filter') == '3' ? 'selected' : '' }}>Đã hoàn tiền</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select name="price_order" class="form-control" title="Sắp xếp giá">
                                    <option value="">Giá</option>
                                    <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>↑</option>
                                    <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>↓</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex">
                                <button class="btn btn-primary flex-grow-1 mr-1" type="submit">
                                    <i class="fas fa-search"></i> Tìm
                                </button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary flex-grow-1">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Thông tin đơn hàng</th>
                                    <th>Địa chỉ</th>
                                    <th>Tổng tiền</th>
                                    <th>Hình thức thanh toán</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Trạng thái đơn hàng</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>Mã: #{{ $order->id }}</strong><br>
                                            KH: {{ $order->user->name ?? 'N/A' }}<br>
                                            Email: {{ $order->email }}<br>
                                            SĐT: {{ $order->phone }}
                                        </td>
                                        <td>
                                            @if(!empty($order->address))
                                                <div><strong>{{ $order->address }}</strong></div>
                                                @php
                                                    $locationParts = [];
                                                    if ($order->ward && $order->ward->name) {
                                                        $locationParts[] = $order->ward->name;
                                                    }
                                                    if ($order->district && $order->district->name) {
                                                        $locationParts[] = $order->district->name;
                                                    }
                                                    if ($order->province && $order->province->name) {
                                                        $locationParts[] = $order->province->name;
                                                    }
                                                @endphp
                                                @if(count($locationParts) > 0)
                                                    <div class="text-muted small">{{ implode(', ', $locationParts) }}</div>
                                                @endif
                                            @else
                                                <span class="text-muted">Không có địa chỉ</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ number_format($order->total, 0, ',', '.') }}đ</strong></td>
                                        <td>
                                            @if($order->payment_method == 0)
                                                <span class="badge badge-warning">COD</span>
                                            @elseif($order->payment_method == 1)
                                                <span class="badge badge-info">Momo</span>
                                            @else
                                                <span class="badge badge-secondary">Khác</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $paymentStatus = $order->payment_status ?? 0;
                                            @endphp
                                            @if($paymentStatus == 1)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Đã thanh toán
                                                </span>
                                            @elseif($paymentStatus == 0)
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Chưa thanh toán
                                                </span>
                                            @elseif($paymentStatus == 2)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle"></i> Thanh toán thất bại
                                                </span>
                                            @elseif($paymentStatus == 3)
                                                <span class="badge badge-info">
                                                    <i class="fas fa-undo"></i> Đã hoàn tiền
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-question-circle"></i> Không xác định
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $order->status_badge_class }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">
                                                @if(request('keyword'))
                                                    Không tìm thấy đơn hàng nào phù hợp với từ khóa "<strong>{{ request('keyword') }}</strong>"
                                                @else
                                                    Chưa có đơn hàng nào
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center">
                            {!! $orders->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection