@extends('clients.layouts.master')

@section('title', $product->name)

@section('content')


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
                                {{ number_format($product->variants->min('price')) }} đ 
                            </span>
                            <del id="variant-old-price"></del>
                        </div>
                        <div class="pr_desc">
                            <div>{!! $product->description !!}</div>
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
    <div class="section mt-5">
        <div class="container">
            <h3 class="fw-bold mb-4">Sản phẩm liên quan</h3>
            @if($relatedProducts->count())
                <div class="row g-4">
                    @foreach($relatedProducts as $product)
                        <div class="col-md-3 col-sm-6">
                            <div class="card product-card border-0 shadow-sm h-100">
                                {{-- Hình --}}
                                <a href="{{ route('details', $product->id) }}" class="position-relative">
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                        class="card-img-top" 
                                        alt="{{ $product->name }}"
                                        style="height:220px;object-fit:cover;">
                                    @if($product->old_price && $product->old_price > $product->min_price)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                            -{{ round(100 - ($product->min_price/$product->old_price*100)) }}%
                                        </span>
                                    @endif
                                </a>

                                {{-- Nội dung --}}
                                <div class="card-body text-center">
                                    <h6 class="card-title fw-bold text-truncate mb-1">
                                        <a href="{{ route('details', $product->id) }}" class="text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <div class="price-block">
                                        <span class="text-danger fw-bold">
                                            {{ number_format($product->min_price,0,',','.') }}₫
                                        </span>
                                        @if($product->old_price && $product->old_price > $product->min_price)
                                            <small class="text-muted text-decoration-line-through ms-1">
                                                {{ number_format($product->old_price,0,',','.') }}₫
                                            </small>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block">
                                        +{{ $product->colors_count ?? 0 }} Màu | +{{ $product->sizes_count ?? 0 }} Size
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">Không có sản phẩm liên quan.</div>
            @endif
        </div>
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

@push('styles')
<style>
.product-card img { transition: transform .3s ease; }
.product-card:hover img { transform: scale(1.05); }
.product-card .badge { font-size: .75rem; }
</style>
@endpush

