@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Thêm sản phẩm</h1>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block">Tên sản phẩm</label>
        <input type="text" name="name" class="w-full border p-2 @error('name') border-red-500 @enderror"
            value="{{ old('name') }}">
        @error('name')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label class="block">Mô tả</label>
        <textarea name="description" class="w-full border p-2">{{ old('description') }}</textarea>
    </div>
    <div class="mb-4">
        <label class="block">Giá gốc</label>
        <input type="number" name="base_price" step="0.01"
            class="w-full border p-2 @error('base_price') border-red-500 @enderror" value="{{ old('base_price') }}">
        @error('base_price')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <h2 class="text-xl font-bold mb-2">Biến thể</h2>
    <div id="variants" class="mb-4">
        <div class="variant mb-2 p-4 border rounded">
            <div class="grid grid-cols-4 gap-2">
                <div>
                    <label>Màu sắc</label>
                    <input type="text" name="variants[0][color]" class="w-full border p-2">
                </div>
                <div>
                    <label>Kích thước</label>
                    <input type="text" name="variants[0][size]" class="w-full border p-2">
                </div>
                <div>
                    <label>Giá</label>
                    <input type="number" name="variants[0][price]" step="0.01" class="w-full border p-2">
                </div>
                <div>
                    <label>Tồn kho</label>
                    <input type="number" name="variants[0][stock]" class="w-full border p-2">
                </div>
            </div>
            <button type="button" class="remove-variant text-red-500 mt-2">Xóa biến thể</button>
        </div>
    </div>
    <button type="button" id="add-variant" class="bg-green-500 text-white px-4 py-2 rounded mb-4">Thêm biến thể</button>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Lưu</button>
</form>

<script>
let variantIndex = 1;
document.getElementById('add-variant').addEventListener('click', function() {
    const variantsDiv = document.getElementById('variants');
    const newVariant = document.createElement('div');
    newVariant.classList.add('variant', 'mb-2', 'p-4', 'border', 'rounded');
    newVariant.innerHTML = `
                <div class="grid grid-cols-4 gap-2">
                    <div>
                        <label>Màu sắc</label>
                        <input type="text" name="variants[${variantIndex}][color]" class="w-full border p-2">
                    </div>
                    <div>
                        <label>Kích thước</label>
                        <input type="text" name="variants[${variantIndex}][size]" class="w-full border p-2">
                    </div>
                    <div>
                        <label>Giá</label>
                        <input type="number" name="variants[${variantIndex}][price]" step="0.01" class="w-full border p-2">
                    </div>
                    <div>
                        <label>Tồn kho</label>
                        <input type="number" name="variants[${variantIndex}][stock]" class="w-full border p-2">
                    </div>
                </div>
                <button type="button" class="remove-variant text-red-500 mt-2">Xóa biến thể</button>
            `;
    variantsDiv.appendChild(newVariant);
    variantIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant')) {
        e.target.parentElement.remove();
    }
});
</script>
@endsection