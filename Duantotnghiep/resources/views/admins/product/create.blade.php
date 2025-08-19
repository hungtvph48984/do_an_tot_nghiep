@extends('admins.layouts.master')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tạo sản phẩm</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">Tạo sản phẩm</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Hiển thị lỗi nếu có --}}
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Tên sản phẩm --}}
                            <div class="mb-3">
                                <label>Tên</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Danh mục</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            {{-- Mô tả --}}
                            <div class="mb-3">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>

                            {{-- Giá --}}
                            <div class="mb-3">
                                <label>Giá</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>

                            {{-- Ảnh --}}
                            <div class="mb-3">
                                <label>Ảnh (Album)</label>
                                <input type="file" name="images[]" multiple accept="image/*" class="form-control" id="image-upload">
                                <div id="image-preview" class="d-flex flex-wrap mt-3"></div>
                            </div>

                            {{-- Biến thể --}}
                            <h2>Biến thể</h2>
                            <div id="variants-wrapper"></div>
                            <button type="button" id="add-variant" class="btn btn-primary mt-3">Thêm option</button>

                            {{-- Nút submit --}}
                            <button type="submit" class="btn btn-success mt-3">Lưu</button>
                        </form>

                        <template id="variant-template">
                            <div class="variant-item border p-3 mb-3 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5>Option __number__</h5>
                                    <button type="button" class="btn btn-sm btn-danger remove-variant">Xóa</button>
                                </div>
                                <select name="variants[__index__][name]" class="form-select mb-3 variant-name">
                                    <option value="">-- Chọn loại biến thể --</option>
                                    <option value="size">Kích cỡ</option>
                                    <option value="color">Màu sắc</option>
                                </select>
                                <select name="variants[__index__][values][]" class="form-select variant-values" multiple></select>

                                {{-- Input giá từng biến thể --}}
                                <div class="mb-2">
                                    <label>Giá cho option __number__</label>
                                    <input type="number" name="variants_prices[__index__]" class="form-control" step="0.01" required>
                                </div>
                            </div>
                        </template>


                        {{-- Choices.js --}}
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
                        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                let variantIndex = 0;
                                const wrapper = document.getElementById('variants-wrapper');
                                const templateHtml = document.getElementById('variant-template').innerHTML;

                                // Thêm biến thể
                                document.getElementById('add-variant').addEventListener('click', function() {
                                    addVariant();
                                });

                                function addVariant() {
                                    let newHtml = templateHtml
                                        .replace(/__index__/g, variantIndex)
                                        .replace(/__number__/g, variantIndex + 1);
                                    wrapper.insertAdjacentHTML('beforeend', newHtml);

                                    let newItem = wrapper.querySelector('.variant-item:last-child');
                                    let nameSelect = newItem.querySelector('.variant-name');
                                    let valueSelect = newItem.querySelector('.variant-values');

                                    let choices = new Choices(valueSelect, {
                                        removeItemButton: true,
                                        placeholderValue: 'Nhập giá trị và nhấn Enter',
                                        duplicateItemsAllowed: false,
                                    });

                                    nameSelect.addEventListener('change', function() {
                                        setChoices(choices, getVariantOptions(this.value));
                                    });

                                    variantIndex++;
                                }

                                function getVariantOptions(type) {
                                    if (type === 'size') return ['S', 'M', 'L', 'XL'];
                                    if (type === 'color') return ['Xanh', 'Đỏ', 'Vàng'];
                                    return [];
                                }

                                function setChoices(choicesInstance, items) {
                                    choicesInstance.clearChoices();
                                    if (items.length) {
                                        choicesInstance.setChoices(items.map(v => ({
                                            value: v,
                                            label: v
                                        })), 'value', 'label', true);
                                    }
                                }

                                // Xử lý ảnh preview
                                const uploadInput = document.getElementById('image-upload');
                                const previewContainer = document.getElementById('image-preview');
                                uploadInput.addEventListener('change', function() {
                                    previewContainer.innerHTML = '';
                                    Array.from(this.files).forEach(file => {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const imgWrapper = document.createElement('div');
                                            imgWrapper.classList.add('position-relative', 'm-2');
                                            imgWrapper.innerHTML = `<img src="${e.target.result}" style="width:100px;height:100px;object-fit:cover;">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image">X</button>`;
                                            previewContainer.appendChild(imgWrapper);
                                            imgWrapper.querySelector('.remove-image').addEventListener('click', () => imgWrapper.remove());
                                        };
                                        reader.readAsDataURL(file);
                                    });
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection