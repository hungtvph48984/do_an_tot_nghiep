@extends('admins.layouts.master')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh mục sản phẩm</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Danh mục</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <a class="btn btn-info mr-4 " href="{{ route('admin.categories.create') }}">Thêm danh mục</a>
                        <a class="btn btn-warning" href="{{ route('admin.categories.hidden') }}">Danh mục đã ẩn</a>

                        <form action="{{ route('admin.categories.index') }}" method="GET" class="form-inline ml-auto mb-0">
                            <input type="text" name="keyword" class="form-control mr-2" placeholder="Tìm danh mục..."
                                value="{{ old('keyword', $keyword ?? '') }}">
                            <button type="submit" class="btn btn-secondary mr-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark" title="Làm mới">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </form> 
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tên danh mục</th>
                                    <th>Ghi chú</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            <form action="{{ route('admin.categories.toggleStatus', $category->id) }}"
                                                method="POST"
                                                class="toggle-status-form">
                                                @csrf
                                                @method('PATCH')
                                                <div class="custom-control custom-switch d-flex align-items-center">
                                                    <input type="checkbox"
                                                        class="custom-control-input toggle-checkbox"
                                                        id="toggleStatus{{ $category->id }}"
                                                        {{ $category->is_active ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="toggleStatus{{ $category->id }}"></label>

                                                    <span class="ml-2 badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                        {{ $category->is_active ? 'Hiển thị' : 'Đã ẩn' }}
                                                    </span>
                                                </div>
                                            </form>
                                        </td>

                                        
                                        <td>
                                            <a class="btn btn-warning" href="{{ route('admin.categories.edit', $category->id) }}">Xem</a>
                                            <a class="btn btn-primary" href="{{ route('admin.categories.edit', $category->id) }}">Sửa</a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xoá danh mục này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $categories->appends(['keyword' => request('keyword')])->links() }}
                        </div>
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Tự động ẩn alert sau 3 giây
    setTimeout(function () {
        const alertEl = document.querySelector('.alert');
        if (alertEl) {
            alertEl.classList.remove('show');
            alertEl.classList.add('fade');
            setTimeout(() => alertEl.remove(), 500);
        }
    }, 3000);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function (e) {
                e.preventDefault(); // Ngăn gửi form tự động

                const form = this.closest('form');
                const isActive = this.checked;
                const confirmMessage = isActive
                    ? 'Bạn có chắc chắn muốn hiển thị mục này không?'
                    : 'Bạn có chắc chắn muốn ẩn mục này không?';

                if (confirm(confirmMessage)) {
                    form.submit(); // submit nếu người dùng đồng ý
                } else {
                    // Nếu huỷ thì không ẩn 
                    this.checked = !this.checked;
                }
            });
        });
    });
</script>
@endsection
