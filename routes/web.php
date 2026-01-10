<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthBiovetUser;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'loginPage'])->name('login.page');
Route::post('/login-auth', [App\Http\Controllers\Auth\LoginController::class, 'loginAuth'])->name('login.auth');


Route::middleware([AuthBiovetUser::class])->group(function () {

    //dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');

    //products
    Route::get('admin/products', [App\Http\Controllers\Admin\ProductsController::class, 'index'])->name('products');
    Route::post('/products/store', [App\Http\Controllers\Admin\ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/update', [App\Http\Controllers\Admin\ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/update', [App\Http\Controllers\Admin\ProductsController::class, 'destroy'])->name('products.delete');
    Route::post('/products/add-quantity', [App\Http\Controllers\Admin\ProductsController::class, 'addQuantity'])->name('products.addQty');

    //customers
    Route::get('admin/customers', [App\Http\Controllers\Admin\CustomersController::class, 'index'])->name('customers');
    Route::post('admin/customers/store', [App\Http\Controllers\Admin\CustomersController::class, 'store'])->name('customers.store');
    Route::put('admin/customers/update', [App\Http\Controllers\Admin\CustomersController::class, 'update'])->name('customers.update');
    Route::delete('admin/customers/delete', [App\Http\Controllers\Admin\CustomersController::class, 'destroy'])->name('customers.delete');


     // Invoices
    Route::get('admin/invoices', [App\Http\Controllers\Admin\InvoicesController::class, 'index'])->name('invoices.index');
    Route::post('invoices/store', [App\Http\Controllers\Admin\InvoicesController::class, 'store'])->name('invoices.store');
    Route::get('/admin/products/json', function () {
        return \App\Models\Systems\BiovetTechProduct::where('remain_quantity', '>', 0)->get();
    });

   // Invoice actions
    Route::get('admin/invoices/view/{id}', [App\Http\Controllers\Admin\InvoicesController::class, 'view'])->name('invoices.view');
    Route::get('admin/invoices/download/{id}', [App\Http\Controllers\Admin\InvoicesController::class, 'download'])->name('invoices.download');
    Route::get('admin/invoices/print/{id}', [App\Http\Controllers\Admin\InvoicesController::class, 'print'])->name('invoices.print');
    Route::put('/admin/invoices/cancel/{invoice}', [App\Http\Controllers\Admin\InvoicesController::class, 'cancel'])->name('invoices.canceled');
    Route::post('/admin/invoices/pay', [App\Http\Controllers\Admin\InvoicesController::class, 'pay'])->name('invoices.pay');





});


