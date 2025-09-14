@extends('admins.layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Thêm nhãn hàng mới</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Nhãn hàng</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Form chính -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin nhãn hàng</h3>
                        </div>
                        <div class="card-body">
                            <!-- Brand Name -->
                            <div class="form-group">
                                <label for="name">Tên nhãn hàng <span class="text-danger">*</span></label>
                                <input type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="Nhập tên nhãn hàng..." 
                                    required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="4" 
                                    placeholder="Mô tả về nhãn hàng...">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email & Phone -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            id="email" 
                                            name="email" 
                                            value="{{ old('email') }}" 
                                            placeholder="email@example.com">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
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
                                            value="{{ old('phone') }}" 
                                            placeholder="0123456789">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
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
                                    value="{{ old('website') }}" 
                                    placeholder="https://example.com">
                                @error('website')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Logo Upload -->
                            <div class="form-group">
                                <label for="logo">Logo nhãn hàng</label>
                                <div class="custom-file">
                                    <input type="file" 
                                        class="custom-file-input @error('logo') is-invalid @enderror" 
                                        id="logo" 
                                        name="logo" 
                                        accept="image/*">
                                    <label class="custom-file-label" for="logo">Chọn file...</label>
                                </div>
                                <small class="form-text text-muted">Chấp nhận file: JPG, PNG, GIF. Tối đa 2MB.</small>
                                @error('logo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror

                                <!-- Preview -->
                                <div id="logo-preview" class="mt-3" style="display:none;">
                                    <img id="preview-image" src="" alt="Logo Preview" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Trạng thái -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Trạng thái & Cài đặt</h3>
                        </div>
                        <div class="card-body">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="status" 
                                    name="status" 
                                    value="1" 
                                    {{ old('status', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Hoạt động</label>
                            </div>
                            <small class="text-muted">Bật để hiển thị nhãn hàng</small>
                        </div>
                    </div>

                    <!-- Hướng dẫn -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Hướng dẫn</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-info-circle text-info"></i> Tên nhãn hàng là bắt buộc</li>
                                <li><i class="fas fa-image text-primary"></i> Logo nên có kích thước vuông</li>
                                <li><i class="fas fa-globe text-success"></i> Website phải bắt đầu bằng http/https</li>
                                <li><i class="fas fa-envelope text-warning"></i> Email sẽ được dùng để liên hệ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer buttons -->
            <div class="row">
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu nhãn hàng
                    </button>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Script preview logo --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('logo');
    const fileLabel = document.querySelector('.custom-file-label');
    const previewContainer = document.getElementById('logo-preview');
    const previewImage = document.getElementById('preview-image');

    fileInput.addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Chọn file...';
        fileLabel.textContent = fileName;

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
});
</script>
@endsection
