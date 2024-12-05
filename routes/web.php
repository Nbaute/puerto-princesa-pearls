<?php

use App\Livewire\CreateProductLivewire;
use App\Livewire\CreateShopLivewire;
use App\Livewire\ForgotPasswordLivewire;
use App\Livewire\Guest\ContactUsLivewire;
use App\Livewire\Guest\HomeLivewire;
use App\Livewire\Guest\LoginLivewire;
use App\Livewire\Guest\ShopsLivewire;
use App\Livewire\Guest\ViewProductLivewire;
use App\Livewire\Guest\ViewShopLivewire;
use App\Livewire\GuestHomeLivewire;
use App\Livewire\MyCartLivewire;
use App\Livewire\OrderLivewire;
use App\Livewire\OrdersLivewire;
use App\Livewire\ProfileLivewire;
use App\Livewire\RegisterLivewire;
use App\Livewire\SearchShopsAndItemsLivewire;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

// Route::get('/', function () {
//     return redirect('admin');
// });

Route::get('/',  HomeLivewire::class)->name('guest.home');
Route::get('/search',  SearchShopsAndItemsLivewire::class)->name('search');
Route::get('/shops',  ShopsLivewire::class)->name('guest.shops');
Route::get('/shops/{username}',  ViewShopLivewire::class)->name('guest.view-shop');
Route::get('/shops/{username}/products/{productId}',  ViewProductLivewire::class)->name('guest.view-product');
Route::get('/products/{productId}',  ViewProductLivewire::class)->name('guest.view-product-by-id');

Route::get('/view-shop',  ViewShopLivewire::class)->name('guest.view-shop');
Route::get('/contact-us',  ContactUsLivewire::class)->name('guest.contact-us');
Route::get('/login',  LoginLivewire::class)->name('login');
Route::get('/forgot-password',  ForgotPasswordLivewire::class)->name('guest.forgot-password');
Route::get('/register',  RegisterLivewire::class)->name('guest.register');

Route::get('/faqs', function () {});
Route::get('/about', function () {});
Route::get('/contact', function () {});
Route::get('/privacy', function () {});
Route::get('/terms', function () {});
Route::get('health', HealthCheckResultsController::class);

Route::group(['middleware' => ['auth',]], function () {
    Route::get('/my-cart',  MyCartLivewire::class)->name('my-cart');
    Route::get('/profile',  ProfileLivewire::class)->name('profile');
    Route::get('/orders',  OrdersLivewire::class)->name('orders');
    Route::get('/orders/{transactionCode}',  OrderLivewire::class)->name('order');

    Route::get('/my/shops',  ShopsLivewire::class)->name('my.shops');
    Route::get('/my/shops/create',  CreateShopLivewire::class)->name('my.shops.create');
    Route::get('/my/shops/{username}',  ViewShopLivewire::class)->name('my.view-shop');
    Route::get('/my/shops/{username}/products/create',  CreateProductLivewire::class)->name('my.products.create');
    Route::get('/my/shops/{username}/products/{productId}',  ViewProductLivewire::class)->name('my.view-product');
}); 