@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>
<p><strong>Mô tả:</strong> {{ $product->description ?? 'Không có' }}</p>
<p><strong>Giá gốc:</strong> {{ number_format($product->base_price, 2) }}</p>

<h2 class="text-xl font-bold mt-4 mb-2">Biến thể</h2>
<table class="w-full border-collapse border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">Màu sắc</th>
            <th class="border p-2">Kích thước</th>
            <th class="border p-2">Giá</th>
            <th class="border p-2">Tồn kho</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($product->variants as $variant)
        <tr>
            <td class="border p-2">{{ $variant->color }}</td>
            <td class="border p-2">{{ $variant->size }}</td>
            <td class="border p-2">{{ number_format($variant->price, 2) }}</td>
            <td class="border p-2">{{ $variant->stock }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('products.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">Quay lại</a>
@endsection