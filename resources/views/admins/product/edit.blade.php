@extends('admins.layouts.master')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Sửa sản phẩm</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
          <li class="breadcrumb-item active">Sửa</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <!-- Cột chính -->
        <div class="col-md-8">
          <div class="card shadow-sm">
            <div class="card-body">

              <!-- Tên -->
              <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
              </div>

              <!-- Danh mục -->
              <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select name="category_id" class="form-control" required>
                  <option value="">-- Chọn danh mục --</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                      {{ $cat->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Mô tả -->
              <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
              </div>

              <!-- Trạng thái -->
              <div class="form-group">
                <label>Trạng thái</label><br>
                <label><input type="radio" name="status" value="1" {{ $product->status ? 'checked' : '' }}> Hiển thị</label>
                <label class="ml-3"><input type="radio" name="status" value="0" {{ !$product->status ? 'checked' : '' }}> Ẩn</label>
              </div>

            </div>
          </div>

          <!-- Biến thể -->
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="card-title">Biến thể</h5>
              <button type="button" class="btn btn-sm btn-success" id="addVariant">+ Thêm biến thể</button>
            </div>
            <div class="card-body p-0">
              <table class="table table-bordered mb-0" id="variantsTable">
                <thead class="thead-light">
                  <tr>
                    <th>Size</th>
                    <th>Màu</th>
                    <th>SKU</th>
                    <th>Giá</th>
                    <th>Giá KM</th>
                    <th>Kho</th>
                    <th>Ảnh</th>
                    <th>Xóa</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($product->variants as $i => $variant)
                  <tr>
                    <td>
                      <select name="variants[{{ $i }}][size_id]" class="form-control">
                        <option value="">--</option>
                        @foreach($sizes as $size)
                          <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <select name="variants[{{ $i }}][color_id]" class="form-control">
                        <option value="">--</option>
                        @foreach($colors as $color)
                          <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="text" name="variants[{{ $i }}][sku]" class="form-control" value="{{ $variant->sku }}">
                    </td>
                    <td>
                      <input type="number" step="0.01" name="variants[{{ $i }}][price]" class="form-control" value="{{ $variant->price }}">
                    </td>
                    <td>
                      <input type="number" step="0.01" name="variants[{{ $i }}][sale_price]" class="form-control" value="{{ $variant->sale_price }}">
                    </td>
                    <td>
                      <input type="number" name="variants[{{ $i }}][stock]" class="form-control" value="{{ $variant->stock }}">
                    </td>
                    <td>
                      @if ($variant->image)
                        <img src="{{ Storage::url($variant->image) }}" style="height:40px;" class="mb-1"><br>
                      @endif
                      <input type="file" name="variants[{{ $i }}][image]" class="form-control-file">
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-danger remove-variant">✖</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>

        <!-- Cột bên -->
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <!-- Ảnh đại diện -->
              <div class="form-group">
                <label>Ảnh đại diện</label><br>
                @if ($product->image)
                  <img src="{{ Storage::url($product->image) }}" style="height:80px;" class="mb-2"><br>
                @endif
                <input type="file" name="image" class="form-control-file">
              </div>

              <!-- Album ảnh -->
              <div class="form-group">
                <label>Album ảnh</label><br>
                @if ($product->album_urls && count($product->album_urls) > 0)
                  <div class="mb-2 d-flex flex-wrap">
                    @foreach($product->album_urls as $url)
                      <img src="{{ $url }}" style="height:60px; margin:2px;">
                    @endforeach
                  </div>
                @endif
                <input type="file" name="images[]" class="form-control-file" multiple>
              </div>

              <!-- Nút lưu -->
              <button type="submit" class="btn btn-primary btn-block">💾 Cập nhật</button>
              <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block">← Quay lại</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  let variantIndex = {{ $product->variants->count() }};

  document.getElementById('addVariant').addEventListener('click', function () {
    const table = document.querySelector('#variantsTable tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <select name="variants[${variantIndex}][size_id]" class="form-control">
          <option value="">--</option>
          @foreach($sizes as $size)
            <option value="{{ $size->id }}">{{ $size->name }}</option>
          @endforeach
        </select>
      </td>
      <td>
        <select name="variants[${variantIndex}][color_id]" class="form-control">
          <option value="">--</option>
          @foreach($colors as $color)
            <option value="{{ $color->id }}">{{ $color->name }}</option>
          @endforeach
        </select>
      </td>
      <td><input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="Tự sinh nếu bỏ trống"></td>
      <td><input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control"></td>
      <td><input type="number" step="0.01" name="variants[${variantIndex}][sale_price]" class="form-control"></td>
      <td><input type="number" name="variants[${variantIndex}][stock]" class="form-control"></td>
      <td><input type="file" name="variants[${variantIndex}][image]" class="form-control-file"></td>
      <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-variant">✖</button></td>
    `;
    table.appendChild(row);
    variantIndex++;
  });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-variant')) {
      e.target.closest('tr').remove();
    }
  });
});
</script>
@endpush
