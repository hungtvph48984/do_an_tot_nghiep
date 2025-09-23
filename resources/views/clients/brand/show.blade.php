@extends('clients.layouts.master')

@section('title', $brand->name)

@section('content')
<div class="container py-4">
    <div class="row">

        {{-- ===== Sidebar Danh mục ===== --}}
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 position-sticky" style="top:20px; z-index:1020;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Danh mục</h5>
                    <ul class="list-group mb-4">
                        @foreach($allCategories as $cat)
                            <li class="list-group-item p-2 border-0">
                                <a href="{{ route('category.show', $cat->id) }}"
                                   class="d-block text-decoration-none text-dark">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- ===== Danh sách sản phẩm theo Brand ===== --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">Thương hiệu: {{ $brand->name }}</h4>
            </div>

            @if($brand->products->count())
                <div class="row g-4">
                    @foreach($brand->products as $product)
                        <div class="col-md-4 col-sm-6">
                            <div class="card product-card border-0 shadow-sm h-100">
                                {{-- Hình --}}
                                <a href="{{ route('details', $product->id) }}" class="position-relative">
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $product->name }}"
                                         style="height:260px;object-fit:cover;">
                                </a>

                                {{-- Nội dung --}}
                                <div class="card-body text-center">
                                    <h6 class="card-title fw-bold text-truncate mb-1">
                                        <a href="{{ route('details', $product->id) }}" 
                                           class="text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <div class="price-block">
                                        <span class="text-danger fw-bold">
                                            {{ number_format($product->price,0,',','.') }}₫
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">Chưa có sản phẩm nào thuộc thương hiệu này.</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card img { transition: transform .3s ease; }
.product-card:hover img { transform: scale(1.05); }
.product-card .badge { font-size: .75rem; }
</style>
@endpush
