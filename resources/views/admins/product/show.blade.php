@extends('admins.layouts.master')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Chi tiết sản phẩm</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
          <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Thông tin sản phẩm -->
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="row">
              <!-- Ảnh đại diện -->
              <div class="col-md-4 text-center">
                @if ($product->image)
                  <img src="{{ Storage::url($product->image) }}"
                       alt="{{ $product->name }}"
                       class="img-fluid rounded mb-2"
                       style="max-height: 220px; object-fit:cover;">
                @else
                  <span class="text-muted">Không có ảnh</span>
                @endif
              </div>

              <!-- Thông tin -->
              <div class="col-md-8">
                <h4 class="mb-3">{{ $product->name }}</h4>
                <p><strong>Mã SP:</strong> {{ $product->code }}</p>
                <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa có' }}</p>
                <p><strong>Thương hiệu:</strong> {{ $product->brand ? $product->brand->name : 'Chưa có' }}</p>
                <p><strong>Trạng thái:</strong>
                  @if ($product->status == 1)
                    <span class="badge badge-success">Hiển thị</span>
                  @else
                    <span class="badge badge-secondary">Ẩn</span>
                  @endif
                </p>
                <p><strong>Mô tả:</strong></p>
                <div class="border rounded p-2 bg-light" style="max-height: 120px; overflow-y:auto;">
                  {!! $product->description !!}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Album -->
        @if ($product->album_urls && count($product->album_urls) > 0)
        <div class="card mt-3">
          <div class="card-header">
            <h5 class="card-title">Album ảnh</h5>
          </div>
          <div class="card-body">
            <div class="row">
              @foreach ($product->album_urls as $url)
              <div class="col-3 mb-2">
                <img src="{{ $url }}" class="img-thumbnail" style="width:100%; height:150px; object-fit:cover;">
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        <!-- Biến thể -->
        <div class="card mt-3">
          <div class="card-header">
            <h5 class="card-title">Danh sách biến thể</h5>
          </div>
          <div class="card-body p-0">
            @if ($product->variants->count() > 0)
            <table class="table table-striped mb-0">
              <thead class="thead-light">
                <tr>
                  <th>Size</th>
                  <th>Màu</th>
                  <th>SKU</th>
                  <th>Giá</th>
                  <th>Giá KM</th>
                  <th>Kho</th>
                  <th>Ảnh</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($product->variants as $variant)
                <tr>
                  <td>{{ $variant->size->name ?? '-' }}</td>
                  <td>{{ $variant->color->name ?? '-' }}</td>
                  <td>{{ $variant->sku ?? '-' }}</td>
                  <td>
                    @if ($variant->sale_price > 0)
                      <del class="text-muted">{{ number_format($variant->price, 0, ',', '.') }} VND</del>
                    @else
                      {{ number_format($variant->price, 0, ',', '.') }} VND
                    @endif
                  </td>
                  <td>
                    @if ($variant->sale_price > 0)
                      <strong class="text-danger">{{ number_format($variant->sale_price, 0, ',', '.') }} VND</strong>
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ $variant->stock ?? 0 }}</td>
                  <td>
                    @if ($variant->image)
                      <img src="{{ Storage::url($variant->image) }}"
                           class="img-thumbnail"
                           style="width:50px; height:50px; object-fit:cover;">
                    @else
                      -
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <div class="p-3 text-muted">Không có biến thể nào.</div>
            @endif
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block mb-2">← Quay lại</a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-block mb-2">✎ Sửa sản phẩm</a>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-block">🗑 Xóa sản phẩm</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
  