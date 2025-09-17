@extends('clients.layouts.master')

@section('title', $product->name)

@section('content')

<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Sản phẩm tri tiết</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active">Sản phẩm tri tiết</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- START MAIN CONTENT -->
<div class="main_content">
    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row">
                <!-- ====== Hình ảnh sản phẩm & gallery ====== -->
                <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                    <div class="product-image">
                        <div class="product_img_box">
                            <img id="product_img"
                                 src="{{ Storage::url($product->image) }}"
                                 data-zoom-image="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}" />
                            <a href="#" class="product_img_zoom" title="Zoom">
                                <span class="linearicons-zoom-in"></span>
                            </a>
                        </div>
                        <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4" data-slides-to-scroll="1" data-infinite="false">
                            @foreach ($product->variants as $variant)
                                <div class="item">
                                    <a href="#"
                                       class="product_gallery_item {{ $loop->first ? 'active' : '' }}"
                                       data-image="{{ Storage::url($variant->image) }}"
                                       data-zoom-image="{{ Storage::url($variant->image) }}">
                                        <img src="{{ Storage::url($variant->image) }}" alt="{{ $product->name }}" />
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ====== Thông tin sản phẩm ====== -->
                <div class="col-lg-6 col-md-6">
                    <div class="pr_detail">
                        <div class="product_description">
                            <h4 class="product_title"><a href="#">{{ $product->name }}</a></h4>
                            <div class="product_price">
                                <span class="price" id="variant-price">{{ number_format($product->variants->min('price')) }} $</span>
                                <del id="variant-old-price"></del>
                                <div class="on_sale">
                                    <span>35% Off</span> <!-- Adjust if discount is dynamic -->
                                </div>
                            </div>
                            <div class="rating_wrap">
                                <div class="rating">
                                    <div class="product_rate" style="width:80%"></div> <!-- Adjust rating dynamically if available -->
                                </div>
                                <span class="rating_num">(21)</span> <!-- Adjust review count dynamically if available -->
                            </div>
                            <div class="pr_desc">
                                <p>{{ $product->description }}</p>
                            </div>
                            <div class="product_sort_info">
                                <ul>
                                    <li><i class="linearicons-shield-check"></i> 1 Year AL Jazeera Brand Warranty</li>
                                    <li><i class="linearicons-sync"></i> 30 Day Return Policy</li>
                                    <li><i class="linearicons-bag-dollar"></i> Cash on Delivery available</li>
                                </ul>
                            </div>
                            <!-- Chọn màu -->
                            <div class="pr_switch_wrap">
                                <span class="switch_lable">Color</span>
                                <div class="product_color_switch">
                                    @foreach ($product->variants->unique('color_id') as $variant)
                                        <span class="color-option {{ $loop->first ? 'active' : '' }}"
                                              style="background-color: {{ $variant->color->code }}"
                                              data-color="{{ $variant->color_id }}"></span>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Chọn size -->
                            <div class="pr_switch_wrap">
                                <span class="switch_lable">Size</span>
                                <div class="product_size_switch">
                                    @foreach ($product->variants->unique('size_id') as $variant)
                                        <span class="size-option {{ $loop->first ? 'active' : '' }}"
                                              data-size="{{ $variant->size_id }}">
                                            {{ $variant->size->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <p>Stock: <span id="variant-stock">{{ $product->variants->first()->stock }}</span></p>
                        </div>
                        <hr />
                        <!-- Form thêm giỏ hàng -->
                        <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="variant_id" id="variant-id"
                                   value="{{ $product->variants->first()->id }}">
                            <div class="cart_extra">
                                <div class="cart-product-quantity">
                                    <div class="quantity">
                                        <input type="button" value="-" class="minus">
                                        <input type="text" name="quantity" value="1" title="Qty" class="qty" size="4">
                                        <input type="button" value="+" class="plus">
                                    </div>
                                </div>
                                <div class="cart_btn">
                                    <button class="btn btn-fill-out btn-addtocart" type="submit">
                                        <i class="icon-basket-loaded"></i>
                                    </button>
                                    <a class="add_compare" href="#"><i class="icon-shuffle"></i></a>
                                    <a class="add_wishlist" href="#"><i class="icon-heart"></i></a>
                                </div>
                            </div>
                        </form>
                        <hr />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="large_divider clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tab-style3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description" role="tab" aria-controls="Description" aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab" href="#Additional-info" role="tab" aria-controls="Additional-info" aria-selected="false">Additional info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews" role="tab" aria-controls="Reviews" aria-selected="false">Reviews (2)</a>
                            </li>
                        </ul>
                        <div class="tab-content shop_info_tab">
                            <div class="tab-pane fade show active" id="Description" role="tabpanel" aria-labelledby="Description-tab">
                                <p>{{ $product->description }}</p>
                            </div>
                            <div class="tab-pane fade" id="Additional-info" role="tabpanel" aria-labelledby="Additional-info-tab">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Capacity</td>
                                        <td>5 Kg</td> <!-- Adjust dynamically if available -->
                                    </tr>
                                    <tr>
                                        <td>Color</td>
                                        <td>{{ $product->variants->unique('color_id')->pluck('color.name')->implode(', ') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Water Resistant</td>
                                        <td>Yes</td> <!-- Adjust dynamically if available -->
                                    </tr>
                                    <tr>
                                        <td>Material</td>
                                        <td>Artificial Leather</td> <!-- Adjust dynamically if available -->
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                                <div class="comments">
                                    <h5 class="product_tab_title">2 Review For <span>{{ $product->name }}</span></h5>
                                    <ul class="list_none comment_list mt-4">
                                        <!-- Static reviews; replace with dynamic data if available -->
                                        <li>
                                            <div class="comment_img">
                                                <img src="assets/images/user1.jpg" alt="user1"/>
                                            </div>
                                            <div class="comment_block">
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                </div>
                                                <p class="customer_meta">
                                                    <span class="review_author">Alea Brooks</span>
                                                    <span class="comment-date">March 5, 2018</span>
                                                </p>
                                                <div class="description">
                                                    <p>Lorem Ipsumin gravida nibh vel velit auctor aliquet...</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="comment_img">
                                                <img src="assets/images/user2.jpg" alt="user2"/>
                                            </div>
                                            <div class="comment_block">
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:60%"></div>
                                                    </div>
                                                </div>
                                                <p class="customer_meta">
                                                    <span class="review_author">Grace Wong</span>
                                                    <span class="comment-date">June 17, 2018</span>
                                                </p>
                                                <div class="description">
                                                    <p>It is a long established fact that a reader will be distracted...</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="review_form field_form">
                                    <h5>Add a review</h5>
                                    <form class="row mt-3">
                                        <div class="form-group col-12 mb-3">
                                            <div class="star_rating">
                                                <span data-value="1"><i class="far fa-star"></i></span>
                                                <span data-value="2"><i class="far fa-star"></i></span>
                                                <span data-value="3"><i class="far fa-star"></i></span>
                                                <span data-value="4"><i class="far fa-star"></i></span>
                                                <span data-value="5"><i class="far fa-star"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 mb-3">
                                            <textarea required="required" placeholder="Your review *" class="form-control" name="message" rows="4"></textarea>
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                            <input required="required" placeholder="Enter Name *" class="form-control" name="name" type="text">
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                            <input required="required" placeholder="Enter Email *" class="form-control" name="email" type="email">
                                        </div>
                                        <div class="form-group col-12 mb-3">
                                            <button type="submit" class="btn btn-fill-out" name="submit" value="Submit">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="small_divider"></div>
                    <div class="divider"></div>
                    <div class="medium_divider"></div>
                </div>
            </div>
            <!-- ====== Sản phẩm liên quan ====== -->
            <div class="row">
                <div class="col-12">
                    <div class="heading_s1">
                        <h3>Related Products</h3>
                    </div>
                    <div class="releted_product_slider carousel_slider owl-carousel owl-theme" data-margin="20"
                         data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                        @foreach ($productRelead as $item)
                            <div class="item">
                                <div class="product">
                                    <div class="product_img">
                                        <a href="{{ route('product.detail', $item->id) }}">
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                                        </a>
                                        <div class="product_action_box">
                                            <ul class="list_none pr_action_btn">
                                                <li class="add-to-cart"><a href="#"><i class="icon-basket-loaded"></i> Add To Cart</a></li>
                                                <li><a href="shop-compare.html"><i class="icon-shuffle"></i></a></li>
                                                <li><a href="shop-quick-view.html" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                                                <li><a href="#"><i class="icon-heart"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product_info">
                                        <h6 class="product_title"><a href="{{ route('product.detail', $item->id) }}">{{ $item->name }}</a></h6>
                                        <div class="product_price">
                                            <span class="price">{{ number_format($item->variants->min('price')) }} $</span>
                                            <del>{{ number_format($item->variants->min('price') * 1.2) }}</del> <!-- Adjust old price dynamically if available -->
                                            <div class="on_sale">
                                                <span>20% Off</span> <!-- Adjust discount dynamically if available -->
                                            </div>
                                        </div>
                                        <div class="rating_wrap">
                                            <div class="rating">
                                                <div class="product_rate" style="width:80%"></div> <!-- Adjust rating dynamically if available -->
                                            </div>
                                            <span class="rating_num">(21)</span> <!-- Adjust review count dynamically if available -->
                                        </div>
                                        <div class="pr_desc">
                                            <p>{{ $item->description }}</p>
                                        </div>
                                        <div class="pr_switch_wrap">
                                            <div class="product_color_switch">
                                                @foreach ($item->variants->unique('color_id')->take(3) as $variant)
                                                    <span class="{{ $loop->first ? 'active' : '' }}"
                                                          data-color="{{ $variant->color->code }}"></span>
                                                @endforeach
                                            </div>
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
    <!-- END SECTION SHOP -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <div class="section bg_default small_pt small_pb">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="heading_s1 mb-md-0 heading_light">
                        <h3>Subscribe Our Newsletter</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="newsletter_form">
                        <form>
                            <input type="text" required="" class="form-control rounded-0" placeholder="Enter Email Address">
                            <button type="submit" class="btn btn-dark rounded-0" name="submit" value="Submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SUBSCRIBE NEWSLETTER -->
</div>
<!-- END MAIN CONTENT -->

<!-- ====== Script chọn màu/size và cập nhật giá tồn kho ====== -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let color = document.querySelector('.color-option.active')?.dataset.color || null;
    let size = document.querySelector('.size-option.active')?.dataset.size || null;

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

    // Initialize with default variant
    updateVariant();
});
</script>
@endsection
