@extends('layouts.app')

@section('content')
    <h1>Chi tiết sản phẩm: {{ $product->name }}</h1>

    <!-- Thông tin cơ bản -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Thông tin sản phẩm</h5>
            <p><strong>Mô tả:</strong> {{ $product->description ?? 'Không có mô tả' }}</p>
            <p><strong>Giá cơ bản:</strong> {{ number_format($product->price, 2) }} VND</p>
        </div>
    </div>

    <!-- Album ảnh -->
    @if ($product->images && count($product->images) > 0)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Album ảnh</h5>
                <div class="d-flex flex-wrap">
                    @foreach ($product->images as $image)
                        <div class="position-relative m-2">
                            <img src="{{ Storage::url($image) }}" alt="Product Image" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <p>Không có ảnh cho sản phẩm này.</p>
    @endif

    <!-- Biến thể -->
    @if ($product->variants->count() > 0)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Biến thể</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Thuộc tính</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>SKU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants as $variant)
                            <tr>
                                <td>
                                    @foreach ($variant->attributes as $key => $value)
                                        {{ $key }}: {{ $value }}<br>
                                    @endforeach
                                </td>
                                <td>{{ number_format($variant->price, 2) }} VND</td>
                                <td>{{ $variant->stock }}</td>
                                <td>{{ $variant->sku }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>Không có biến thể cho sản phẩm này.</p>
    @endif

    <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
@endsection