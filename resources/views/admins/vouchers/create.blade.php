@extends('admins.layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tạo Voucher Mới</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
                    <li class="breadcrumb-item active">Tạo mới</li>
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
                        <h3 class="card-title">Thông tin voucher</h3>
                    </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger mx-3 mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.vouchers.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <!-- Voucher Name -->
                            <div class="form-group">
                                <label for="name">Tên Voucher <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Nhập tên voucher..."
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Voucher Code -->
                            <div class="form-group">
                                <label for="code">Mã Voucher <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code') }}" 
                                           placeholder="Nhập mã voucher hoặc nhấn 'Tạo mã'"
                                           required>
                                    <div class="input-group-append">
                                        <button type="button" 
                                                class="btn btn-info" 
                                                onclick="generateCode()">
                                            <i class="fas fa-magic"></i> Tạo mã
                                        </button>
                                    </div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Mã voucher phải là duy nhất và chứa ít nhất 6 ký tự</small>
                            </div>

                            <!-- Voucher Type -->
                            <div class="form-group">
                                <label for="type">Loại Voucher <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">-- Chọn loại voucher --</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
                                        Giảm giá cố định (VNĐ)
                                    </option>
                                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>
                                        Giảm giá theo phần trăm (%)
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sale Price and Min Order -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sale_price">Giá trị giảm <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   
                                                   class="form-control @error('sale_price') is-invalid @enderror" 
                                                   id="sale_price" 
                                                   name="sale_price" 
                                                   value="{{ old('sale_price') }}" 
                                                   placeholder="0"
                                                   required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="sale-price-unit">VNĐ</span>
                                            </div>
                                        </div>
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="min_order">Giá trị đơn hàng tối thiểu <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" 
                                                    
                                                   class="form-control @error('min_order') is-invalid @enderror" 
                                                   id="min_order" 
                                                   name="min_order" 
                                                   value="{{ old('min_order') }}" 
                                                   placeholder="0"
                                                   required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                        </div>
                                        @error('min_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Max Price and Quantity -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_price">Giá trị giảm tối đa <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" 
                                                  
                                                   class="form-control @error('max_price') is-invalid @enderror" 
                                                   id="max_price" 
                                                   name="max_price" 
                                                   value="{{ old('max_price') }}" 
                                                   placeholder="0"
                                                   required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">Áp dụng cho voucher giảm theo phần trăm</small>
                                        @error('max_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control @error('quantity') is-invalid @enderror" 
                                               id="quantity" 
                                               name="quantity" 
                                               value="{{ old('quantity', 1) }}" 
                                               min="1"
                                               placeholder="1"
                                               required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Start and End Date -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('start_date') is-invalid @enderror" 
                                               id="start_date" 
                                               name="start_date" 
                                               value="{{ old('start_date') }}" 
                                               required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control @error('end_date') is-invalid @enderror" 
                                               id="end_date" 
                                               name="end_date" 
                                               value="{{ old('end_date') }}" 
                                               required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo Voucher
                            </button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Voucher Preview Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Preview Voucher</h3>
                    </div>
                    <div class="card-body">
                        <div class="voucher-preview p-3 border rounded" style="background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                            <div class="text-center">
                                <h5 id="preview-name" class="text-primary mb-2">Tên voucher</h5>
                                <div class="voucher-code p-2 bg-primary text-white rounded mb-2">
                                    <strong id="preview-code">VOUCHERCODE</strong>
                                </div>
                                <div class="voucher-info">
                                    <p class="mb-1">
                                        <strong>Giảm:</strong> 
                                        <span id="preview-discount" class="text-success">0</span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Đơn tối thiểu:</strong> 
                                        <span id="preview-min" class="text-info">0 VNĐ</span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Số lượng:</strong> 
                                        <span id="preview-quantity">1</span>
                                    </p>
                                    <small class="text-muted">
                                        <span id="preview-dates">Chọn ngày</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hướng dẫn</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fas fa-info-circle text-info"></i> <strong>Giảm cố định:</strong> Giảm số tiền VNĐ cố định</li>
                            <li><i class="fas fa-percent text-success"></i> <strong>Giảm phần trăm:</strong> Giảm theo % giá trị đơn hàng</li>
                            <li><i class="fas fa-shopping-cart text-primary"></i> Đơn hàng phải đạt giá trị tối thiểu</li>
                            <li><i class="fas fa-clock text-warning"></i> Voucher chỉ có hiệu lực trong khoảng thời gian đã chọn</li>
                            <li><i class="fas fa-users text-danger"></i> Số lượng voucher có thể sử dụng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const salePriceUnit = document.getElementById('sale-price-unit');
    
    // Update preview and sale price unit when type changes
    typeSelect.addEventListener('change', function() {
        if (this.value === 'percent') {
            salePriceUnit.textContent = '%';
        } else {
            salePriceUnit.textContent = 'VNĐ';
        }
        updatePreview();
    });

    // Update preview when form values change
    const formInputs = ['name', 'code', 'type', 'sale_price', 'min_order', 'quantity', 'start_date', 'end_date'];
    formInputs.forEach(inputName => {
        const element = document.getElementById(inputName);
        if (element) {
            element.addEventListener('input', updatePreview);
        }
    });

    function updatePreview() {
        // Update name
        const name = document.getElementById('name').value || 'Tên voucher';
        document.getElementById('preview-name').textContent = name;

        // Update code
        const code = document.getElementById('code').value || 'VOUCHERCODE';
        document.getElementById('preview-code').textContent = code;

        // Update discount
        const type = document.getElementById('type').value;
        const salePrice = document.getElementById('sale_price').value || '0';
        let discountText = salePrice;
        if (type === 'percent') {
            discountText += '%';
        } else {
            discountText = new Intl.NumberFormat('vi-VN').format(salePrice) + ' VNĐ';
        }
        document.getElementById('preview-discount').textContent = discountText;

        // Update min order
        const minOrder = document.getElementById('min_order').value || '0';
        document.getElementById('preview-min').textContent = new Intl.NumberFormat('vi-VN').format(minOrder) + ' VNĐ';

        // Update quantity
        const quantity = document.getElementById('quantity').value || '1';
        document.getElementById('preview-quantity').textContent = quantity;

        // Update dates
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        let dateText = 'Chọn ngày';
        if (startDate && endDate) {
            const start = new Date(startDate).toLocaleDateString('vi-VN');
            const end = new Date(endDate).toLocaleDateString('vi-VN');
            dateText = `${start} - ${end}`;
        }
        document.getElementById('preview-dates').textContent = dateText;
    }

    // Set default datetime values
    const now = new Date();
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const nextMonth = new Date(now);
    nextMonth.setMonth(nextMonth.getMonth() + 1);

    if (!document.getElementById('start_date').value) {
        document.getElementById('start_date').value = tomorrow.toISOString().slice(0, 16);
    }
    if (!document.getElementById('end_date').value) {
        document.getElementById('end_date').value = nextMonth.toISOString().slice(0, 16);
    }

    updatePreview();
});

function generateCode() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
    
    fetch('{{ route('admin.vouchers.generateCode') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('code').value = data.code;
            // Update preview
            document.getElementById('preview-code').textContent = data.code;
            
            // Show success message
            const codeInput = document.getElementById('code');
            codeInput.classList.add('is-valid');
            setTimeout(() => {
                codeInput.classList.remove('is-valid');
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi khi tạo mã voucher');
        })
        .finally(() => {
            // Re-enable button
            button.disabled = false;
            button.innerHTML = originalText;
        });
}
</script>

<style>
.voucher-preview {
    border: 2px dashed #dee2e6 !important;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.voucher-code {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
    font-size: 1.1em;
}

.list-unstyled li {
    margin-bottom: 10px;
    padding-left: 5px;
}

.list-unstyled i {
    margin-right: 8px;
    width: 16px;
}

.is-valid {
    border-color: #28a745 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.71-.68L5.42 3.1l.71-.68L3.19 0 2.3 1.1 0 3.4l.71.68z'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
}

#sale-price-unit {
    min-width: 50px;
    justify-content: center;
}
</style>
@endsection