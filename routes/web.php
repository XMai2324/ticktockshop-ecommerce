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



// Route::get('/', function () {
//     if (request()->has('checkout')) {
//         return view('client.checkout');
//     }

//     return view('client.home');
// });


Route::get('/login', function () {
    return view('client.login'); // đúng tên file chị đã có
})->name('login');

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
    
Route::get('/products', [ProductController::class, 'filterProducts'])->name('products.filter');
Route::get('/quick-view/{slug}', [ProductController::class, 'quickView']);
Route::get('/accessories/quick-view/{type}/{id}', [AccessoriesController::class, 'quickView']);




    //User routes
Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('client.login');
Route::post('/client/register', [LoginAuthController::class, 'register'])->name('client.register');


Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('client.home');
    })->name('client.home');
});

//Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


// Trang bảo hành khách
Route::get('/warranty', [WarrantyController::class, 'showClient'])->name('warranty.form');

// Trang bảo hành admin
Route::get('/admin/warranty', [WarrantyController::class, 'showAdmin'])->middleware(['auth', 'role:admin'])->name('admin.warranty');

// Xử lý tra cứu từ form
Route::post('/warranty/lookup', [WarrantyController::class, 'lookup'])->name('warranty.lookup');




// Client
Route::prefix('accessories')->group(function () {
    Route::get('/straps', [AccessoriesController::class, 'showStraps'])->name('accessories.straps');
    Route::get('/boxes', [AccessoriesController::class, 'showBoxes'])->name('accessories.boxes');
    Route::get('/glasses', [AccessoriesController::class, 'showGlasses'])->name('accessories.glasses');
});


//ADMIN accessories
Route::prefix('admin/accessories')->name('admin.accessories.')->group(function () {
    // List theo loại (view: admin.accessories_index)
    Route::get('/straps',  [AccessoriesController::class, 'adminStraps'])->name('straps');
    Route::get('/boxes',   [AccessoriesController::class, 'adminBoxes'])->name('boxes');
    Route::get('/glasses', [AccessoriesController::class, 'adminGlasses'])->name('glasses');

    // Trang tổng (nếu có)
    Route::get('/', [AccessoriesController::class, 'index'])->name('index');

    // CRUD
    Route::post('/{type}/store',   [AccessoriesController::class, 'store'])->name('store');
    Route::put('/{type}/{id}',     [AccessoriesController::class, 'update'])->name('update');
    Route::delete('/{type}/{id}',  [AccessoriesController::class, 'delete'])->name('delete');
});


// Giỏ hàng customer
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

// Xóa giỏ hàng 
Route::get('/cart/clear', function () {
    session()->forget('cart');
    return 'Đã xoá giỏ hàng';
});
//update giỏ hàng
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');


Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
// Lịch sử tìm kiếm
Route::get('/search', [ProductController::class, 'unifiedSearch'])->name('search.all');


//Admin product
Route::prefix('admin/products')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('admin.products_index');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('admin.products.update'); // ✅ thêm dòng này
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
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


//thanh toan
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
