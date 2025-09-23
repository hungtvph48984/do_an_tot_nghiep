@extends('clients.layouts.master')

@section('title', 'Thương hiệu')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4 text-center">Danh sách thương hiệu</h3>

    @if($brands->count())
        <div class="row g-4">
            @foreach($brands as $brand)
                <div class="col-md-3 col-sm-6">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        {{-- Logo --}}
                        <a href="{{ route('brands.show', $brand->id) }}" class="p-3 d-block">
                            @if($brand->logo)
                                <img src="{{ asset('storage/'.$brand->logo) }}" 
                                     class="img-fluid mb-2" 
                                     alt="{{ $brand->name }}" 
                                     style="max-height:120px; object-fit:contain;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="height:120px;">
                                    <span class="text-muted">No Logo</span>
                                </div>
                            @endif
                        </a>

                        {{-- Nội dung --}}
                        <div class="card-body p-2">
                            <h6 class="fw-bold text-truncate mb-1">
                                <a href="{{ route('brands.show', $brand->id) }}" class="text-dark text-decoration-none">
                                    {{ $brand->name }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $brand->products_count ?? 0 }} sản phẩm
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $brands->links('vendor.pagination.bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center">
            Chưa có thương hiệu nào.
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.card img { transition: transform .3s ease; }
.card:hover img { transform: scale(1.05); }
</style>
@endpush
