<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// Trang chủ
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'user') {
            return redirect()->route('client.home');
        }
    }
    return view('client.home'); // fallback nếu chưa login
})->name('home');

// ================== PRODUCTS ==================
Route::get('/products/filter', [ProductController::class, 'filterProducts'])->name('products.filter');
Route::get('/products/category/{slug}', [ProductController::class, 'byCategory'])->name('products.byCategory');
Route::get('/quick-view/{slug}', [ProductController::class, 'quickView']);
// phải để sau cùng để tránh nuốt route
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// ================== ACCESSORIES ==================
Route::prefix('accessories')->group(function () {
    Route::get('/straps', [AccessoriesController::class, 'showStraps'])->name('accessories.straps');
    Route::get('/boxes', [AccessoriesController::class, 'showBoxes'])->name('accessories.boxes');
    Route::get('/glasses', [AccessoriesController::class, 'showGlasses'])->name('accessories.glasses');

    Route::get('/quick-view/{type}/{id}', [AccessoriesController::class, 'quickView']);
});

// ================== AUTH ==================
Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('client.login');
Route::post('/client/register', [LoginAuthController::class, 'register'])->name('client.register');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('client.home');
    })->name('client.home');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// ================== WARRANTY ==================
Route::get('/warranty', [WarrantyController::class, 'showClient'])->name('warranty.form');
Route::get('/admin/warranty', [WarrantyController::class, 'showAdmin'])->middleware(['auth', 'role:admin'])->name('admin.warranty');
Route::post('/warranty/lookup', [WarrantyController::class, 'lookup'])->name('warranty.lookup');

// ================== ADMIN ACCESSORIES ==================
Route::prefix('admin/accessories')->name('admin.accessories.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/straps',  [AccessoriesController::class, 'adminStraps'])->name('straps');
    Route::get('/boxes',   [AccessoriesController::class, 'adminBoxes'])->name('boxes');
    Route::get('/glasses', [AccessoriesController::class, 'adminGlasses'])->name('glasses');
    Route::get('/', [AccessoriesController::class, 'index'])->name('index');

    Route::post('/{type}/store',   [AccessoriesController::class, 'store'])->name('store');
    Route::put('/{type}/{id}',     [AccessoriesController::class, 'update'])->name('update');
    Route::delete('/{type}/{id}',  [AccessoriesController::class, 'delete'])->name('delete');
});

// ================== ADMIN PRODUCTS ==================
Route::prefix('admin/products')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('admin.products_index');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});
Route::post('/admin/create', [ProductController::class, 'store'])->name('admin.store');

// ================== CART & CHECKOUT ==================
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

// Xóa giỏ hàng nhanh
Route::get('/cart/clear', function () {
    session()->forget('cart');
    return 'Đã xoá giỏ hàng';
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

// ================== SEARCH ==================
Route::get('/search', [ProductController::class, 'unifiedSearch'])->name('search.all');