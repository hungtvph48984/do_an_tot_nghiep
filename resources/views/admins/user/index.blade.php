@extends('admins.layouts.master')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách người dùng </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">

                        <form action="{{ route('admin.user.index') }}" method="GET" class="form-inline ml-auto mb-0">
                            <input type="text" name="keyword" class="form-control mr-2" placeholder="Tìm kiếm..."
                                value="{{ old('keyword', $keyword ?? '') }}">
                            <button type="submit" class="btn btn-secondary mr-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-dark" title="Làm mới">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </form>

                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Chức vụ</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone ?? '—' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @elseif ($user->role === 'client')
                                                <span class="badge bg-primary">Khách</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $user->role }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->status ?? true)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Tạm khóa</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                                <i class=""></i> Xem
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $users->withQueryString()->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
