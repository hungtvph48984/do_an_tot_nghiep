@extends('admins.layouts.master')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Quản lý Thuộc tính</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="attrTabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="sizes-tab" data-toggle="tab" href="#sizes" role="tab">Size</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="colors-tab" data-toggle="tab" href="#colors" role="tab">Màu</a>
      </li>
    </ul>

    <div class="tab-content mt-3">
      <!-- Tab Size -->
      <div class="tab-pane fade show active" id="sizes" role="tabpanel">
        <form action="{{ route('admin.attributes.sizes.store') }}" method="POST" class="form-inline mb-3">
          @csrf
          <input type="text" name="name" class="form-control mr-2" placeholder="Nhập tên size (VD: S, M, L)" required>
          <button type="submit" class="btn btn-primary">Thêm Size</button>
        </form>

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên Size</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sizes as $size)
              <tr>
                <td>{{ $size->id }}</td>
                <td>{{ $size->name }}</td>
                <td>
                  <!-- Sửa -->
                  <form action="{{ route('admin.attributes.sizes.update',$size->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $size->name }}" class="form-control d-inline w-25">
                    <button type="submit" class="btn btn-sm btn-success">Sửa</button>
                  </form>

                  <!-- Xóa -->
                  <form action="{{ route('admin.attributes.sizes.destroy',$size->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {{ $sizes->links() }}
      </div>

      <!-- Tab Màu -->
      <div class="tab-pane fade" id="colors" role="tabpanel">
        <form action="{{ route('admin.attributes.colors.store') }}" method="POST" class="form-inline mb-3">
          @csrf
          <input type="text" name="name" class="form-control mr-2" placeholder="Tên màu (VD: Đỏ, Xanh...)" required>
          <input type="color" name="code" class="form-control mr-2" value="#000000" required>
          <button type="submit" class="btn btn-primary">Thêm Màu</button>
        </form>

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên Màu</th>
              <th>Mã Màu</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach($colors as $color)
              <tr>
                <td>{{ $color->id }}</td>
                <td>{{ $color->name }}</td>
                <td>
                  <span style="display:inline-block;width:20px;height:20px;background:{{ $color->code }};border:1px solid #ccc"></span>
                  {{ $color->code }}
                </td>
                <td>
                  <!-- Sửa -->
                  <form action="{{ route('admin.attributes.colors.update',$color->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $color->name }}" class="form-control d-inline w-25">
                    <input type="color" name="code" value="{{ $color->code }}" class="form-control d-inline w-25">
                    <button type="submit" class="btn btn-sm btn-success">Sửa</button>
                  </form>

                  <!-- Xóa -->
                  <form action="{{ route('admin.attributes.colors.destroy',$color->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        {{ $colors->links() }}
      </div>
    </div>
  </div>
</section>
@endsection
