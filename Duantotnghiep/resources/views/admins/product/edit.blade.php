@extends('admins.layouts.master')

@section('content')
    <h1>Chỉnh sửa sản phẩm</h1>
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" required>
        </div>
        <div class="mb-3">
            <label>Ảnh (Album)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-control" id="image-upload">
            <div id="image-preview" class="d-flex flex-wrap mt-3">
                @foreach($product->images ?? [] as $image)
                    <div class="position-relative m-2" data-path="{{ $image }}">
                        <img src="{{ Storage::url($image) }}" style="width:100px;height:100px;object-fit:cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image">X</button>
                    </div>
                @endforeach
            </div>
            <input type="hidden" name="deleted_images" id="deleted-images">
        </div>
        <h2>Biến thể</h2>
        <div id="variants-wrapper">
            @foreach($options ?? [] as $index => $variant)
                <div class="variant-item border p-3 mb-3 rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Option {{ $loop->iteration }}</h5>
                        <button type="button" class="btn btn-sm btn-danger remove-variant">Xóa</button>
                    </div>
                    <select name="variants[{{ $index }}][name]" class="form-select mb-3">
                        <option value="size" {{ $variant['name'] == 'size' ? 'selected' : '' }}>Kích cỡ</option>
                        <option value="color" {{ $variant['name'] == 'color' ? 'selected' : '' }}>Màu sắc</option>
                        <option value="weight" {{ $variant['name'] == 'weight' ? 'selected' : '' }}>Cân nặng</option>
                        <option value="smell" {{ $variant['name'] == 'smell' ? 'selected' : '' }}>Mùi</option>
                    </select>
                    <select name="variants[{{ $index }}][values][]" class="form-select variant-values" multiple>
                        @foreach($variant['values'] as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-variant" class="btn btn-primary mt-3">Thêm option</button>
        <button type="submit" class="btn btn-success mt-3">Cập nhật</button>
    </form>

    <template id="variant-template">
        <div class="variant-item border p-3 mb-3 rounded">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>Option __number__</h5>
                <button type="button" class="btn btn-sm btn-danger remove-variant">Xóa</button>
            </div>
            <select name="variants[__index__][name]" class="form-select mb-3">
                <option value="size">Kích cỡ</option>
                <option value="color">Màu sắc</option>
                <option value="weight">Cân nặng</option>
                <option value="smell">Mùi</option>
            </select>
            <select name="variants[__index__][values][]" class="form-select variant-values" multiple></select>
        </div>
    </template>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Truyền giá trị $options sang JavaScript bằng @json
            const options = @json($options ?? []);
            let variantIndex = options.length || 0;
            const wrapper = document.getElementById('variants-wrapper');
            const template = document.getElementById('variant-template').innerHTML;

            wrapper.querySelectorAll('.variant-values').forEach(initChoices);

            document.getElementById('add-variant').addEventListener('click', function () {
                let newHtml = template
                    .replace(/__index__/g, variantIndex)
                    .replace(/__number__/g, variantIndex + 1);
                wrapper.insertAdjacentHTML('beforeend', newHtml);
                let newSelect = wrapper.querySelectorAll('.variant-item:last-child .variant-values')[0];
                initChoices(newSelect);
                variantIndex++;
            });

            wrapper.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-variant')) {
                    e.target.closest('.variant-item').remove();
                }
            });

            function initChoices(selectEl) {
                new Choices(selectEl, {
                    removeItemButton: true,
                    placeholderValue: 'Nhập giá trị và nhấn Enter',
                    duplicateItemsAllowed: false,
                });
            }

            // Xử lý ảnh
            const uploadInput = document.getElementById('image-upload');
            const previewContainer = document.getElementById('image-preview');
            const deletedImagesInput = document.getElementById('deleted-images');

            uploadInput.addEventListener('change', function () {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.classList.add('position-relative', 'm-2');
                        imgWrapper.innerHTML = `<img src="${e.target.result}" style="width:100px;height:100px;object-fit:cover;"><button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image">X</button>`;
                        previewContainer.appendChild(imgWrapper);
                        imgWrapper.querySelector('.remove-image').addEventListener('click', () => imgWrapper.remove());
                    };
                    reader.readAsDataURL(file);
                });
            });

            previewContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-image')) {
                    const path = e.target.parentElement.dataset.path;
                    if (path) {
                        let deleted = deletedImagesInput.value.split(',').filter(p => p);
                        if (!deleted.includes(path)) deleted.push(path);
                        deletedImagesInput.value = deleted.join(',');
                    }
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endsection