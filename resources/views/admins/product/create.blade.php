@extends('admins.layouts.master')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Thêm sản phẩm</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
          <li class="breadcrumb-item active">Thêm</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
<div class="container-fluid">
  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                  <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Mô tả sản phẩm</label>
                  <textarea name="description" id="description" class="form-control"></textarea>
                </div>
              </div>

              <!-- Tab Biến thể -->
              <div class="tab-pane fade" id="variant">
                <div class="card card-outline card-info mb-3">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <label><strong>Chọn Size</strong></label>
                        <select id="size-select" class="form-control" multiple>
                          @foreach($sizes as $s)
                            <option value="{{ $s->id }}" data-name="{{ $s->name }}">{{ $s->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label><strong>Chọn Màu</strong></label>
                        <select id="color-select" class="form-control" multiple>
                          @foreach($colors as $c)
                            <option value="{{ $c->id }}" data-name="{{ $c->name }}">{{ $c->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <button type="button" id="generate-variants" class="btn btn-sm btn-primary mt-2">Tạo biến thể</button>
                  </div>
                </div>

                <div class="card card-outline card-secondary">
                  <div class="card-header"><strong>Danh sách biến thể</strong></div>
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
                      <tbody></tbody>
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
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Ảnh đại diện</label>
              <input type="file" name="image" id="main-image" class="form-control" accept="image/*">
              <div id="main-image-preview" class="mt-2"></div>
            </div>

            <div class="form-group">
              <label>Album ảnh</label>
              <input type="file" name="images[]" id="album-images" class="form-control" multiple accept="image/*">
              <div id="album-preview" class="row mt-2"></div>
            </div>

            <div class="form-group">
              <label>Trạng thái</label>
              <select name="status" class="form-control">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-success btn-block">Lưu sản phẩm</button>
      </div>
    </div>
  </form>
</div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  ClassicEditor.create(document.querySelector('#description')).catch(err => console.error(err));

  const sizeChoices  = new Choices("#size-select", { removeItemButton: true });
  const colorChoices = new Choices("#color-select", { removeItemButton: true });

  let variantIndex = 0;

  document.getElementById("generate-variants").addEventListener("click", function() {
    const sizes  = sizeChoices.getValue();
    const colors = colorChoices.getValue();

    if (sizes.length === 0 || colors.length === 0) {
      alert("Vui lòng chọn ít nhất 1 size và 1 màu!");
      return;
    }

    let tbody = document.querySelector("#variants-list tbody");
    tbody.innerHTML = "";

    sizes.forEach(size => {
      colors.forEach(color => {
        tbody.insertAdjacentHTML("beforeend", `
          <tr>
            <td>
              <input type="hidden" name="variants[${variantIndex}][size_id]" value="${size.value}">
              ${size.label}
            </td>
            <td>
              <input type="hidden" name="variants[${variantIndex}][color_id]" value="${color.value}">
              ${color.label}
            </td>
            <td><input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="Tự sinh nếu bỏ trống"></td>
            <td><input type="number" name="variants[${variantIndex}][price]" class="form-control"></td>
            <td><input type="number" name="variants[${variantIndex}][sale_price]" class="form-control"></td>
            <td><input type="number" name="variants[${variantIndex}][stock]" class="form-control"></td>
            <td><input type="file" name="variants[${variantIndex}][image]" class="form-control"></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-variant">X</button></td>
          </tr>
        `);
        variantIndex++;
      });
    });
  });

  document.querySelector("#variants-list").addEventListener("click", e => {
    if (e.target.classList.contains("remove-variant")) {
      e.target.closest("tr").remove();
    }
  });

  // Preview ảnh chính
  document.getElementById("main-image").addEventListener("change", function(e) {
    let preview = document.getElementById("main-image-preview");
    preview.innerHTML = "";
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = ev => {
        preview.innerHTML = `<img src="${ev.target.result}" style="width:100px;height:100px;object-fit:cover;">`;
      };
      reader.readAsDataURL(file);
    }
  });

  // Preview album
  document.getElementById("album-images").addEventListener("change", function(e) {
    let preview = document.getElementById("album-preview");
    preview.innerHTML = "";
    Array.from(e.target.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = ev => {
        let col = document.createElement("div");
        col.classList.add("col-4", "mb-2");
        col.innerHTML = `<img src="${ev.target.result}" style="width:100%;height:100px;object-fit:cover;">`;
        preview.appendChild(col);
      };
      reader.readAsDataURL(file);
    });
  });
});
</script>
@endpush
