@extends('clients.layouts.master')
@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <!-- <div class="col-md-3 mb-4">
            <div class="card shadow-sm text-center p-3">
                <img src="{{ asset('images/avatar-default.png') }}" class="rounded-circle mb-3" width="120" alt="Avatar">
                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted">{{ Auth::user()->email }}</p>
                <a href="{{ route('client.logout') }}" class="btn btn-outline-danger btn-sm mt-2"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                <form id="logout-form" action="{{ route('client.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div> -->

        <!-- Main Content -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Trang cá nhân</h5>
                </div>
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == '' || request('tab') == 'info' ? 'active' : '' }}" data-bs-toggle="tab" href="#info">Thông tin cá nhân</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'password' ? 'active' : '' }}" data-bs-toggle="tab" href="#password">Đổi mật khẩu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'orders' ? 'active' : '' }}" data-bs-toggle="tab" href="#orders">Lịch sử đơn hàng</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">

                        <!-- Personal Info -->
                        <div class="tab-pane fade {{ request('tab') == '' || request('tab') == 'info' ? 'show active' : '' }}" id="info">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="mb-3 text-primary"><i class="bi bi-person-lines-fill"></i> Cập nhật thông tin cá nhân</h5>

                                    <form method="POST" action="{{ route('profile.update') }}">
                                        @csrf
                                        <div class="row g-3">
                                            <!-- Họ tên -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Họ tên</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ Auth::user()->name }}" placeholder="Nhập họ tên của bạn">
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                    <input type="email" class="form-control"
                                                        value="{{ Auth::user()->email }}" readonly>
                                                </div>
                                            </div>

                                            <!-- Số điện thoại -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Số điện thoại</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ Auth::user()->phone }}" placeholder="Nhập số điện thoại">
                                                </div>
                                            </div>

                                            <!-- Địa chỉ -->
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Địa chỉ</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                    <input type="text" class="form-control" name="address"
                                                        value="{{ Auth::user()->address }}" placeholder="Nhập địa chỉ của bạn">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="bi bi-save"></i> Lưu thay đổi
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary px-4 ms-2">
                                                <i class="bi bi-arrow-counterclockwise"></i> Nhập lại
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="tab-pane fade {{ request('tab') == 'password' ? 'show active' : '' }}" id="password">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                <div class="mb-3">
                                    <label>Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control" name="current_password">
                                </div>
                                <div class="mb-3">
                                    <label>Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="new_password">
                                </div>
                                <div class="mb-3">
                                    <label>Nhập lại mật khẩu mới</label>
                                    <input type="password" class="form-control" name="new_password_confirmation">
                                </div>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save"></i> Lưu thay đổi
                                </button>
                            </form>
                        </div>

                        <!-- Order History -->
                        <div class="tab-pane fade {{ request('tab') == 'orders' ? 'show active' : '' }}" id="orders">
                            <div class="mb-3">
                                <form method="GET" action="{{ route('profile.show') }}" class="row g-2 align-items-end">
                                    <input type="hidden" name="tab" value="orders">

                                    <!-- Tìm kiếm -->
                                    <div class="col-md-3">
                                        <label class="form-label">Tìm kiếm</label>
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}"
                                            placeholder="Nhập mã đơn hàng...">
                                    </div>

                                    <!-- Lọc theo ngày -->
                                    <div class="col-md-3">
                                        <label class="form-label">Từ ngày</label>
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Đến ngày</label>
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ request('date_to') }}">
                                    </div>

                                    <!-- Lọc theo giá -->
                                    <div class="col-md-2">
                                        <label class="form-label">Sắp xếp giá</label>
                                        <select name="sort_price" class="form-select">
                                            <option value="">Mặc định</option>
                                            <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Thấp → Cao</option>
                                            <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Cao → Thấp</option>
                                        </select>
                                    </div>
                                    <!-- Nút -->
                                    <div class="col-md-3 d-flex align-items-end gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="bi bi-search"></i> Tìm
                                        </button>
                                        <a href="{{ route('profile.show', ['tab' => 'orders']) }}" class="btn btn-secondary flex-fill">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </a>
                                    </div>

                                </form>
                            </div>

                            <!-- Bảng đơn hàng -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $index => $order)
                                            <tr>
                                                <td class="text-center">
                                                    {{ method_exists($orders, 'firstItem')
                                                        ? $orders->firstItem() + $index
                                                        : $loop->iteration }}
                                                </td>
                                                <td class="text-center fw-bold">#{{ $order->id }}</td>
                                                <td class="text-center">{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td class="fw-bold text-danger">
                                                    {{ number_format($order->total, 0, ',', '.') }}đ
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = match($order->status) {
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger',
                                                            default => 'secondary'
                                                        };
                                                        $statusText = match($order->status) {
                                                            'pending' => 'Chờ xử lý',
                                                            'processing' => 'Đang giao',
                                                            'completed' => 'Hoàn tất',
                                                            'cancelled' => 'Đã hủy',
                                                            default => ucfirst($order->status)
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Xem chi tiết đơn hàng #{{ $order->id }}">
                                                        <i class="bi bi-eye"></i> Xem
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                    Chưa có đơn hàng nào
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center">
                                {{ $orders->appends(request()->query())->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
