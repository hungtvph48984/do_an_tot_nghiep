@extends('admins.layouts.master')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <h1>Chi tiết danh mục: {{ $category->name }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mt-2">← Quay lại danh sách</a>
    </div>
</section>

<section class="content mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info text-white">
                Danh sách sản phẩm thuộc danh mục "{{ $category->name }}"
            </div>
            <div class="card-body">
                @if($category->products->isEmpty())
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Hình ảnh</th>
                                <th>Mô tả</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                    <td>{{ $product->amount }}</td>
                                    <td>
                                        <img src="{{ asset('assets/images/' . $product->image) }}" alt="{{ $product->name }}" width="90" height="90" class="img-thumbnail ">
                                    </td>
                                    <td>{{ Str::limit($product->description, 100) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
