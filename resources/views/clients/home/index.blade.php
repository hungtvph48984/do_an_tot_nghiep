@extends('clients.layouts.master')

@section('title', 'Trang Chủ')

@section('content')
<!-- START SECTION BANNER -->
<div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
    <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active background_bg" data-img-src="assets/images/banner111.jpg">

            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev"><i class="ion-chevron-left"></i></a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next"><i class="ion-chevron-right"></i></a>
    </div>
    <br>
<br>


    <div class="section small_pt">
	<div class="container">
        <div class="row">
        	<div class="col-12">
            	<div class="client_logo carousel_slider owl-carousel owl-theme" data-dots="false" data-margin="30" data-loop="true" data-autoplay="true" data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "5"}}'>
                	<div class="item">
                    	<div class="cl_logo">
                        	<img src="assets/images/cl_logo1.png" alt="cl_logo"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class="cl_logo">
                        	<img src="assets/images/cl_logo2.png" alt="cl_logo"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class="cl_logo">
                        	<img src="assets/images/cl_logo3.png" alt="cl_logo"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class="cl_logo">
                        	<img src="assets/images/cl_logo4.png" alt="cl_logo"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class="cl_logo">
                        	<img src="assets/images/cl_logo5.png" alt="cl_logo"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class="cl_logo">
                        	<img src="assets/images/cl_logo6.png" alt="cl_logo"/>
                        </div>
                    </div>
                    <div class="item">
                        <div class="cl_logo">
                        	<img src="assets/images/cl_logo7.png" alt="cl_logo"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<br><br><br><br><br><br><br><br><br><br><br>


<!-- END SECTION BANNER -->

<!-- SECTION: SẢN PHẨM NỔI BẬT TỪ DATABASE -->





<div class="product_slider carousel_slider owl-carousel owl-theme nav_style1"
     data-loop="true" data-dots="false" data-nav="true" data-margin="20"
     data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>

    @foreach ($products as $product)

    <div class="item">
        <div class="product">
            <div class="product_img">
                <a href="{{ route('details', ['id' => $product->id]) }}" class="position-relative" style="display: block;">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                </a>
                <div class="product_action_box">
                    <ul class="list_none pr_action_btn">
                        <li class="add-to-cart">
                            <a href="#"><i class="icon-basket-loaded"></i> Add To Cart</a>
                        </li>
                        <li><a href="#" class="popup-ajax"><i class="icon-shuffle"></i></a></li>
                        <li>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#quickViewModal{{ $product->id }}">
                                <i class="icon-magnifier-add"></i>
                            </a>
                        </li>
                        @php
                            $wishlist = session('wishlist', []);
                            $isInWishlist = isset($wishlist[$product->id]);
                        @endphp
                        <button type="button" class="btn p-0 border-0 bg-transparent btn-toggle-wishlist" data-id="{{ $product->id }}">
                            <i class="icon-heart {{ $isInWishlist ? 'text-danger' : 'text-muted' }}"></i>
                        </button>
                    </ul>
                </div>
            </div>
            <div class="product_info">
                <h6 class="product_title">
                    <a href="{{ route('details', ['id' => $product->id]) }}">{{ $product->name }}</a>
                </h6>
                <div class="product_price">
                    <span class="price">{{ $product->lowest_price ?? 0 }} đ</span>
                    @if (!empty($product->original_price) && $product->original_price > $product->lowest_price)
                        <del>{{ $product->original_price }} đ</del>
                        <div class="on_sale">
                            <span>
                                {{ round(100 - ($product->lowest_price / $product->original_price * 100)) }}% Off
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
                    <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                        <div class="product-image">
                            <div class="product_img_box">
                                <img id="product_img" src="{{ Storage::url($product->image) }}" 
                                     data-zoom-image="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}" />
                            </div>
                            <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4" data-slides-to-scroll="1" data-infinite="false">
                                @php
                                    $images = is_array($product->images) || is_object($product->images) ? $product->images : [];
                                @endphp

                                @foreach ($images as $img)
                                    <div class="item">
                                        <a href="#" class="product_gallery_item" data-image="{{ Storage::url($img->path) }}" data-zoom-image="{{ Storage::url($img->path) }}">
                                            <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" />
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="col-lg-6 col-md-6">
                        <div class="pr_detail">
                            <div class="product_description">
                                <h4 class="product_title"><a href="{{ route('details', ['id' => $product->id]) }}">{{ $product->name }}</a></h4>
                                <div class="product_price">
                                    <span class="price">{{ $product->lowest_price ?? 0 }} đ</span>
                                    @if (!empty($product->original_price) && $product->original_price > $product->lowest_price)
                                        <del>{{ $product->original_price }} đ</del>
                                        <div class="on_sale">
                                            <span>{{ round(100 - ($product->lowest_price / $product->original_price * 100)) }}% Off</span>
                                        </div>
                                    @endif
                                </div>


                                <div class="pr_desc">
                                    <p>{{ $product->description }}</p>
                                </div>

                                <!-- Hiển thị màu sắc -->
                                <div class="pr_switch_wrap">
                                    <span class="switch_lable">Màu sắc</span>
                                    <div class="product_color_switch">
                                        @foreach ($product->variants as $variant)
                                            <span class="active" style="background-color: {{ $variant->color->code }}"></span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Hiển thị kích cỡ và số lượng tồn kho -->
                                <div class="pr_switch_wrap">
                                    <span class="switch_lable">Kích cỡ</span>
                                    <div class="product_size_switch">
                                        @foreach ($product->variants as $variant)
                                            <span class="size_option" data-size="{{ $variant->size->name }}" data-stock="{{ $variant->stock }}">{{ $variant->size->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Hiển thị số lượng tồn kho -->
                                <div id="stock_info" class="pr_switch_wrap">
                                    <span class="switch_lable">Tồn kho:</span>
                                    <span id="stock_quantity">0</span> sản phẩm
                                </div>

                            </div>

                            <hr />

                            <div class="cart_extra">
                                <div class="cart-product-quantity">
                                    <div class="quantity">
                                        <input type="button" value="-" class="minus">
                                        <input type="text" name="quantity" value="1" title="Qty" class="qty" size="4">
                                        <input type="button" value="+" class="plus">
                                    </div>
                                </div>
                                <div class="cart_btn">
                                    <button class="btn btn-fill-out btn-addtocart" type="button">
                                        <i class="icon-basket-loaded"></i> Thêm vào giỏ hàng
                                    </button>
                                    <a class="add_compare" href="#"><i class="icon-shuffle"></i></a>
                                    <a class="add_wishlist" href="#"><i class="icon-heart"></i></a>
                                </div>
                            </div>

                            <hr />

                            <ul class="product-meta">
                                <li>Category: <a href="#">Clothing</a></li>
                                <li>Tags: <a href="#" rel="tag">Cloth</a>, <a href="#" rel="tag">printed</a></li>
                            </ul>

                            <div class="product_share">
                                <span>Chia sẻ:</span>
                                <ul class="social_icons">
                                    <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                    <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                    <li><a href="#"><i class="ion-social-googleplus"></i></a></li>
                                    <li><a href="#"><i class="ion-social-youtube-outline"></i></a></li>
                                    <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                                </ul>
                            </div>
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

        <div class="item">
            <div class="product">
                <div class="product_img">
                    <a href="{{ route('details', $product->id) }}" class="position-relative">
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
                        <a href="{{ route('details', ['id' => $product->id]) }}">{{ $product->name }}</a>
                    </h6>

                    <div class="product_price">
                        <span class="price">{{ $product->lowest_price ?? 0 }} đ</span>
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

        
    </div>
</section>
    </div>
</div>

    <div class="middle_footer">
    	<div class="container">
        	<div class="row">
            	<div class="col-12">
                	<div class="shopping_info">
                        <div class="row justify-content-center">
                            <div class="col-md-4">	
                                <div class="icon_box icon_box_style2">
                                    <div class="icon">
                                        <i class="flaticon-shipped"></i>
                                    </div>
                                    <div class="icon_box_content">
                                    	<h5>Free Delivery</h5>
                                        <p>Phasellus blandit massa enim elit of passage varius nunc.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">	
                                <div class="icon_box icon_box_style2">
                                    <div class="icon">
                                        <i class="flaticon-money-back"></i>
                                    </div>
                                    <div class="icon_box_content">
                                    	<h5>30 Day Returns Guarantee</h5>
                                        <p>Phasellus blandit massa enim elit of passage varius nunc.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">	
                                <div class="icon_box icon_box_style2">
                                    <div class="icon">
                                        <i class="flaticon-support"></i>
                                    </div>
                                    <div class="icon_box_content">
                                    	<h5>27/4 Online Support</h5>
                                        <p>Phasellus blandit massa enim elit of passage varius nunc.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>


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


<script>
    // JavaScript để cập nhật số lượng tồn kho khi người dùng chọn size
    document.querySelectorAll('.size_option').forEach(function (sizeElement) {
        sizeElement.addEventListener('click', function () {
            var stockQuantity = this.getAttribute('data-stock');
            document.getElementById('stock_quantity').textContent = stockQuantity;
        });
    });
</script>
@endpush
