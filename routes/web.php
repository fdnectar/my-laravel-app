<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('admin')->name('admin.')->group(function() {
    Route::middleware(['guest'])->group(function() {
        Route::controller(AuthController::class)->group(function() {
            Route::get('/login', 'loginForm')->name('login');
            Route::post('/login', 'loginHandler')->name('login_handler');
        });
    });

    Route::middleware(['auth'])->group(function() {
        Route::controller(AdminController::class)->group(function() {
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');
            Route::post('/logout', 'logoutHandler')->name('logout');
        });

        Route::controller(ProductController::class)->group(function() {
            // Product Routes
            Route::get('/all-products', 'viewProdut')->name('all-products');
            Route::get('/add-product', 'addProduct')->name('add-product');
            Route::post('/store-product', 'storeProduct')->name('store-product');
            Route::get('/edit-product', 'editProduct')->name('edit-product');
            Route::post('/update-product', 'updateProduct')->name('update-product');

            Route::post('/upload-product-images', 'uploadProductImages')->name('upload-product-images');
            Route::get('/get-product-images', 'getProductImages')->name('get-product-images');
            Route::post('/delete-product-images', 'deleteProductImages')->name('delete-product-images');
            Route::post('/delete-product', 'deleteProduct')->name('delete-product');

            Route::get('/cart-items', 'showCartItems')->name('cart-items');
            Route::get('/order-items', 'showOrderItems')->name('order-items');
        });
    });
});


Route::controller(HomeController::class)->group(function() {
    Route::get('/', 'homePage')->name('/');
    Route::get('/product-details', 'productDetails')->name('product-details');
    Route::get('shoppingcart', 'shoppingCartView')->name('shoppingcart');
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::get('/checkout-shipping', [CheckoutController::class, 'showShippingForm'])->name('checkout.showShippingForm');

Route::post('/checkout/shipping', [CheckoutController::class, 'handleShippingForm'])->name('checkout.handleShippingForm');

Route::get('/checkout/payment', [CheckoutController::class, 'showPaymentForm'])->name('checkout.showPaymentForm');
Route::post('/checkout/payment', [CheckoutController::class, 'handlePaymentForm'])->name('checkout.handlePaymentForm');

Route::get('/checkout/confirmation', [CheckoutController::class, 'checkoutConfirmation'])->name('checkout.confirmation');



