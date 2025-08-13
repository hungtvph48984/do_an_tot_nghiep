@extends('clients.layouts.master')

@section('title', $product->name)

@section('content')
@if(session('success'))
    <div class="alert alert-success fade show" role="alert">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="section">
    <div class="container">
        <div class="row">

            {{-- ====== Hình ảnh sản phẩm & gallery ====== --}}
            <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                <div class="product-image">
                    <div class="product_img_box">
                        <img id="product_img"
                             src="{{ Storage::url($product->image) }}"
                             data-zoom-image="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}" />
                    </div>
                    <div id="pr_item_gallery" class="product_gallery_item owl-thumbs-slider owl-carousel owl-theme">
                        @foreach ($product->variants as $variant)
                            <div class="item">
                                <a href="#"
                                   class="product_gallery_item"
                                   data-image="{{ Storage::url($variant->image) }}"
                                   data-zoom-image="{{ Storage::url($variant->image) }}">
                                    <img src="{{ Storage::url($variant->image) }}" alt="{{ $product->name }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ====== Thông tin sản phẩm ====== --}}
            <div class="col-lg-6 col-md-6">
                <div class="pr_detail">
                    <div class="product_description">
                        <h4 class="product_title">{{ $product->name }}</h4>
                        <div class="product_price">
                            <span class="price" id="variant-price">
                                {{ number_format($product->variants->min('price')) }} $
                            </span>
                            <del id="variant-old-price"></del>
                        </div>
                        <div class="pr_desc">
                            <p>{{ $product->description }}</p>
                        </div>

                        {{-- Chọn màu --}}
                        <div class="pr_switch_wrap">
                            <span class="switch_lable">Color</span>
                            <div class="product_color_switch">
                                @foreach ($product->variants->unique('color_id') as $variant)
                                    <span class="color-option"
                                          style="background-color: {{ $variant->color->code }}"
                                          data-color="{{ $variant->color_id }}"></span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Chọn size --}}
                        <div class="pr_switch_wrap">
                            <span class="switch_lable">Size</span>
                            <div class="product_size_switch">
                                @foreach ($product->variants->unique('size_id') as $variant)
                                    <span class="size-option"
                                          data-size="{{ $variant->size_id }}">
                                        {{ $variant->size->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <p>Stock: <span id="variant-stock">{{ $product->variants->first()->stock }}</span></p>
                    </div>

                    {{-- Form thêm giỏ hàng --}}
                    <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="variant_id" id="variant-id"
                               value="{{ $product->variants->first()->id }}">
                        <div class="cart_extra">
                            <div class="cart-product-quantity">
                                <input type="number" name="quantity" value="1" min="1">
                            </div>
                            <div class="cart_btn">
                                <button class="btn btn-fill-out btn-addtocart" type="submit">
                                    <i class="icon-basket-loaded"></i> thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ====== Sản phẩm liên quan ====== --}}
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="heading_s1">Sản phẩm liên quan</h3>
                <div class="product_slider carousel_slider owl-carousel owl-theme" data-margin="20" data-loop="true">
                    @foreach ($productRelead as $item)
                        <div class="item">
                            <div class="product">
                                <div class="product_img">
                                    <a href="{{ route('product.detail', $item->id) }}">
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                                    </a>
                                </div>
                                <div class="product_info">
                                    <h6 class="product_title">{{ $item->name }}</h6>
                                    <div class="product_price">
                                        <span class="price">
                                            {{ number_format($item->variants->min('price')) }} $
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ====== Script chọn màu/size và cập nhật giá tồn kho ====== --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    let color = null;
    let size = null;
    const updateVariant = () => {
        if (color && size) {
            fetch(`/get-variant/price?product_id={{ $product->id }}&color=${color}&size=${size}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('variant-price').textContent = data.price + ' $';
                        document.getElementById('variant-stock').textContent = data.stock;
                        document.getElementById('variant-id').value = data.variant_id;
                    }
                });
        }
    };
    document.querySelectorAll('.color-option').forEach(el => {
        el.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            color = this.dataset.color;
            updateVariant();
        });
    });
    document.querySelectorAll('.size-option').forEach(el => {
        el.addEventListener('click', function() {
            document.querySelectorAll('.size-option').forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            size = this.dataset.size;
            updateVariant();
        });
    });
});
</script>
@endsection
