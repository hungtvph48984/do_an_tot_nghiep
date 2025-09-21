@extends('layouts.master')

@section('content')
<h1 class="text-2xl font-bold mb-4">Danh sách sản phẩm</h1>
<a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Thêm sản
    phẩm</a>
<table class="w-full border-collapse border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">Tên</th>
            <th class="border p-2">Giá gốc</th>
            <th class="border p-2">Biến thể</th>
            <th class="border p-2">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td class="border p-2">{{ $product->name }}</td>
            <td class="border p-2">{{ number_format($product->base_price, 2) }}</td>
            <td class="border p-2">{{ $product->variants->count() }}</td>
            <td class="border p-2">
                <a href="{{ route('products.show', $product) }}" class="text-blue-500">Xem</a>
                <a href="{{ route('products.edit', $product) }}" class="text-yellow-500 mx-2">Sửa</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500" onclick="return confirm('Xóa sản phẩm?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
