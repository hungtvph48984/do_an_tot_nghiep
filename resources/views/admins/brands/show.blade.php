@extends('admins.layouts.master')

@section('content')
<div class="container mt-4">
    <h1>Chi tiết nhãn: {{ $brand->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.brands.link-products', ['brand' => $brand->id]) }}" class="btn btn-primary mb-3">
            Liên kết sản phẩm
        </a>

        <a href="{{ route('admin.brands.edit', ['brand' => $brand->id]) }}" class="btn btn-warning">
            Sửa nhãn
        </a>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
            Quay lại danh sách
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Mã</th>
                <th>Danh mục</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brand->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Chưa có sản phẩm nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
