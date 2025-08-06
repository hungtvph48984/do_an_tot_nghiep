<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Middleware\AdminMiddleware;


// ✅ Các route nhóm dành cho admin
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/', function () {
        return view('admins.layouts.master');
    });

    // ✅ Route sản phẩm: admin.product.index
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');

    // ✅ Route danh mục: admin.categories.index, admin.categories.create, ...
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

     Route::prefix('users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
    });


});

// ✅ Đăng nhập
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');



// ✅ Các route dành cho client

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\Client\LoginClientController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\ProfileController;

Route::get('/client', [ClientController::class, 'index'])->name('client.index');
// Route::get('/about', [ClientController::class, 'about']);
Route::get('/about', [ClientController::class, 'about'])->name('client.about');
Route::get('/index_2', [ClientController::class, 'index_2'])->name('client.index_2');
Route::get('/contact', [ClientController::class, 'contact'])->name('client.contact');


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
});







