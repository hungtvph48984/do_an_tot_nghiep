@extends('clients.layouts.master')

@section('title', $category->name)

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- Sidebar bộ lọc --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 position-sticky" style="top: 20px; z-index: 1020;">
                <h6 class="mb-4 text-left ">Danh mục: {{ $category->name }}<h6 >
                {{-- Form sắp xếp --}}
            <form method="GET" action="{{ route('category.show', $category->id) }}" class="d-flex align-items-center">
                {{-- Giữ lại filter hiện tại nếu có --}}
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                @foreach((array) request('sizes') as $size)
                    <input type="hidden" name="sizes[]" value="{{ $size }}">
                @endforeach

                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Sắp xếp --</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A → Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z → A</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                </select>
            </form>
                <div class="card-header text-black">
                    Bộ lọc sản phẩm
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('category.show', $category->id) }}" 
                        class="filter-form p-3 shadow-sm rounded bg-white">
                        <h5 class="mb-3 text-primary">Bộ lọc sản phẩm</h5>

                        {{-- Giá --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Giá (VNĐ):</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                    class="form-control form-control-sm" placeholder="Từ">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                    class="form-control form-control-sm" placeholder="Đến">
                            </div>
                        </div>

                        {{-- Size --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Size:</label>
                            <div class="filter-sizes">
                                @foreach($sizes as $size)
                                    <div class="form-check">
                                        <input type="checkbox" name="sizes[]" value="{{ $size->id }}" 
                                            id="size_{{ $size->id }}" class="form-check-input"
                                            {{ in_array($size->id, (array) request('sizes')) ? 'checked' : '' }}>
                                        <label for="size_{{ $size->id }}" class="form-check-label">{{ $size->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Nút --}}
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                            <a href="{{ route('category.show', $category->id) }}" 
                            class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- Danh sách sản phẩm --}}
        <div class="col-md-9">
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <a href="{{ route('details', $product->id) }}" class="d-block">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="card-img-top img-fluid" 
                                         style="height: 250px; object-fit: cover; border-top-left-radius: .5rem; border-top-right-radius: .5rem;"
                                         alt="{{ $product->name }}">
                                </a>
                                <div class="card-body text-center">
                                    <h6 class="card-title fw-bold text-truncate" title="{{ $product->name }}">
                                        <a href="{{ route('details', $product->id) }}" class="text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <p class="text-danger fw-bold mb-1">
                                        {{ number_format($product->min_price, 0, ',', '.') }} ₫
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-0 d-flex justify-content-center gap-2 align-items-center">
                                    <a href="{{ route('details', $product->id) }}" 
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Xem
                                    </a>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                            Giỏ
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">Không tìm thấy sản phẩm phù hợp.</div>
            @endif
        </div>
    </div>
</div>
@endsection

<style>
    .filter-form {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}
.filter-form:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.filter-form h5 {
    font-size: 1.1rem;
    font-weight: 600;
    border-left: 4px solid #0d6efd;
    padding-left: 8px;
}
.filter-form .form-control {
    border-radius: 8px;
}
.filter-form .form-check-label {
    cursor: pointer;
    transition: color 0.2s ease;
}
.filter-form .form-check-input:checked + .form-check-label {
    font-weight: 600;
    color: #0d6efd;
}
.card.shadow-sm.border-0.position-sticky {
    max-height: 90vh;   
    overflow-y: auto;   
}
form select.form-select-sm {
    min-width: 160px;
    border-radius: 8px;
    font-size: 0.9rem;
}



</style>
