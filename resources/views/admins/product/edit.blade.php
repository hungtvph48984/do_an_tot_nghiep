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
      <!-- Cột trái -->
      <div class="col-md-9">
        <div class="card card-outline card-info">
          <div class="card-body">
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general">Thông tin chung</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#variant">Biến thể</a>
              </li>
            </ul>

            <div class="tab-content mt-3">
              <!-- Tab Thông tin chung -->
              <div class="tab-pane fade show active" id="general">
                <div class="form-group">
                  <label>Tên sản phẩm</label>
                  <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
                <div class="form-group">
                  <label>Mô tả sản phẩm</label>
                  <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                </div>
              </div>

              <!-- Tab Biến thể -->
              <div class="tab-pane fade" id="variant">
                <div class="card card-outline card-secondary">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Danh sách biến thể</strong>
                    <button type="button" class="btn btn-sm btn-success" id="add-variant">
                      + Thêm biến thể
                    </button>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-bordered mb-0" id="variants-list">
                      <thead>
                        <tr>
                          <th>Size</th>
                          <th>Màu</th>
                          <th>SKU</th>
                          <th>Giá</th>
                          <th>Giá KM</th>
                          <th>Kho</th>
                          <th>Ảnh</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($product->variants as $i => $variant)
                          <tr>
                            <td>
                              <select name="variants[{{ $i }}][size_id]" class="form-control">
                                <option value="">-- Size --</option>
                                @foreach($sizes as $s)
                                  <option value="{{ $s->id }}" {{ $variant->size_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                  </option>
                                @endforeach
                              </select>
                            </td>
                            <td>
                              <select name="variants[{{ $i }}][color_id]" class="form-control">
                                <option value="">-- Màu --</option>
                                @foreach($colors as $c)
                                  <option value="{{ $c->id }}" {{ $variant->color_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                  </option>
                                @endforeach
                              </select>
                            </td>
                            <td><input type="text" name="variants[{{ $i }}][sku]" class="form-control" value="{{ $variant->sku }}"></td>
                            <td><input type="number" name="variants[{{ $i }}][price]" class="form-control" step="0.01" value="{{ $variant->price }}"></td>
                            <td><input type="number" name="variants[{{ $i }}][sale_price]" class="form-control" step="0.01" value="{{ $variant->sale_price }}"></td>
                            <td><input type="number" name="variants[{{ $i }}][stock]" class="form-control" value="{{ $variant->stock }}"></td>
                            <td>
                              @if($variant->image)
                                <div class="mb-2">
                                  <img src="{{ Storage::url($variant->image) }}" style="width:50px; height:50px; object-fit:cover;">
                                </div>
                              @endif
                              <input type="file" name="variants[{{ $i }}][image]" class="form-control">
                            </td>
                            <td><button type="button" class="btn btn-sm btn-danger remove-variant">X</button></td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> <!-- /.tab-content -->
          </div>
        </div>
      </div>

      <!-- Cột phải -->
      <div class="col-md-3">
        <div class="card card-outline card-info">
          <div class="card-body">
            <div class="form-group">
              <label>Danh mục</label>
              <select name="category_id" class="form-control" required>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Ảnh đại diện</label>
              @if($product->image)
                <div class="mb-2">
                  <img src="{{ Storage::url($product->image) }}" style="width:100px; height:100px; object-fit:cover;">
                </div>
              @endif
              <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
              <label>Album ảnh</label>
              @if($product->images)
                <div class="row mb-2">
                  @foreach(json_decode($product->images, true) as $img)
                    <div class="col-4">
                      <img src="{{ Storage::url($img) }}" style="width:100%; height:auto; object-fit:cover;">
                    </div>
                  @endforeach
                </div>
              @endif
              <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            </div>

            <div class="form-group">
              <label>Giá sản phẩm</label>
              <input type="number" name="price" class="form-control" step="0.01" value="{{ $product->price }}">
            </div>

            <div class="form-group">
              <label>Trạng thái</label>
              <select name="status" class="form-control">
                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Ẩn</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-success btn-block">Cập nhật sản phẩm</button>
      </div>
    </div>
  </form>
</div>
</section>
@endsection

@push('scripts')
<script>
  let variantIndex = {{ $product->variants->count() }};

  // Nút thêm biến thể
  document.getElementById("add-variant").addEventListener("click", function () {
    let tbody = document.querySelector("#variants-list tbody");
    tbody.insertAdjacentHTML("beforeend", `
      <tr>
        <td>
          <select name="variants[${variantIndex}][size_id]" class="form-control">
            <option value="">-- Size --</option>
            @foreach($sizes as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="variants[${variantIndex}][color_id]" class="form-control">
            <option value="">-- Màu --</option>
            @foreach($colors as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </td>
        <td><input type="text" name="variants[${variantIndex}][sku]" class="form-control"></td>
        <td><input type="number" name="variants[${variantIndex}][price]" class="form-control" step="0.01"></td>
        <td><input type="number" name="variants[${variantIndex}][sale_price]" class="form-control" step="0.01"></td>
        <td><input type="number" name="variants[${variantIndex}][stock]" class="form-control"></td>
        <td><input type="file" name="variants[${variantIndex}][image]" class="form-control"></td>
        <td><button type="button" class="btn btn-sm btn-danger remove-variant">X</button></td>
      </tr>
    `);
    variantIndex++;
  });

  // Xoá biến thể
  document.querySelector("#variants-list").addEventListener("click", e => {
    if (e.target.classList.contains("remove-variant")) {
      e.target.closest("tr").remove();
    }
  });
</script>
@endpush
