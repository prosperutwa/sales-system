<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthBiovetUser;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'loginPage'])->name('login.page');
Route::post('/login-auth', [App\Http\Controllers\Auth\LoginController::class, 'loginAuth'])->name('login.auth');


Route::middleware([AuthBiovetUser::class])->group(function () {
    
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/products', [App\Http\Controllers\Admin\ProductsController::class, 'index'])->name('products');
    Route::post('/products/store', [App\Http\Controllers\Admin\ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/update', [App\Http\Controllers\Admin\ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/update', [App\Http\Controllers\Admin\ProductsController::class, 'destroy'])->name('products.delete');
    Route::post('/products/add-quantity', [App\Http\Controllers\Admin\ProductsController::class, 'addQuantity'])->name('products.addQty');

});


