@extends('admins.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách sản phẩm</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
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
                        <div class="card-header">
                            <a class="btn btn-info" href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Chuyên mục</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đăng</th>
                                        <th>Tùy chỉnh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                @if ($product->images && count($product->images) > 0)
                                                    <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'Chưa có chuyên mục' }}</td> <!-- Giả định có relationship với Category -->
                                            <td>{{ number_format($product->price, 2) }} VND</td>
                                            <td>{{ $product->variants->sum('stock') ?? 0 }}</td> <!-- Tổng số lượng từ các biến thể -->
                                            <td>
                                                @if ($product->variants->sum('stock') > 0)
                                                    <span class="badge bg-success">Còn hàng</span>
                                                @else
                                                    <span class="badge bg-danger">Hết hàng</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Không có sản phẩm nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Vietnamese.json"
                }
            });
        });
    </script>
@endpush