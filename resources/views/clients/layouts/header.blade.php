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

                <div class="search-container">
                    <form action="{{ route('product.search') }}" method="GET">
                        <input type="text" name="search" class="search-input" placeholder="Tìm kiếm sản phẩm..." required>
                        <button type="submit" class="search-btn">Tìm kiếm</button>
                    </form>
                </div>



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

                        <li class="">
                            <a class=" nav-link" href="#" data-bs-toggle="">SẢN PHẨM</a>
                        </li>

                        <li class="">
                            <a class=" nav-link" href="#" data-bs-toggle="">PAGE</a>
                        </li>

                        <li><a class="nav-link nav_item" href="{{ route('contact.form')}}">Liên hệ</a></li>

                        <li class="wishlist">
                            <a href="{{ route('wishlist.index') }}" class="nav-link cart_trigger" data-bs-toggle="">
                                <i class="ti-heart"></i>
                                <span class="wishlist-count">
                                    {{ count(session('wishlist', [])) }}
                                </span>
                            </a>
                        </li>




                    </ul>
                </div>
                    <ul class="navbar-nav attr-nav align-items-center">


                        @auth
                            <li class="dropdown cart_dropdown">
                                <a class="nav-link cart_trigger" href="{{ route('cart.show') }}" data-bs-toggle="">
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
                                      <p class="cart_buttons">
                                            <a href="{{ route('cart.show') }}"
                                                class="btn btn-fill-line rounded-0 view-cart">Xem giỏ hàng</a>
                                        </p> 
                                    </div>
                                </div>
                            </li>
                        @endauth

                        <ul class="header_list d-inline-flex align-items-center mb-0">

                            @if (Auth::check())
                                <li class="user-dropdown ms-3  ">
                                    <a href="#" class=" user">
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

                                            <form action="{{ route('wishlist.index') }}" method="GET">
                                                @csrf

                                                <button type="submit" class="dropdown-item">
                                                    <i class="ti-heart"></i> Yêu thích
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


                    </ul>
            </nav>
        </div>
    </div>
</header>

<style>
    /* CSS cho số lượng sản phẩm trong wishlist */
.wishlist-count {
    position: absolute;
    top: 17px;
    right: -9px;
    background-color: #FF324D; 
    color: #fff;
    font-size: 9px;
    font-weight: bold;
    padding: 1px 6px;
    border-radius: 50%;
}

.wishlist {
    position: relative;
    margin-right: 15px; 
}

/* Container cho thanh tìm kiếm */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin-bottom: 20px;
}

.search-input {
    padding: 10px;
    width: 210px;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
}

.search-btn {
   
    margin-left: 10px;
    border: none;
    background-color: #0d6efd;
    color: white;
    font-size: 16px;
    border-radius: 25px;
    cursor: pointer;
}

.search-btn:hover {
    background-color: #0056b3;
}



</style>
<!-- END HEADER -->
