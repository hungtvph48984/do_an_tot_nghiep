<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\AttributeController;

use App\Http\Controllers\BrandController as ClientBrandController;
use App\Http\Controllers\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\LoginClientController;
use App\Http\Controllers\Client\RegisterController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// ================== ADMIN ROUTES ==================
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/', function () {
        return view('admins.layouts.master');
    });

    // Thuộc tính (Size + Color)
    Route::get('/attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::post('/attributes/sizes', [AttributeController::class, 'storeSize'])->name('attributes.sizes.store');
    Route::put('/attributes/sizes/{size}', [AttributeController::class, 'updateSize'])->name('attributes.sizes.update');
    Route::delete('/attributes/sizes/{size}', [AttributeController::class, 'destroySize'])->name('attributes.sizes.destroy');
    Route::post('/attributes/colors', [AttributeController::class, 'storeColor'])->name('attributes.colors.store');
    Route::put('/attributes/colors/{color}', [AttributeController::class, 'updateColor'])->name('attributes.colors.update');
    Route::delete('/attributes/colors/{color}', [AttributeController::class, 'destroyColor'])->name('attributes.colors.destroy');

    // Voucher
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::get('/generate-code', [VoucherController::class, 'generateCode'])->name('generateCode');
        Route::post('/bulk-action', [VoucherController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
    });

    // Brands (Admin)
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', [AdminBrandController::class, 'index'])->name('index');
        Route::get('/create', [AdminBrandController::class, 'create'])->name('create');
        Route::post('/', [AdminBrandController::class, 'store'])->name('store');
        Route::get('/{brand}', [AdminBrandController::class, 'show'])->name('show');
        Route::get('/{brand}/edit', [AdminBrandController::class, 'edit'])->name('edit');
        Route::put('/{brand}', [AdminBrandController::class, 'update'])->name('update');
        Route::delete('/{brand}', [AdminBrandController::class, 'destroy'])->name('destroy');

        // Liên kết sản phẩm
        Route::get('/{brand}/link-products', [AdminBrandController::class, 'showLinkProducts'])->name('link-products');
        Route::post('/{brand}/link-products', [AdminBrandController::class, 'linkProducts'])->name('store-link-products');
    });

    // Category (Admin)
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('/hidden', [CategoryController::class, 'hidden'])->name('hidden');
        Route::get('/{category}/show', [CategoryController::class, 'show'])->name('show');
    });

    // Products (Admin)
    Route::get('/products/hidden', [AdminProductController::class, 'hidden'])->name('products.hidden');
    Route::patch('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::resource('products', AdminProductController::class)->whereNumber('product');

    // Contacts (Admin)
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminContactController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [AdminContactController::class, 'reply'])->name('reply');
    });

    // Orders (Admin)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{id}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        Route::put('/{id}', [AdminOrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });

    // Users (Admin)
    Route::prefix('users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
    });
});

// ================== AUTH ==================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// ================== CLIENT ROUTES ==================

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home.index'); 

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
Route::get('/products/{id}', [ProductController::class, 'details'])->name('products.details');
Route::get('/detail/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/product/get-variant', [ProductController::class, 'getVariant'])->name('product.getVariant');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('details');

// Categories (Client)
Route::get('/category/{id}', [ClientCategoryController::class, 'show'])->name('category.show');
Route::get('/category/{id}/filter', [ClientCategoryController::class, 'filter'])->name('category.filter');

// Brands (Client)
Route::get('/brands', [ClientBrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{id}', [ClientBrandController::class, 'show'])->name('brands.show');

// Search
Route::get('/search', [ProductController::class, 'search'])->name('product.search');

// Auth Client
Route::get('/login/client', [LoginClientController::class, 'showLoginForm'])->name('client.login');
Route::post('/login/client', [LoginClientController::class, 'login'])->name('client.login.submit');
Route::post('/logout/client', [LoginClientController::class, 'logout'])->name('client.logout');

Route::get('/register/client', [RegisterController::class, 'showRegistrationForm'])->name('client.register');
Route::post('/register/client', [RegisterController::class, 'register'])->name('client.register.submit');

// Wishlist
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Cart
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout.post');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/filter', [OrderController::class, 'filterOrders'])->name('orders.filter');
    Route::put('/orders/{id}/confirm-received', [OrderController::class, 'confirmReceived'])->name('orders.confirmReceived');
    Route::put('/orders/{id}/request-return', [OrderController::class, 'requestReturn'])->name('orders.requestReturn');

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/apply-coupon', [CouponController::class, 'apply'])->name('cart.applyCoupon');

    // Contact
    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
    Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
    Route::prefix('my-contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactController::class, 'myContacts'])->name('my'); 
        Route::get('/{id}', [ContactController::class, 'show'])->name('show'); 
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profile/orders', [OrderController::class, 'profileOrders'])->name('profile.orders');
});
