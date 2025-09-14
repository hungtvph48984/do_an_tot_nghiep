@extends('clients.layouts.master')

@section('title', 'Trang Chủ')

@section('content')

<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Contact</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active">Contact</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION CONTACT -->
<div class="section pb_70">
	<div class="container">
        <div class="row">
            <div class="col-xl-4 col-md-6">
            	<div class="contact_wrap contact_style3">
                    <div class="contact_icon">
                        <i class="linearicons-map2"></i>
                    </div>
                    <div class="contact_text">
                        <span>Address</span>
                        <p>Cao Đẳng FPT Polytechnic Hà Nội</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
            	<div class="contact_wrap contact_style3">
                    <div class="contact_icon">
                        <i class="linearicons-envelope-open"></i>
                    </div>
                    <div class="contact_text">
                        <span>Email Address</span>
                        <a href="mailto:info@sitename.com">Minite@mail.com </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
            	<div class="contact_wrap contact_style3">
                    <div class="contact_icon">
                        <i class="linearicons-tablet2"></i>
                    </div>
                    <div class="contact_text">
                        <span>Phone</span>
                        <p>+ 457 789 789 65</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION CONTACT -->

<!-- START SECTION CONTACT -->
<div class="section pt-0">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-6">
            	<div class="heading_s1">
                	<h2>Phản hồi về shop</h2>
                </div>
                <p class="leads">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. Nullam id varius nunc id varius nunc.</p>
                <div class="field_form">
                   <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Họ và tên</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nhập họ và tên" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Nhập số điện thoại" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="message">Lời nhắn</label>
                            <textarea class="form-control" name="message" id="message" rows="4" placeholder="Nhập nội dung phản hồi..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-fill-out btn-block">Gửi phản hồi</button>
                    </form>



                </div>
            </div>
            <div class="col-lg-6 pt-2 pt-lg-0 mt-4 mt-lg-0">
            	<div id="map" class="contact_map2" data-zoom="12" data-latitude="40.680" data-longitude="-73.945" data-icon="assets/images/marker.png"></div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION CONTACT -->

@endsection
