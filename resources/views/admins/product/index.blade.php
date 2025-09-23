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
                        <a class="btn btn-info mr-3" href="{{ route('admin.products.create') }}">
                            <i class="fas fa-plus"></i> Thêm sản phẩm
                        </a>
                        <a class="btn btn-warning mr-3" href="{{ route('admin.products.hidden') }}">
                            <i class="fas fa-eye-slash"></i> Sản phẩm đã ẩn
                        </a>

                        <form action="{{ route('admin.products.index') }}" method="GET" class="form-inline ml-auto mb-0">
                            <input type="text" name="keyword" class="form-control mr-2" placeholder="Tìm sản phẩm..."
                                   value="{{ old('keyword', $keyword ?? '') }}">
                            <button type="submit" class="btn btn-secondary mr-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark" title="Làm mới">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </form>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tên</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th>Biến thể</th>
                                    <th>Trạng thái</th>
                                    <th>Tùy chỉnh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                <tr>
                                    {{-- Ảnh --}}
                                    <td>
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 style="width:50px; height:50px; object-fit:cover;">
                                        @else
                                            <span>Không có ảnh</span>
                                        @endif
                                    </td>

                                    {{-- Tên --}}
                                    <td>{{ $product->name }}</td>

                                    {{-- Danh mục --}}
                                    <td>{{ $product->category->name ?? 'Chưa có' }}</td>

                                    {{-- Giá --}}
                                    <td>
                                        @if ($product->variants->count() > 0)
                                            @if ($product->min_price == $product->max_price)
                                                {{ number_format($product->min_price, 0, ',', '.') }} VND
                                            @else
                                                {{ number_format($product->min_price, 0, ',', '.') }}
                                                – {{ number_format($product->max_price, 0, ',', '.') }} VND
                                            @endif
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

                                    {{-- Biến thể --}}
                                    <td>
                                        @if ($product->variants->count() > 0)
                                            <span data-toggle="tooltip"
                                                  data-html="true"
                                                  title="@foreach($product->variants as $v) Size: {{ $v->size->name ?? '-' }}, Màu: {{ $v->color->name ?? '-' }}, SL: {{ $v->stock ?? 0 }} <br> @endforeach"
                                                  style="cursor:pointer; color:#007bff; text-decoration:underline;">
                                                {{ $product->variants->count() }} biến thể
                                            </span>
                                        @else
                                            Không có
                                        @endif
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td>
                                        <form action="{{ route('admin.products.toggleStatus', $product->id) }}"
                                              method="POST" class="toggle-status-form">
                                            @csrf
                                            @method('PATCH')
                                            <div class="custom-control custom-switch d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input toggle-checkbox"
                                                       id="toggleStatus{{ $product->id }}" {{ $product->status ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="toggleStatus{{ $product->id }}"></label>
                                                <span class="ml-2 badge {{ $product->status ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $product->status ? 'Hiển thị' : 'Đã ẩn' }}
                                                </span>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- Hành động --}}
                                    <td class="text-center">
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
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

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-3">
                        {!! $products->appends(['keyword' => request('keyword')])->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const isActive = this.checked;
                const confirmMessage = isActive ?
                    'Bạn có chắc chắn muốn hiển thị sản phẩm này không?' :
                    'Bạn có chắc chắn muốn ẩn sản phẩm này không?';

                if (confirm(confirmMessage)) {
                    form.submit();
                } else {
                    this.checked = !this.checked;
                }
            });
        });
    });
</script>
@endpush
