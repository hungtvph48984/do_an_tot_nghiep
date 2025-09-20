@extends('admins.layouts.master')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1>Sản phẩm đã ẩn</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
          <li class="breadcrumb-item active">Đã ẩn</li>
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

  <div class="card">
    <div class="card-header">
      <a class="btn btn-secondary" href="{{ route('admin.products.index') }}"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Trạng thái</th>
            <th>Ngày</th>
            <th>Tùy chỉnh</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            <tr>
              <td>
                @if ($product->image)
                  <img src="{{ Storage::url($product->image) }}" style="width:50px;height:50px;object-fit:cover;">
                @else
                  Không có ảnh
                @endif
              </td>
              <td>{{ $product->name }}</td>
              <td>{{ $product->category->name ?? '-' }}</td>
              <td>
                @if ($product->variants->count() > 0)
                  {{ number_format($product->variants->min('price'), 0, ',', '.') }} VND
                @else
                  {{ number_format($product->price ?? 0, 0, ',', '.') }} VND
                @endif
              </td>
              <td>{{ $product->variants->sum('stock') }}</td>
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
              <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
              <td>
                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center">Không có sản phẩm ẩn nào.</td></tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-3">{{ $products->links() }}</div>
    </div>
  </div>
</div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const isActive = this.checked;
            const confirmMessage = isActive
                ? 'Bạn có chắc chắn muốn hiển thị sản phẩm này không?'
                : 'Bạn có chắc chắn muốn ẩn sản phẩm này không?';

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
