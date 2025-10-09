<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\AuthApiMiddleware;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EntranceController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatabaseController;

/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test-view', function () {
    // Verifica si la vista existe
    if (view()->exists('Login.index')) {
        return "✅ La vista login.index EXISTE";
    } else {
        return "❌ La vista login.index NO EXISTE";
    }
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth.api')->post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth.api')->get('/Start', [StartController::class, 'index'])->name('start.index');
Route::middleware('auth.api')->get('/start/get-data', [StartController::class, 'getData'])->name('start.getData');


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


Route::middleware('auth.api')->resource('categories', CategoryController::class);
Route::middleware('auth.api')->resource('suppliers', SupplierController::class);
Route::middleware('auth.api')->resource('projects', ProjectController::class);
Route::middleware('auth.api')->resource('entrances', EntranceController::class);
Route::middleware('auth.api')->resource('outputs', OutputController::class);
Route::middleware('auth.api')->resource('loans', LoanController::class);
Route::middleware('auth.api')->resource('users', UserController::class);
Route::middleware('auth.api')->get('/databases', [DatabaseController::class, 'index'])->name('databases.index');


Route::post('/entrances', [ProductController::class, 'storeEntrance'])->name('products.entrances.store');
Route::post('/products/outputs', [ProductController::class, 'storeOutPuts'])->name('products.outputs.store');
Route::post('/products/loans', [ProductController::class, 'storeLoans'])->name('products.loans.store');

Route::get('/products/{id}/loans', [ProductController::class, 'loansGet'])->name('products.loans.get');
Route::get('/products/{id}/output', [ProductController::class, 'outPutGet'])->name('products.output.get');


Route::get('/products/{id}/entrances', [ProductController::class, 'entrancesGet'])->name('products.entrances.get');
Route::post('/products/{id}/entrances', [ProductController::class, 'entrancesPost'])->name('products.entrances.post');
 Route::post('/products/{id}/output', [ProductController::class, 'outPutPost'])->name('products.output.post');



Route::middleware('auth.api')->resource('outputs', OutputController::class);
Route::middleware('auth.api')->resource('loans', LoanController::class);

