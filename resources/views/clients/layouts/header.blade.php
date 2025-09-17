<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="Anil z" name="author">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
<meta name="keywords" content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

<!-- SITE TITLE -->
<title>Shops online</title>
<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png">
<!-- Animation CSS -->
<link rel="stylesheet" href="assets/css/animate.css">
<!-- Latest Bootstrap min CSS -->
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<!-- Icon Font CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/linearicons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/simple-line-icons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/owlcarousel/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/owlcarousel/css/owl.theme.css') }}">
<link rel="stylesheet" href="{{ asset('assets/owlcarousel/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
<style>
.user-dropdown {
    position: relative;
}

.user-dropdown .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #ddd;
    padding: 5px 0;
    z-index: 1000;
    min-width: 120px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.user-dropdown:hover .dropdown-menu {
    display: block;
}

.user-dropdown .dropdown-item {
    display: block;
    padding: 8px 16px;
    color: #333;
    text-decoration: none;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.user-dropdown .dropdown-item:hover {
    background-color: #f5f5f5;
}

</style>

</head>

<body>

    {{-- Flash Message --}}
    @if (session('reply_success'))
        {{-- Đây là thông báo phản hồi từ admin, có thể click để vào trang liên hệ --}}
        <div id="flash-alert" 
             class="alert alert-info alert-dismissible fade show text-center m-0 rounded-0" 
             role="alert" 
             style="cursor: pointer;" 
             onclick="window.location.href='{{ route('my') }}'">
            📩 {{ session('reply_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        {{-- Thông báo bình thường --}}
        <div id="flash-alert" 
             class="alert alert-success alert-dismissible fade show text-center m-0 rounded-0" 
             role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-alert" 
             class="alert alert-danger alert-dismissible fade show text-center m-0 rounded-0" 
             role="alert">
            ⚠️ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


<!-- START HEADER -->
<header class="header_wrap fixed-top header_with_topbar">
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                </div>

                <!-- Bên phải -->
                <div class="col-md-6">
                    <div class="text-end">
                        <ul class="header_list d-inline-flex align-items-center mb-0">
                            <li>
                                <a href="{{ route('wishlist.index') }}">
                                    <i class="ti-heart"></i>
                                    <span>Yêu thích (<span class="wishlist-count">{{ count(session('wishlist', [])) }}</span>)</span>
                                </a>
                            </li>

                            @if (Auth::check())
                                <li class="user-dropdown ms-3">
                                    <a href="#">
                                        <i class="ti-user"></i>
                                        <span>{{ Auth::user()->name }}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('client.logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item logout-btn">
                                                    <i class="ti-power-off"></i> Logout
                                                </button>
                                            </form>

                                            <form action="{{ route('profile.show') }}" method="GET">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="ti-user"></i> Profile
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="ms-3">
                                    <a href="{{ route('login') }}">
                                        <i class="ti-user"></i>
                                        <span>Login</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom_header dark_skin main_menu_uppercase">
    	<div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ route('home.index') }}">
                    <img class="logo_light" src="assets/images/logo_light.png" alt="logo" />
                    <img class="logo_dark" src="assets/images/logo_dark.png" alt="logo" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-expanded="false">
                    <span class="ion-android-menu"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li >
                            <a  class="nav-link nav_item" href="{{ route('home.index') }}">Trang chủ</a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Danh mục</a>
                            <div class="dropdown-menu">
                                <ul>
                                    @forelse($categories as $category)
                                        <li>
                                            <a class="dropdown-item nav-link nav_item" href="{{ route('category.show', $category->id) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @empty
                                        <li>
                                            <span class="dropdown-item">Chưa có danh mục</span>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </li>

                        <li class="dropdown dropdown-mega-menu">
                            <a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Products</a>
                            <div class="dropdown-menu">
                                <ul class="mega-menu d-lg-flex">
                                    <li class="mega-menu-col col-lg-3">
                                        <ul>
                                            <li class="dropdown-header">Woman's</li>

                                        </ul>
                                    </li>
                                    <li class="mega-menu-col col-lg-3">
                                        <ul>
                                            <li class="dropdown-header">Men's</li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-cart.html">Donec vitae ante ante</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="checkout.html">Etiam ac rutrum</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="wishlist.html">Quisque condimentum</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="compare.html">Curabitur laoreet</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="order-completed.html">Vivamus in tortor</a></li>
                                        </ul>
                                    </li>
                                    <li class="mega-menu-col col-lg-3">
                                        <ul>
                                            <li class="dropdown-header">Kid's</li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail.html">Donec vitae facilisis</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-left-sidebar.html">Quisque condimentum</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-right-sidebar.html">Etiam ac rutrum</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-thumbnails-left.html">Donec vitae ante ante</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-thumbnails-left.html">Donec porttitor</a></li>
                                        </ul>
                                    </li>
                                    <li class="mega-menu-col col-lg-3">
                                        <ul>
                                            <li class="dropdown-header">Accessories</li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail.html">Donec vitae facilisis</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-left-sidebar.html">Quisque condimentum</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-right-sidebar.html">Etiam ac rutrum</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-thumbnails-left.html">Donec vitae ante ante</a></li>
                                            <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-thumbnails-left.html">Donec porttitor</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                <div class="d-lg-flex menu_banners row g-3 px-3">
                                    <div class="col-sm-4">
                                        <div class="header-banner">
                                            <img src="assets/images/menu_banner1.jpg" alt="menu_banner1">
                                            <div class="banne_info">
                                                <h6>10% Off</h6>
                                                <h4>New Arrival</h4>
                                                <a href="#">Shop now</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="header-banner">
                                            <img src="assets/images/menu_banner2.jpg" alt="menu_banner2">
                                            <div class="banne_info">
                                                <h6>15% Off</h6>
                                                <h4>Men's Fashion</h4>
                                                <a href="#">Shop now</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="header-banner">
                                            <img src="assets/images/menu_banner3.jpg" alt="menu_banner3">
                                            <div class="banne_info">
                                                <h6>23% Off</h6>
                                                <h4>Kids Fashion</h4>
                                                <a href="#">Shop now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Blog</a>
                            <div class="dropdown-menu dropdown-reverse">
                                <ul>
                                    <li>
                                        <a class="dropdown-item menu-link dropdown-toggler" href="#">Grids</a>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-three-columns.html">3 columns</a></li>
                                            	<li><a class="dropdown-item nav-link nav_item" href="blog-four-columns.html">4 columns</a></li>
                                            	<li><a class="dropdown-item nav-link nav_item" href="blog-left-sidebar.html">Left Sidebar</a></li>
                                            	<li><a class="dropdown-item nav-link nav_item" href="blog-right-sidebar.html">right Sidebar</a></li>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-standard-left-sidebar.html">Standard Left Sidebar</a></li>
                                            	<li><a class="dropdown-item nav-link nav_item" href="blog-standard-right-sidebar.html">Standard right Sidebar</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item menu-link dropdown-toggler" href="#">Masonry</a>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-masonry-three-columns.html">3 columns</a></li>
                                           		<li><a class="dropdown-item nav-link nav_item" href="blog-masonry-four-columns.html">4 columns</a></li>
                                            	<li><a class="dropdown-item nav-link nav_item" href="blog-masonry-left-sidebar.html">Left Sidebar</a></li>
                                            	<li><a class="dropdown-item nav-link nav_item" href="blog-masonry-right-sidebar.html">right Sidebar</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item menu-link dropdown-toggler" href="#">Single Post</a>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-single.html">Default</a></li>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-single-left-sidebar.html">left sidebar</a></li>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-single-slider.html">slider post</a></li>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-single-video.html">video post</a></li>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-single-audio.html">audio post</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item menu-link dropdown-toggler" href="#">List</a>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-list-left-sidebar.html">left sidebar</a></li>
                                                <li><a class="dropdown-item nav-link nav_item" href="blog-list-right-sidebar.html">right sidebar</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="dropdown dropdown-mega-menu">
                            <a class="dropdown-toggle nav-link" href="#" data-bs-toggle="dropdown">Shop</a>
                            <div class="dropdown-menu">
                                <ul class="mega-menu d-lg-flex">
                                    <li class="mega-menu-col col-lg-9">
                                        <ul class="d-lg-flex">
                                            <li class="mega-menu-col col-lg-4">
                                                <ul>
                                                    <li class="dropdown-header">Shop Page Layout</li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-list.html">shop List view</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-list-left-sidebar.html">shop List Left Sidebar</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-list-right-sidebar.html">shop List Right Sidebar</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-left-sidebar.html">Left Sidebar</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-right-sidebar.html">Right Sidebar</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-load-more.html">Shop Load More</a></li>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-col col-lg-4">
                                                <ul>
                                                    <li class="dropdown-header">Other Pages</li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-cart.html">Cart</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="checkout.html">Checkout</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="my-account.html">My Account</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="wishlist.html">Wishlist</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="compare.html">compare</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="order-completed.html">Order Completed</a></li>
                                                </ul>
                                            </li>
                                            <li class="mega-menu-col col-lg-4">
                                                <ul>
                                                    <li class="dropdown-header">Product Pages</li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail.html">Default</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-left-sidebar.html">Left Sidebar</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-right-sidebar.html">Right Sidebar</a></li>
                                                    <li><a class="dropdown-item nav-link nav_item" href="shop-product-detail-thumbnails-left.html">Thumbnails Left</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="mega-menu-col col-lg-3">
                                        <div class="header_banner">
                                            <div class="header_banner_content">
                                                <div class="shop_banner">
                                                    <div class="banner_img overlay_bg_40">
                                                        <img src="assets/images/shop_banner.jpg" alt="shop_banner"/>
                                                    </div>
                                                    <div class="shop_bn_content">
                                                        <h5 class="text-uppercase shop_subtitle">New Collection</h5>
                                                        <h3 class="text-uppercase shop_title">Sale 30% Off</h3>
                                                        <a href="#" class="btn btn-white rounded-0 btn-sm text-uppercase">Shop Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li><a class="nav-link nav_item" href="{{ route('contact.form')}}">Liên hệ</a></li>
                    </ul>
                </div>
                    <ul class="navbar-nav attr-nav align-items-center">
                        <li><a href="javascript:void(0);" class="nav-link search_trigger"><i
                                    class="linearicons-magnifier"></i></a>
                            <div class="search_wrap">
                                <span class="close-search"><i class="ion-ios-close-empty"></i></span>
                                <form>
                                    <input type="text" placeholder="Search" class="form-control"
                                        id="search_input">
                                    <button type="submit" class="search_icon"><i
                                            class="ion-ios-search-strong"></i></button>
                                </form>
                            </div>
                            <div class="search_overlay"></div>
                        </li>

                        @auth
                            <li class="dropdown cart_dropdown">
                                <a class="nav-link cart_trigger" href="#" data-bs-toggle="dropdown">
                                    <i class="linearicons-cart"></i>
                                    <span class="cart_count">{{ count(session('cart', [])) }}</span>
                                </a>
                                <div class="cart_box dropdown-menu dropdown-menu-right">
                                    <ul class="cart_list">
                                        @forelse (session('cart', []) as $id => $product)
                                            <li>
                                                <a href="{{ route('cart.remove', $id) }}" class="item_remove"><i
                                                        class="ion-close"></i></a>
                                                <a href="#">
                                                    <img src="{{ $product['image'] ?? asset('assets/images/default.jpg') }}"
                                                        alt="{{ $product['name'] }}">
                                                    {{ $product['name'] }}
                                                </a>
                                                <span class="cart_quantity">
                                                    {{ $product['quantity'] }} x
                                                    <span class="cart_amount">
                                                        <span class="price_symbole">₫</span>
                                                        {{ number_format($product['price']) }}
                                                    </span>
                                                </span>
                                            </li>
                                        @empty
                                            <li>
                                                <p class="text-center">Giỏ hàng trống</p>
                                            </li>
                                        @endforelse
                                    </ul>
                                    <div class="cart_footer">
                                        <p class="cart_total"><strong>Tổng phụ:</strong>
                                            <span class="cart_price">
                                                <span class="price_symbole">₫</span>
                                                {{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, session('cart', [])))) }}
                                            </span>
                                        </p>
                                     {{-- <p class="cart_buttons">
                                            <a href="{{ route('cart.show') }}"
                                                class="btn btn-fill-line rounded-0 view-cart">Xem giỏ hàng</a>
                                            <a href="{{ route('cart.checkout') }}"
                                                class="btn btn-fill-out rounded-0 checkout">Thanh toán</a>
                                        </p> --}}
                                    </div>
                                </div>
                            </li>
                        @endauth


                    </ul>
            </nav>
        </div>
    </div>
</header>
<!-- END HEADER -->
