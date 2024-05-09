<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\AuthApiMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login.index');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth.api')->post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth.api')->get('/Start', [StartController::class, 'index'])->name('start.index');
    
    // Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    // Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    // Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    // Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    // Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    // Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::middleware('auth.api')->resource('products', ProductController::class);
    
    Route::middleware('auth.api')->controller(ProductController::class)->group(function () {
        Route::get('/products/orderbyname', 'orderby')->name('products.orderbyname');
    }); 

    Route::middleware('auth.api')->resource('suppliers', SupplierController::class);
    Route::middleware('auth.api')->resource('projects', ProjectController::class);

    
