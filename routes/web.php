<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| HOME + AUTH
|--------------------------------------------------------------------------
*/

// /login
Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('client.login.submit');
Route::post('/client/register', [LoginAuthController::class, 'register'])->name('client.register');

Route::get('/reset_pass', [LoginAuthController::class, 'showResetForm'])->name('client.reset_pass');
Route::post('client/reset_pass', [LoginAuthController::class, 'resetDirect'])->name('client.pass_update');

// Trang /
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.home');
    }
    return view('client.home');
})->name('home');

// logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');

/*
|--------------------------------------------------------------------------
| CLIENT DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', fn () => view('client.home'))->name('client.home');
});

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
});


//ADMIN PRODUCTS
Route::prefix('admin/products')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/',           [ProductController::class, 'index'])->name('admin.products_index');
    Route::get('/{id}/edit',  [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/{id}',       [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/{id}',    [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // toggle ẩn/hiện
    Route::post('/toggle/{id}', [ProductController::class, 'toggleHidden'])->name('admin.products.toggle');
});

Route::post('/admin/create', [ProductController::class, 'store'])->name('admin.store');




//Admin promotion
Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('promotions', [PromotionsController::class,'index'])->name('admin.promotions_index');
    Route::post('promotions', [PromotionsController::class,'store'])->name('admin.promotions.store');
    Route::put('promotions/{id}', [PromotionsController::class,'update'])
         ->name('admin.promotions.update');
    Route::delete('promotions/{id}', [PromotionsController::class,'destroy'])->name('admin.promotions.delete');
});



// ACCESSORIES (Admin)
// Route::prefix('admin/accessories')->name('admin.accessories.')->middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/',        [AccessoriesController::class, 'index'])->name('index');
//     Route::get('/straps',  [AccessoriesController::class, 'adminStraps'])->name('straps');
//     Route::get('/boxes',   [AccessoriesController::class, 'adminBoxes'])->name('boxes');
//     Route::get('/glasses', [AccessoriesController::class, 'adminGlasses'])->name('glasses');

//     Route::post('/{type}/store',  [AccessoriesController::class, 'store'])->name('store');
//     Route::put('/{type}/{id}',    [AccessoriesController::class, 'update'])->name('update');
//     Route::delete('/{type}/{id}', [AccessoriesController::class, 'delete'])->name('delete');
// });

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

    // Trang index chung (mặc định dây đeo) – menu dùng route('admin.accessories.index')
    Route::get('/accessories', [AccessoriesController::class, 'adminStraps'])
        ->name('accessories.index');

    // Từng loại
    Route::get('/accessories/straps', [AccessoriesController::class, 'adminStraps'])
        ->name('accessories.straps');

    Route::get('/accessories/boxes', [AccessoriesController::class, 'adminBoxes'])
        ->name('accessories.boxes');

    Route::get('/accessories/glasses', [AccessoriesController::class, 'adminGlasses'])
        ->name('accessories.glasses');

    // Thêm / sửa / xoá
    Route::post('/accessories/{type}', [AccessoriesController::class, 'store'])
        ->name('accessories.store');

    Route::put('/accessories/{type}/{id}', [AccessoriesController::class, 'update'])
        ->name('accessories.update');

    Route::delete('/accessories/{type}/{id}', [AccessoriesController::class, 'delete'])
        ->name('accessories.delete');

    Route::post('/accessories/toggle/{type}/{id}', [AccessoriesController::class, 'toggleHidden'])
        ->name('accessories.toggle');
});


/*
|--------------------------------------------------------------------------
| ADMIN ORDER
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('orders',          [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{id}',     [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('orders/{id}/edit',[OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('orders/{id}',     [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('orders/{id}',  [OrderController::class, 'destroy'])->name('admin.orders.delete');
});


/*
|--------------------------------------------------------------------------
| PRODUCTS CLIENT
|--------------------------------------------------------------------------
*/
Route::get('/products/filter', [ProductController::class, 'filterProducts'])->name('products.filter');
Route::get('/products/category/{slug}', [ProductController::class, 'byCategory'])->name('products.byCategory');
Route::get('/product/{product:slug}', [ProductController::class, 'showDetail'])->name('product.detail');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| ACCESSORIES (Client)
|--------------------------------------------------------------------------
*/
Route::prefix('accessories')->group(function () {
    Route::get('/straps',  [AccessoriesController::class, 'showStraps'])->name('accessories.straps');
    Route::get('/boxes',   [AccessoriesController::class, 'showBoxes'])->name('accessories.boxes');
    Route::get('/glasses', [AccessoriesController::class, 'showGlasses'])->name('accessories.glasses');

    Route::get('/quick-view/{type}/{id}', [AccessoriesController::class, 'quickView']);
});


/*
|--------------------------------------------------------------------------
| WARRANTY
|--------------------------------------------------------------------------
*/
Route::get('/warranty', [WarrantyController::class, 'showClient'])->name('warranty.form');
Route::get('/admin/warranty', [WarrantyController::class, 'showAdmin'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.warranty');
Route::post('/warranty/lookup', [WarrantyController::class, 'lookup'])->name('warranty.lookup');

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', function () {
    session()->forget('cart');
    return 'Đã xoá giỏ hàng';
});

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

/* Coupons */
Route::get('/coupons/available', [CheckoutController::class, 'availableCoupons'])->name('checkout.availableCoupons');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])
    ->middleware('auth')
    ->name('checkout.applyCoupon');
Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])
    ->middleware('auth')
    ->name('checkout.removeCoupon');

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
Route::get('/search', [ProductController::class, 'unifiedSearch'])->name('search.all');

/*
|--------------------------------------------------------------------------
| HISTORY ORDER
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

