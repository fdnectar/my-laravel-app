<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
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

        Route::controller(ClientController::class)->group(function() {
            Route::get('/all-clients', 'index')->name('all-clients');
            Route::get('/add-clients', 'create')->name('add-clients');
            Route::post('/store-client', 'storeCLient')->name('store-client');
            Route::get('/edit-client', 'editClient')->name('edit-client');
            Route::post('/update-client', 'updateClient')->name('update-client');
            Route::post('/delete-client', 'deleteClient')->name('delete-client');
        });
        
        Route::controller(InvoiceController::class)->group(function() {
            Route::get('/all-invoices', 'index')->name('all-invoices');
            Route::get('/add-invoice', 'create')->name('add-invoice');
            Route::post('/store-invoice', 'storeInvoice')->name('store-invoice');
            Route::get('/invoice/{id}/download', 'downloadPDF')->name('invoice-download');
            Route::get('/edit-invoice', 'editInvoice')->name('edit-invoice');
            Route::post('/update-invoice', 'updateInvoice')->name('update-invoice');

            Route::post('/delete-invoice', 'deleteInvoice')->name('delete-invoice');
        });
    });
});




