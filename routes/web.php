<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;



// Route::get('/', function () {
//     //return view('client.home');
//     return view('admin.dashboard');
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


Route::get('/products/filter', [ProductController::class, 'filterProducts'])->name('products.filter');


Route::get('/accessories/straps', [AccessoriesController::class, 'showStraps'])->name('accessories.straps');
Route::get('/accessories/boxes', [AccessoriesController::class, 'showBoxes'])->name('accessories.boxes');
Route::get('/accessories/glasses', [AccessoriesController::class, 'showGlasses'])->name('accessories.glasses');


// Trang bảo hành khách
Route::get('/warranty', [WarrantyController::class, 'showClient'])->name('warranty.form');

// Trang bảo hành admin
Route::get('/admin/warranty', [WarrantyController::class, 'showAdmin'])->middleware(['auth', 'role:admin'])->name('admin.warranty');

// Xử lý tra cứu từ form
Route::post('/warranty/lookup', [WarrantyController::class, 'lookup'])->name('warranty.lookup');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


Route::get('/accessories', [AccessoriesController::class, 'index'])->name('accessories.index');

Route::get('/login', function () {
    return view('client.login'); // nếu có file login.blade.php
})->name('login');