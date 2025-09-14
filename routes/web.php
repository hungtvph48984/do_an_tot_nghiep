<?php
<<<<<<< HEAD

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Middleware\AdminMiddleware;
=======
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\LoginClientController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Client\WishlistController;
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8


// ✅ Các route nhóm dành cho admin
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/', function () {
        return view('admins.layouts.master');
    });

<<<<<<< HEAD
    // ✅ Route sản phẩm: admin.product.index
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');

    // ✅ Route danh mục: admin.categories.index, admin.categories.create, ...
=======
    // Nhóm route voucher
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        // Route generateCode phải được đặt TRƯỚC các route có parameter
        Route::get('/generate-code', [VoucherController::class, 'generateCode'])->name('generateCode');
        Route::post('/bulk-action', [VoucherController::class, 'bulkAction'])->name('bulk-action');
        
        // Các route có parameter phải đặt sau
        Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
        Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');

        Route::get('admin/vouchers/generate-code', [VoucherController::class, 'generateCode'])->name('admin.vouchers.generateCode');

    });


    // Nhóm route nhãn hàng (brands)
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/', [BrandController::class, 'store'])->name('store');
        // Route bulk-action phải được đặt TRƯỚC các route có parameter
        Route::post('/bulk-action', [BrandController::class, 'bulkAction'])->name('bulk-action');
        
        // Các route có parameter phải đặt sau
        Route::get('/{brand}', [BrandController::class, 'show'])->name('show');
        Route::get('/{brand}/edit', [BrandController::class, 'edit'])->name('edit');
        Route::put('/{brand}', [BrandController::class, 'update'])->name('update');
        Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
    });

    // ✅ Route danh mục
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        // ẩn danh mục 
        Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('/categories/hidden', [CategoryController::class, 'hidden'])->name('categories.hidden');
        Route::get('/hidden', [CategoryController::class, 'hidden'])->name('hidden');
        // Xem chi tiết danh mục
        Route::get('/{category}/show', [CategoryController::class, 'show'])->name('show');

    });

<<<<<<< HEAD
=======
    // Nhóm route sản phẩm admin
    Route::resource('products', AdminProductController::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'show'    => 'products.show',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);


    // Route liên hệ
    Route::prefix('contacts')->name('contacts.')->group(function () {
    Route::get('/', [AdminContactController::class, 'index'])->name('index');
    Route::get('/{id}', [AdminContactController::class, 'show'])->name('show');
    Route::post('/{id}/reply', [AdminContactController::class, 'reply'])->name('reply');

    });
    // ✅ Route đơn hàng
    Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [AdminOrderController::class, 'index'])->name('index');
    Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
    Route::put('/{id}', [AdminOrderController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/update-status', [AdminOrderController::class, 'updateStatus'])
        ->name('updateStatus');
    });


    // ✅ Route user
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
     Route::prefix('users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
<<<<<<< HEAD
=======

        //route xem đơn hàng của user
        // Route::get('/{id}/orders', [UserController::class, 'orders'])->name('orders');
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    });


});

// ✅ Đăng nhập
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');


<<<<<<< HEAD

// ✅ Các route dành cho client

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\Client\LoginClientController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\client\CouponController;

Route::get('/client', [ClientController::class, 'index'])->name('client.index');
// Route::get('/about', [ClientController::class, 'about']);
Route::get('/about', [ClientController::class, 'about'])->name('client.about');
Route::get('/index_2', [ClientController::class, 'index_2'])->name('client.index_2');
Route::get('/contact', [ClientController::class, 'contact'])->name('client.contact');
Route::get('/checkout', [ClientController::class, 'checkout'])->name('client.checkout');


// ✅ Route đăng nhập
Route::get('/login/client', [LoginClientController::class, 'showLoginForm'])->name('client.login');
Route::post('/login/client', [LoginClientController::class, 'login'])->name('client.login.submit');
Route::post('/logout/client', [LoginClientController::class, 'logout'])->name('client.logout');

// ✅ Route đăng ký tài khoản khách hàng
Route::get('/register/client', [RegisterController::class, 'showRegistrationForm'])->name('client.register');
Route::post('/register/client', [RegisterController::class, 'register'])->name('client.register.submit');

// ✅ Route liên hệ
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

// ✅ Route profile người dùng
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

// ✅ Route thanh toán
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
// ✅ Route mã giảm giá
    Route::post('/apply-coupon', [CouponController::class, 'apply'])->name('apply.coupon');
});







=======
Route::get('/', [HomeController::class, 'index'])->name('home.index'); // Trang chủ
Route::get('/detail/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/product/get-variant', [ProductController::class, 'getVariant'])->name('product.getVariant');
//Route đăng nhập/ đăng xuất
Route::get('/login/client', [LoginClientController::class, 'showLoginForm'])->name('login');
Route::post('/login/client', [LoginClientController::class, 'login'])->name('client.login.submit');
Route::post('/logout/client', [LoginClientController::class, 'logout'])->name('client.logout');

//Route đăng ký tài khoản khách hàng
Route::get('/register/client', [RegisterController::class, 'showRegistrationForm'])->name('client.register');
Route::post('/register/client', [RegisterController::class, 'register'])->name('client.register.submit');

// Các route cần đăng nhập
Route::middleware('auth')->group(function () {


    // Wishlist
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Danh sách sản phẩm trong wishlist
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Thêm sản phẩm vào giỏ hàng
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    //hiển thị form thanh toán
    Route::get('/cart/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout');
    //Thanh toán
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout.post');
    // Danh sách đơn hàng
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');


    // Route thanh toán
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    // Route mã giảm giá
    Route::post('/apply-coupon', [CouponController::class, 'apply'])->name('apply.coupon');

    // ✅ Route liên hệ
    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
    Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
    Route::prefix('my-contacts')->name('contacts.')->middleware('auth')->group(function () {
        Route::get('/', [ContactController::class, 'myContacts'])->name('my'); 
        Route::get('/{id}', [ContactController::class, 'show'])->name('show'); 
    });


    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profile/orders', [OrderController::class, 'profileOrders'])->name('profile.orders');


});
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
