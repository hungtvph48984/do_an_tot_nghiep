@extends('admins.layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Nhãn hàng</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Nhãn hàng</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Danh sách nhãn hàng</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm nhãn hàng
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.brands.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm kiếm theo tên, email, phone..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-control">
                                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Ngày tạo mới nhất</option>
                                <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Ngày tạo cũ nhất</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="action" class="form-control">
                                <option value="">Chọn hành động</option>
                                <option value="activate">Kích hoạt</option>
                                <option value="deactivate">Vô hiệu hóa</option>
                                <option value="delete">Xóa</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="bulk_action" class="btn btn-warning">
                                Thực hiện
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Brands Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th width="80">Logo</th>
                                <th>Tên nhãn hàng</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Website</th>
                                <th width="100">Trạng thái</th>
                                <th width="80">Sản phẩm</th>
                                <th width="120">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td>
                                    <input type="checkbox" name="brand_ids[]" value="{{ $brand->id }}">
                                </td>
                                <td class="text-center">
                                    @if($brand->logo)
                                        <img src="{{ Storage::url($brand->logo) }}" 
                                             alt="{{ $brand->name }}" 
                                             class="brand-logo img-thumbnail"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='No Image';">
                                    @else
                                        <div class="no-logo-placeholder">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $brand->name }}</strong>
                                    @if($brand->description)
                                        <br><small class="text-muted">{{ Str::limit($brand->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($brand->email)
                                        <a href="mailto:{{ $brand->email }}">{{ $brand->email }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($brand->phone)
                                        <a href="tel:{{ $brand->phone }}">{{ $brand->phone }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($brand->website)
                                        <a href="{{ $brand->website }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($brand->status)
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-secondary">Không hoạt động</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ $brand->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" 
                                           class="btn btn-sm btn-info" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Xóa"
                                                onclick="confirmDelete({{ $brand->id }}, '{{ $brand->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Chưa có nhãn hàng nào</h5>
                                        <p class="text-muted">Hãy thêm nhãn hàng đầu tiên của bạn</p>
                                        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Thêm nhãn hàng
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($brands->hasPages())
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Hiển thị {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} 
                            trong tổng số {{ $brands->total() }} nhãn hàng
                        </p>
                    </div>
                    <div class="col-md-6">
                        {{ $brands->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa nhãn hàng <strong id="brandName"></strong>?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form method="POST" id="deleteForm" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Select all checkbox
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="brand_ids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Delete confirmation
function confirmDelete(brandId, brandName) {
    document.getElementById('brandName').textContent = brandName;
    document.getElementById('deleteForm').action = `/admin/brands/${brandId}`;
    $('#deleteModal').modal('show');
}

// Success/Error messages auto hide
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>

<style>
.brand-logo {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.no-logo-placeholder {
    width: 50px;
    height: 50px;
    background: #f4f4f4;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.empty-state {
    padding: 2rem;
}

.btn-group .btn {
    margin: 0 1px;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 1px 0;
    }
}
</style>
@endsection