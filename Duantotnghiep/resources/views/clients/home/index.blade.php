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
                            <li><a href="#" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                            <li><a href="#"><i class="icon-heart"></i></a></li>
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
