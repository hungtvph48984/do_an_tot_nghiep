@extends('admins.layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chỉnh sửa nhãn hàng</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Nhãn hàng</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin nhãn hàng</h3>
                    </div>
                    
                    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <!-- Brand Name -->
                            <div class="form-group">
                                <label for="name">Tên nhãn hàng <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $brand->name) }}" 
                                       placeholder="Nhập tên nhãn hàng..."
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Mô tả về nhãn hàng...">{{ old('description', $brand->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $brand->email) }}" 
                                               placeholder="email@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Số điện thoại</label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $brand->phone) }}" 
                                               placeholder="0123456789">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Website -->
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" 
                                       class="form-control @error('website') is-invalid @enderror" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website', $brand->website) }}" 
                                       placeholder="https://example.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Logo Display -->
                            @if($brand->logo)
                            <div class="form-group">
                                <label>Logo hiện tại</label>
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $brand->logo) }}" 
                                         alt="Current Logo" 
                                         class="img-thumbnail current-logo" 
                                         style="max-width: 150px; max-height: 150px;">
                                </div>
                            </div>
                            @endif

                            <!-- Logo Upload -->
                            <div class="form-group">
                                <label for="logo">{{ $brand->logo ? 'Thay đổi logo' : 'Logo nhãn hàng' }}</label>
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input @error('logo') is-invalid @enderror" 
                                           id="logo" 
                                           name="logo" 
                                           accept="image/*">
                                    <label class="custom-file-label" for="logo">Chọn file...</label>
                                </div>
                                <small class="form-text text-muted">
                                    Chấp nhận file: JPG, JPEG, PNG, GIF. Kích thước tối đa: 2MB
                                    @if($brand->logo)
                                        <br><em>Để trống nếu không muốn thay đổi logo hiện tại</em>
                                    @endif
                                </small>
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                
                                <!-- Logo Preview -->
                                <div id="logo-preview" class="mt-3" style="display: none;">
                                    <label>Preview logo mới:</label>
                                    <div>
                                        <img id="preview-image" src="" alt="Logo Preview" 
                                             class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="status" 
                                           name="status" 
                                           value="1" 
                                           {{ old('status', $brand->status) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Hoạt động</label>
                                </div>
                                <small class="text-muted">Kích hoạt để hiển thị nhãn hàng</small>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật nhãn hàng
                            </button>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Brand Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-calendar text-info"></i> <strong>Tạo:</strong> {{ $brand->created_at->format('d/m/Y H:i') }}</li>
                            <li><i class="fas fa-edit text-warning"></i> <strong>Cập nhật:</strong> {{ $brand->updated_at->format('d/m/Y H:i') }}</li>
                            <li><i class="fas fa-{{ $brand->status ? 'check-circle text-success' : 'times-circle text-danger' }}"></i> 
                                <strong>Trạng thái:</strong> {{ $brand->status ? 'Hoạt động' : 'Không hoạt động' }}
                            </li>
                            @if($brand->products_count ?? 0)
                            <li><i class="fas fa-box text-primary"></i> <strong>Sản phẩm:</strong> {{ $brand->products_count ?? 0 }}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hướng dẫn</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-info-circle text-info"></i> Tên nhãn hàng là bắt buộc</li>
                            <li><i class="fas fa-image text-primary"></i> Logo nên có kích thước vuông để hiển thị tốt nhất</li>
                            <li><i class="fas fa-globe text-success"></i> Website phải bắt đầu bằng http:// hoặc https://</li>
                            <li><i class="fas fa-envelope text-warning"></i> Email sẽ được sử dụng để liên hệ</li>
                            <li><i class="fas fa-exclamation-triangle text-danger"></i> Thay đổi trạng thái sẽ ảnh hưởng đến hiển thị</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File input label update
    const fileInput = document.getElementById('logo');
    const fileLabel = document.querySelector('.custom-file-label');
    const previewContainer = document.getElementById('logo-preview');
    const previewImage = document.getElementById('preview-image');

    fileInput.addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Chọn file...';
        fileLabel.textContent = fileName;

        // Show preview
        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            previewContainer.style.display = 'none';
        }
    });

    // Confirmation before leaving page with unsaved changes
    let formChanged = false;
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        const initialValue = input.value;
        input.addEventListener('input', function() {
            if (this.value !== initialValue) {
                formChanged = true;
            }
        });
    });

    // Warn before leaving if form has changes
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'Bạn có thay đổi chưa được lưu. Bạn có chắc chắn muốn rời khỏi trang?';
        }
    });

    // Reset formChanged when form is submitted
    form.addEventListener('submit', function() {
        formChanged = false;
    });
});
</script>

<style>
.custom-file-label::after {
    content: "Chọn";
}

.img-thumbnail {
    border-radius: 8px;
}

.current-logo {
    border: 2px solid #ddd;
}

.list-unstyled li {
    margin-bottom: 8px;
}

.list-unstyled i {
    margin-right: 8px;
}

#logo-preview {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px dashed #ddd;
}

.card-footer .btn {
    margin-right: 10px;
}
</style>
@endsection