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

<<<<<<< HEAD


// Route::get('/', function () {
//     if (request()->has('checkout')) {
//         return view('client.checkout');
//     }

//     return view('client.home');
// });

<<<<<<< HEAD
=======

Route::get('/login', function () {
    return view('client.login'); // đúng tên file chị đã có
})->name('login');

>>>>>>> 24c700b1b99cc6031d36bdcc554af910fe6df928
Route::get('/', function () { 
=======
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
>>>>>>> 3366b25ca99a902aa845f5804fc5ec5e7ab4a42d
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.home');
    }
    return view('client.home');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('client.login.submit');
Route::post('/client/register', [LoginAuthController::class, 'register'])->name('client.register');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', fn () => view('client.home'))->name('client.home');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/
Route::get('/products/filter', [ProductController::class, 'filterProducts'])->name('products.filter');
Route::get('/products/category/{slug}', [ProductController::class, 'byCategory'])->name('products.byCategory');
Route::get('/quick-view/{slug}', [ProductController::class, 'quickView']);
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
| ACCESSORIES (Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin/accessories')->name('admin.accessories.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/',        [AccessoriesController::class, 'index'])->name('index');
    Route::get('/straps',  [AccessoriesController::class, 'adminStraps'])->name('straps');
    Route::get('/boxes',   [AccessoriesController::class, 'adminBoxes'])->name('boxes');
    Route::get('/glasses', [AccessoriesController::class, 'adminGlasses'])->name('glasses');

    Route::post('/{type}/store',  [AccessoriesController::class, 'store'])->name('store');
    Route::put('/{type}/{id}',    [AccessoriesController::class, 'update'])->name('update');
    Route::delete('/{type}/{id}', [AccessoriesController::class, 'delete'])->name('delete');
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
<<<<<<< HEAD
//update giỏ hàng
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
=======

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/
>>>>>>> 3366b25ca99a902aa845f5804fc5ec5e7ab4a42d
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

/* Coupons
   Xem danh sách mã: ai cũng xem được
   Áp dụng hoặc bỏ mã: bắt buộc đăng nhập
*/
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

<<<<<<< HEAD
<<<<<<< HEAD
//thanh toan
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
=======
>>>>>>> 24c700b1b99cc6031d36bdcc554af910fe6df928

//Admin product
Route::prefix('admin/products')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('admin.products_index');
=======
/*
|--------------------------------------------------------------------------
| ADMIN PRODUCTS
|--------------------------------------------------------------------------
*/
Route::prefix('admin/products')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/',        [ProductController::class, 'index'])->name('admin.products_index');
>>>>>>> 3366b25ca99a902aa845f5804fc5ec5e7ab4a42d
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/{id}',    [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});
Route::post('/admin/create', [ProductController::class, 'store'])->name('admin.store');

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| ADMIN PROMOTIONS
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('promotions',       [PromotionsController::class, 'index'])->name('admin.promotions_index');
    Route::post('promotions',      [PromotionsController::class, 'store'])->name('admin.promotions.store');
    Route::put('promotions/{id}',  [PromotionsController::class, 'update'])->name('admin.promotions.update');
    Route::delete('promotions/{id}', [PromotionsController::class, 'destroy'])->name('admin.promotions.delete');
});
=======

//Admin promotion
Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('promotions', [PromotionsController::class,'index'])->name('admin.promotions_index');
    Route::post('promotions', [PromotionsController::class,'store'])->name('admin.promotions.store');
    Route::put('promotions/{id}', [PromotionsController::class,'update'])
         ->name('admin.promotions.update');
    Route::delete('promotions/{id}', [PromotionsController::class,'destroy'])->name('admin.promotions.delete');
});


//thanh toan
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
>>>>>>> 24c700b1b99cc6031d36bdcc554af910fe6df928
