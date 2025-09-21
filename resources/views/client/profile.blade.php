@extends('client.layout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm text-center p-3">
                <img src="{{ asset('images/avatar-default.png') }}" class="rounded-circle mb-3" width="120" alt="Avatar">
                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted">{{ Auth::user()->email }}</p>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm mt-2">Đăng xuất</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Trang cá nhân</h5>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#info">Thông tin cá nhân</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password">Đổi mật khẩu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#orders">Lịch sử đơn hàng</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Personal Info -->
                        <div class="tab-pane active" id="info">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Họ tên</label>
                                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" value="{{ Auth::user()->address }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                            </form>
                        </div>

                        <!-- Change Password -->
                        <div class="tab-pane fade" id="password">
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
                                <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
                            </form>
                        </div>

                        <!-- Order History -->
                        <div class="tab-pane fade" id="orders">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                                <td>{{ $order->status }}</td>
                                                <td><a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-info">Xem</a></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Không có đơn hàng nào</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- End tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
