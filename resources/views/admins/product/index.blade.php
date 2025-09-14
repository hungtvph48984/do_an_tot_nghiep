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
                                        <th>Danh mục</th>
                                        <th>Giá thấp nhất</th>
                                        <th>Tồn kho</th>
                                        <th>Biến thể</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đăng</th>
                                        <th>Tùy chỉnh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            {{-- Ảnh --}}
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </td>

                                            {{-- Tên --}}
                                            <td>{{ $product->name }}</td>

                                            {{-- Danh mục --}}
                                            <td>{{ $product->category->name ?? 'Chưa có danh mục' }}</td>

                                            {{-- Giá thấp nhất --}}
                                            <td>
                                                @if ($product->variants->count() > 0)
                                                    {{ number_format($product->variants->min('price'), 0, ',', '.') }} VND
                                                @else
                                                    {{ number_format($product->price ?? 0, 0, ',', '.') }} VND
                                                @endif
                                            </td>

                                            {{-- Tồn kho --}}
                                            <td>
                                                @if ($product->variants->count() > 0)
                                                    {{ $product->variants->sum('stock') }}
                                                @else
                                                    0
                                                @endif
                                            </td>

                                            {{-- Biến thể với tooltip --}}
                                            <td>
                                                @if ($product->variants->count() > 0)
                                                    <span 
                                                        data-toggle="tooltip" 
                                                        data-html="true"
                                                        title="
                                                            @foreach($product->variants as $variant)
                                                                Size: {{ $variant->size->name ?? '-' }},
                                                                Màu: {{ $variant->color->name ?? '-' }},
                                                                SL: {{ $variant->stock ?? 0 }} <br>
                                                            @endforeach
                                                        "
                                                        style="cursor: pointer; color: #007bff; text-decoration: underline;">
                                                        {{ $product->variants->count() }} biến thể
                                                    </span>
                                                @else
                                                    <span>Không có</span>
                                                @endif
                                            </td>

                                            {{-- Trạng thái --}}
                                            <td>
                                                @if ($product->status == 1)
                                                    <span class="badge bg-success">Hiển thị</span>
                                                @else
                                                    <span class="badge bg-secondary">Ẩn</span>
                                                @endif
                                            </td>

                                            {{-- Ngày --}}
                                            <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>

                                            {{-- Hành động --}}
                                            <td>
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info">Xem</a>
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
                                            <td colspan="9" class="text-center">Không có sản phẩm nào.</td>
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

        // Kích hoạt tooltip
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
