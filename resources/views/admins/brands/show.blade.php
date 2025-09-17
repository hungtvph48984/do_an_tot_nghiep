@extends('admins.layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết nhãn hàng: {{ $brand->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Nhãn hàng</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Brand Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin nhãn hàng</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Logo -->
                            <div class="col-md-3 text-center">
                                <img src="{{ $brand->logo_url }}" 
                                     alt="{{ $brand->name }}" 
                                     class="img-thumbnail"
                                     style="max-width: 150px; max-height: 150px; object-fit: cover;">
                            </div>
                            
                            <!-- Basic Info -->
                            <div class="col-md-9">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">Tên nhãn hàng:</th>
                                        <td>{{ $brand->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug:</th>
                                        <td><code>{{ $brand->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            @if($brand->status)
                                                <span class="badge badge-success">Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>
                                            @if($brand->email)
                                                <a href="mailto:{{ $brand->email }}">{{ $brand->email }}</a>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Điện thoại:</th>
                                        <td>
                                            @if($brand->phone)
                                                <a href="tel:{{ $brand->phone }}">{{ $brand->phone }}</a>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Website:</th>
                                        <td>
                                            @if($brand->website)
                                                <a href="{{ $brand->website }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i> Truy cập website
                                                </a>
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($brand->description)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Mô tả</h5>
                                <div class="alert alert-light">
                                    {!! nl2br(e($brand->description)) !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Products List -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Sản phẩm của nhãn hàng 
                            <span class="badge badge-primary">{{ $brand->products->count() }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($brand->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="80">Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Mã sản phẩm</th>
                                            <th width="100">Trạng thái</th>
                                            <th width="100">Danh mục</th>
                                            <th width="120">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($brand->products->take(10) as $product)
                                        <tr>
                                            <td class="text-center">
                                                <img src="{{ $product->image ?? 'https://via.placeholder.com/50x50' }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->description)
                                                    <br><small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                                @endif
                                            </td>
                                            <td><code>{{ $product->code }}</code></td>
                                            <td>
                                                @if($product->status)
                                                    <span class="badge badge-success">Hoạt động</span>
                                                @else
                                                    <span class="badge badge-danger">Ẩn</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->category)
                                                    <span class="badge badge-info">{{ $product->category->name }}</span>
                                                @else
                                                    <span class="text-muted">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.product.show', $product) ?? '#' }}" 
                                                       class="btn btn-info btn-sm" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.product.edit', $product) ?? '#' }}" 
                                                       class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($brand->products->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.product.index') }}?brand_id={{ $brand->id }}" 
                                       class="btn btn-outline-primary">
                                        Xem tất cả {{ $brand->products->count() }} sản phẩm
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có sản phẩm nào</h5>
                                <p class="text-muted">Nhãn hàng này chưa có sản phẩm nào được liên kết.</p>
                                <a href="{{ route('admin.products.create') ?? '#' }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm sản phẩm
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thống kê nhanh</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-shopping-bag"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tổng sản phẩm</span>
                                <span class="info-box-number">{{ $brand->products->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-eye"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sản phẩm hoạt động</span>
                                <span class="info-box-number">{{ $brand->products->where('status', 1)->count() }}</span>
                            </div>
                        </div>

                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sản phẩm ẩn</span>
                                <span class="info-box-number">{{ $brand->products->where('status', 0)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meta Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin meta</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>ID:</th>
                                <td>{{ $brand->id }}</td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật cuối:</th>
                                <td>{{ $brand->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Thời gian tồn tại:</th>
                                <td>{{ $brand->created_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hành động</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group-vertical btn-block">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            
                            @if($brand->products->count() == 0)
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa nhãn hàng này? Hành động này không thể hoàn tác!')">
                                        <i class="fas fa-trash"></i> Xóa nhãn hàng
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-danger btn-block" disabled title="Không thể xóa vì có sản phẩm liên kết">
                                    <i class="fas fa-trash"></i> Không thể xóa
                                </button>
                                <small class="text-muted">Có {{ $brand->products->count() }} sản phẩm liên kết</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.img-thumbnail {
    border-radius: 8px;
}

.info-box {
    margin-bottom: 15px;
}

.btn-group .btn {
    margin-right: 2px;
}

.table td, .table th {
    vertical-align: middle;
}

.alert-light {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}
</style>
@endsection