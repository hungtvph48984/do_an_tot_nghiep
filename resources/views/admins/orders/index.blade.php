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
                            <!-- Keyword chung -->
                            <div class="col-md-4">
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo Mã ĐH, tên, email, SĐT..." value="{{ request('keyword') }}">
                            </div>

                            <!-- Ngày tạo từ → đến -->
                            <div class="col-md-2">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>

                            <!-- Sắp xếp theo giá -->
                            <div class="col-md-2">
                                <select name="price_order" class="form-control">
                                    <option value="">-- Mặc định --</option>
                                    <option value="asc" {{ request('price_order') == 'asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                                    <option value="desc" {{ request('price_order') == 'desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                                </select>
                            </div>

                            <!-- Nút tìm kiếm + Reset -->
                            <div class="col-md-2 d-flex">
                                <button class="btn btn-primary flex-grow-1 mr-1" type="submit">
                                    <i class="fas fa-search"></i> Tìm kiếm
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
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Tổng tiền</th>
                                   
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->phone }}</td>
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
                                        <td colspan="13" class="text-center py-4">
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

                    <!-- Phân trang -->
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
