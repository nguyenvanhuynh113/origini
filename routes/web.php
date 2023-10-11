<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Hiển thị các sản phẩm -- --
Route::resource('/', \App\Http\Controllers\TrangchuController::class);
Route::get('/shop', [\App\Http\Controllers\TrangchuController::class, 'shop'])->name('shop');
Route::get('shop_details/{slug}', [\App\Http\Controllers\TrangchuController::class, 'shop_details'])
    ->name('shop_details');
Route::get('/category/{slug}', [\App\Http\Controllers\TrangchuController::class, 'category_product'])
    ->name('category');

// Thao tác với giỏ hàng ( người dùng đã đăng nhập )
Route::get('cart', [\App\Http\Controllers\TrangchuController::class, 'cart'])->name('cart');
Route::get('/add_to_cart/{id}', [\App\Http\Controllers\TrangchuController::class, 'add_to_cart'])->name('add_to_cart');
Route::delete('/delete_product_cart/{id}', [\App\Http\Controllers\TrangchuController::class, 'delete_product_cart'])
    ->name('delete_product_cart');
Route::patch('update_product_cart/{id}', [\App\Http\Controllers\TrangchuController::class, 'update_product_cart'])
    ->name('update_product_cart');

// thao tác với bài viết
Route::get('/blog', [\App\Http\Controllers\TrangchuController::class, 'blog'])->name('blog');
Route::get('/blog_details/{slug}', [\App\Http\Controllers\TrangchuController::class, 'blog_details'])->name('blog_details');
Route::get('blog-type/{slug}', [\App\Http\Controllers\TrangchuController::class, 'blog_type'])->name('blog-type');
// Thanh toán đơn hàng
Route::get('/checkout', [\App\Http\Controllers\TrangchuController::class, 'checkout'])->name('checkout');
Route::post('/payment', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('payment');

// Contact gởi mesage với admin
Route::get('/contact', [\App\Http\Controllers\TrangchuController::class, 'contact'])->name('contact');
//
Route::post('/message', [\App\Http\Controllers\TrangchuController::class, 'message'])->name('message');

// Hiển thị gợi ý tìm kiếm
Route::get('/search', [\App\Http\Controllers\TrangchuController::class, 'search'])->name('search');
Route::post('/search-product', [\App\Http\Controllers\TrangchuController::class, 'search_product'])
    ->name('search-product');
// Tìm kiếm bài viết
Route::post('search-blog', [\App\Http\Controllers\TrangchuController::class, 'search_blog'])->name('search-blog');

// Auth //
// Đăng xuất tài khoản người dùng //
Route::get('/logout', [\App\Http\Controllers\TrangchuController::class, 'logout']);

//Đăng ký
Route::get('get-register', [\App\Http\Controllers\TrangchuController::class, 'get_register'])
    ->name('get-register');
Route::post('/register', [\App\Http\Controllers\TrangchuController::class, 'register'])->name('register');

// Đăng nhập
Route::get('get-login', [\App\Http\Controllers\TrangchuController::class, 'get_login'])
    ->name('get-login');
Route::post('/login', [\App\Http\Controllers\TrangchuController::class, 'login'])->name('login');
