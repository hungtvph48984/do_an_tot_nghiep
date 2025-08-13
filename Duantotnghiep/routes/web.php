<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
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

// Các route cần đăng nhập
Route::middleware('auth')->group(function () {

    // Thêm sản phẩm vào giỏ hàng
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
  // Giỏ hàng
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    //hiển thị form thanh toán
    Route::get('/cart/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout');
    //Thanh toán
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout.post');
});
