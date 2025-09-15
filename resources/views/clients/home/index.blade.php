@extends('clients.layouts.master')

@section('title', 'Trang Chủ')

@section('content')
<!-- START SECTION BANNER -->
<div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
    <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active background_bg" data-img-src="assets/images/banner1.jpg">
                <div class="banner_slide_content">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7 col-9">
                                <div class="banner_content overflow-hiddn">
                                    <h5 class="mb-3 staggered-animation font-weight-light" data-animation="slideInLeft" data-animation-delay="0.5s">Get up to 50% off Today Only!</h5>
                                    <h2 class="staggered-animation" data-animation="slideInLeft" data-animation-delay="1s">Woman Fashion</h2>
                                    <a class="btn btn-fill-out rounded-0 staggered-animation text-uppercase" href="shop-left-sidebar.html" data-animation="slideInLeft" data-animation-delay="1.5s">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev"><i class="ion-chevron-left"></i></a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next"><i class="ion-chevron-right"></i></a>
    </div>
</div>
<!-- END SECTION BANNER -->

<!-- SECTION: SẢN PHẨM NỔI BẬT TỪ DATABASE -->


<div class="product_slider carousel_slider owl-carousel owl-theme nav_style1"
     data-loop="true" data-dots="false" data-nav="true" data-margin="20"
     data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>

    @foreach ($products as $product)

        <div class="item">
            <div class="product">
                <div class="product_img">
                    <a href="#">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product_action_box">
                        <ul class="list_none pr_action_btn">
                            <li class="add-to-cart"><a href="#"><i class="icon-basket-loaded"></i> Add To Cart</a></li>
                            <li><a href="#" class="popup-ajax"><i class="icon-shuffle"></i></a></li>
                            <li>
                                <a href="javascript:void(0)" data-bs-toggle="modal" 
                                data-bs-target="#quickViewModal{{ $product->id }}">
                                <i class="icon-magnifier-add"></i>
                                </a>
                            </li>
                            @php
                                $wishlist = session('wishlist', []);
                                $isInWishlist = isset($wishlist[$product->id]);
                            @endphp

                            <button type="button"
                                    class="btn p-0 border-0 bg-transparent btn-toggle-wishlist"
                                    data-id="{{ $product->id }}">
                                <i class="icon-heart {{ $isInWishlist ? 'text-danger' : 'text-muted' }}"></i>
                            </button>


                        </ul>
                    </div>
                </div>
                <div class="product_info">
                    <h6 class="product_title">
                        <a href="#">{{ $product->name }}</a>
                    </h6>
                    <div class="product_price">
                        <span class="price">${{ $product->lowest_price ?? 0 }}</span>
                        @if (!empty($product->original_price) && $product->original_price > $product->lowest_price)
                            <del>${{ $product->original_price }}</del>
                            <div class="on_sale">
                                <span>
                                    {{
                                        round(100 - ($product->lowest_price / $product->original_price * 100))
                                    }}% Off
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

    @foreach ($products as $product)
        <!-- Quick View Modal -->
        <div class="modal fade" id="quickViewModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-3">
                    <button type="button" class="btn-close ms-auto me-2 mt-2" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Ảnh sản phẩm -->
                            <div class="col-lg-6">
                                <div class="product-image">
                                    <img id="mainImage{{ $product->id }}" 
                                        src="{{ Storage::url($product->image) }}" 
                                        class="img-fluid border" alt="{{ $product->name }}">
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <!-- Ảnh nhỏ (gallery) -->
                                    @foreach ($product->images ?? [] as $img)
                                        <img src="{{ Storage::url($img->path) }}" 
                                            onclick="document.getElementById('mainImage{{ $product->id }}').src=this.src"
                                            class="img-thumbnail" width="80" height="80" alt="">
                                    @endforeach
                                </div>
                            </div>

                            <!-- Thông tin sản phẩm -->
                            <div class="col-lg-6">
                                <h4 class="mb-3">{{ $product->name }}</h4>
                                <h4 class="text-danger">{{ $product->lowest_price ?? 0 }} $</h4>

                                @if (!empty($product->original_price) && $product->original_price > $product->lowest_price)
                                    <p>
                                        <del>{{ $product->original_price }} $</del>
                                        <span class="badge bg-success ms-2">
                                            -{{ round(100 - ($product->lowest_price / $product->original_price * 100)) }}%
                                        </span>
                                    </p>
                                @endif

                                <p class="mb-3">Mô tả ngắn: {{ Str::limit($product->description, 1000) }}</p>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('product.detail', $product->id) }}" class="btn btn-primary">Xem chi tiết</a>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="btn btn-fill-out btn-addtocart" type="submit">
                                        <i class="icon-basket-loaded"></i> thêm vào giỏ hàng
                                    </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    @endforeach




<!-- SECTION: ABOUT US -->
<!-- BANNER 2 -->
<div class="section pb_20">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="single_banner">
                    <img src="assets/images/shop_banner_img1.jpg" alt="shop_banner_img1"/>
                    <div class="single_banner_info">
                        <h5 class="single_bn_title1">Super Sale</h5>
                        <h3 class="single_bn_title">New Collection</h3>
                        <a href="shop-left-sidebar.html" class="single_bn_link">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="single_banner">
                    <img src="assets/images/shop_banner_img2.jpg" alt="shop_banner_img2"/>
                    <div class="single_banner_info">
                        <h3 class="single_bn_title">New Season</h3>
                        <h4 class="single_bn_title1">Sale 40% Off</h4>
                        <a href="shop-left-sidebar.html" class="single_bn_link">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SECTION: FEATURED PRODUCTS STATIC -->
<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="heading_s1 text-center">
                    <h2 class="text-center mb-4">Sản Phẩm Nổi Bật</h2>
                </div>
            </div>
        </div>
        <section class="featured-products mb-5">
    <div class="container">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text fw-bold text-danger">{{ $product->lowest_price ?? 0 }} $</p>
                            <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
    </div>
</div>


@endsection

@push('scripts')
<script>
document.querySelectorAll('.wishlist-form').forEach(form => {
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: new FormData(this)
        });

        if (response.ok) {
            const icon = this.querySelector('i');
            icon.classList.toggle('text-danger');
            icon.classList.toggle('text-muted');
        }
    });
});
</script>
@endpush
