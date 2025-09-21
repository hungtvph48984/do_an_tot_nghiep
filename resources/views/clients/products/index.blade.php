@extends('clients.layouts.master')

@section('title', 'Tìm kiếm sản phẩm')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold">Kết quả tìm kiếm cho: "{{ $searchTerm }}"</h4>

    <!-- Bộ lọc tìm kiếm -->
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 position-sticky" style="top:20px; z-index:1020;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Bộ lọc</h5>
                    <form method="GET" action="{{ route('product.search') }}" class="filter-form">
                        {{-- Giá --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Khoảng giá (VNĐ)</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                    class="form-control form-control-sm" placeholder="Từ">
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                    class="form-control form-control-sm" placeholder="Đến">
                            </div>
                        </div>

                        {{-- Size --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Size</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                    <label class="btn btn-outline-secondary btn-sm rounded-pill size-label 
                                        {{ in_array($size->id, (array)request('sizes')) ? 'active' : '' }}">
                                        <input type="checkbox" class="d-none" name="sizes[]" value="{{ $size->id }}"
                                            {{ in_array($size->id, (array)request('sizes')) ? 'checked' : '' }}>
                                        {{ $size->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                            <a href="{{ route('product.search') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="col-lg-9">
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-4 col-sm-6">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <a href="{{ route('details', $product->id) }}" class="position-relative">
                                <img src="{{ Storage::url($product->image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height:260px;object-fit:cover;">
                                @if($product->old_price && $product->old_price > $product->min_price)
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                        -{{ round(100 - ($product->min_price/$product->old_price*100)) }}%
                                    </span>
                                @endif
                            </a>
                            <div class="card-body text-center">
                                <h6 class="card-title fw-bold text-truncate mb-1">
                                    <a href="{{ route('details', $product->id) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <div class="price-block">
                                    <span class="text-danger fw-bold">{{ number_format($product->min_price, 0, ',', '.') }}₫</span>
                                    @if($product->old_price && $product->old_price > $product->min_price)
                                        <small class="text-muted text-decoration-line-through ms-1">
                                            {{ number_format($product->old_price, 0, ',', '.') }}₫
                                        </small>
                                    @endif
                                </div>
                                <small class="text-muted d-block">
                                    +{{ $product->colors_count ?? 0 }} Màu | +{{ $product->sizes_count ?? 0 }} Size
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-card img {
        transition: transform .3s ease;
    }
    .product-card:hover img {
        transform: scale(1.05);
    }
    .product-card .badge {
        font-size: .75rem;
    }
    .filter-form .btn-outline-secondary.active {
        background-color: #0d6efd; color:#fff; border-color:#0d6efd;
    }
    .list-group-item a:hover {
        color:#0d6efd;
    }
    .btn-outline-secondary.active {
        background-color: #0d6efd; 
        color: #fff;
        border-color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.size-label input[type="checkbox"]').forEach(el => {
    el.addEventListener('change', function() {
        if (this.checked) {
            this.closest('label').classList.add('active');   // highlight size được chọn
        } else {
            this.closest('label').classList.remove('active'); // bỏ highlight nếu bỏ chọn
        }
    });
});
</script>
@endpush
