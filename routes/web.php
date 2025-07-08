<?php
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
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
