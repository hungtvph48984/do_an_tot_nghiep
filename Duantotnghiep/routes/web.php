<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\LoginClientController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admins.layouts.master');
    });

    // Nhóm route sản phẩm
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');

    //  Nhóm route danh mục
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index'); // admin.categories.index
        Route::get('/create', [CategoryController::class, 'create'])->name('create'); // admin.categories.create
        Route::post('/', [CategoryController::class, 'store'])->name('store'); // admin.categories.store
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit'); // admin.categories.edit
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update'); // admin.categories.update
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy'); // admin.categories.destroy
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home.index'); // Trang chủ
Route::get('/detail/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/product/get-variant', [ProductController::class, 'getVariant'])->name('product.getVariant');
//Route đăng nhập
Route::get('/login/client', [LoginClientController::class, 'showLoginForm'])->name('login');
Route::post('/login/client', [LoginClientController::class, 'login'])->name('client.login.submit');
Route::post('/logout/client', [LoginClientController::class, 'logout'])->name('client.logout');

//Route đăng ký tài khoản khách hàng
Route::get('/register/client', [RegisterController::class, 'showRegistrationForm'])->name('client.register');
Route::post('/register/client', [RegisterController::class, 'register'])->name('client.register.submit');

// Các route cần đăng nhập
Route::middleware('auth')->group(function () {

    // Thêm sản phẩm vào giỏ hàng
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    //hiển thị form thanh toán
    Route::get('/cart/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout');
    //Thanh toán
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout.post');
     Route::get('/order', [OrderController::class, 'index'])->name('orders.index');   // tất cả đơn hàng
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
});
