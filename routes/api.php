<?php

use App\Http\Controllers\Api\Admin\ApiFactorController;
use App\Http\Controllers\Api\Admin\ApiOrdersController;
use App\Http\Controllers\Api\Admin\ApiProductsController;
use App\Http\Controllers\Api\Admin\ApiUsersController;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\ApiRegisterController;
use App\Http\Controllers\Api\Buyer\ApiBuyerFactorController;
use App\Http\Controllers\Api\Buyer\ApiBuyerOrderController;
use App\Http\Controllers\Api\Buyer\ApiBuyerProfileController;
use App\Http\Controllers\Api\Seller\ApiSellerOrderController;
use App\Http\Controllers\Api\Seller\ApiSellerProuctsController;
use App\Mail\MyTestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// tests start

Route::get('/auth/{provider}/redirect', function ($provider) {
    $url = Socialite::driver($provider)->redirect();
    
});

Route::get('/auth/{provider}/callback', function ($provider) {
    $user = Socialite::driver($provider)->user();
    dd($user);
    // $user->token
});

Route::get('/testMail', function () {
    $name = 'Funny Coder';
    Mail::to('rastegarmohammadhosien@gmail.com')->send(new MyTestMail($name));
});

Route::get('test', [ApiUsersController::class, 'test'])->name('test');

//  tests end



Route::post('register', [ApiRegisterController::class, 'store'])->name('register');
Route::get('login', [ApiLoginController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    // -- Logout Route -- //
    Route::delete('logout', [ApiLoginController::class, 'destroy'])->name('logout');


    // -- Admin Panel Routes -- //
    Route::middleware('role:Admin')->prefix('admin')->group(function () {


        // -- Admin Panel access to users Routes -- //
        Route::get('users', [ApiUsersController::class, 'index']);
        Route::get('users/filter', [ApiUsersController::class, 'filter']);
        Route::get('users/show/{id}', [ApiUsersController::class, 'show']);
        Route::post('users/update/{id}', [ApiUsersController::class, 'update']);
        Route::get('users/destroy/{id}', [ApiUsersController::class, 'destroy']);


        // -- Admin Panel access to products Routes -- //
        Route::get('products', [ApiProductsController::class, 'index']);
        Route::post('products', [ApiProductsController::class, 'store']);
        Route::get('products/filter', [ApiProductsController::class, 'filter']);
        Route::get('products/show/{id}', [ApiProductsController::class, 'show']);
        Route::post('products/update/{id}', [ApiProductsController::class, 'update']);
        Route::get('products/destroy/{id}', [ApiProductsController::class, 'destroy']);


        // -- Admin Panel access to orders Routes -- //
        Route::get('orders', [ApiOrdersController::class, 'index']);
        Route::post('orders', [ApiOrdersController::class, 'store']);
        Route::get('orders/filter', [ApiOrdersController::class, 'filter']);
        Route::get('orders/show/{id}', [ApiOrdersController::class, 'show']);
        Route::post('orders/update/{id}', [ApiOrdersController::class, 'update']);
        Route::get('orders/destroy/{id}', [ApiOrdersController::class, 'destroy']);


        // -- Admin Panel access to factores Routes -- //
        Route::get('factors', [ApiFactorController::class, 'index']);
        Route::post('factors', [ApiFactorController::class, 'store']);
        Route::get('factors/filter', [ApiFactorController::class, 'filter']);
        Route::get('factors/show/{id}', [ApiFactorController::class, 'show']);
        Route::post('factors/update/{id}', [ApiFactorController::class, 'update']);
        Route::get('factors/destroy/{id}', [ApiFactorController::class, 'destroy']);
    });

    Route::middleware('role:Customer')->prefix('buyer')->group(function () {

        // -- Start Buyer profile Routs-- //
        Route::post('users/update/{id}', [ApiBuyerProfileController::class, 'update']);
        // -- Start Buyer profile Routs-- //


        // -- Start Buyer Orders Routs-- //
        Route::get('/orders', [ApiBuyerOrderController::class, 'index'])->name('orders.index');
        Route::post('/orders', [ApiBuyerOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/show/{id}', [ApiBuyerOrderController::class, 'show'])->name('orders.show');
        Route::any('/orders/{id}/edit', [ApiBuyerOrderController::class, 'edit'])->name('orders.edit');
        Route::any('/orders/update/{id}', [ApiBuyerOrderController::class, 'update'])->name('orders.update');
        Route::post('/orders/destroy/{id}/', [ApiBuyerOrderController::class, 'destroy'])->name('orders.destroy');
        // -- End Buyer Orders Routs-- //

        // // -- Start Buyer Products Routs-- //
        // Route::get('/products/{category_id}', [BuyerProductController::class, 'index'])->name('products.index');
        // Route::get('/products/{id}/show', [BuyerProductController::class, 'show'])->name('products.show');
        // // -- End Buyer Products Routs-- //

        // -- Start Buyer factors Routs-- //
        Route::get('/factors', [ApiBuyerFactorController::class, 'index'])->name('orders.index');
        Route::post('/factors', [ApiBuyerFactorController::class, 'store'])->name('orders.store');
        Route::get('/factors/show/{id}', [ApiBuyerFactorController::class, 'show'])->name('orders.show');
        Route::any('/factors/{id}/edit', [ApiBuyerFactorController::class, 'edit'])->name('orders.edit');
        Route::any('/factors/update/{id}', [ApiBuyerFactorController::class, 'update'])->name('orders.update');
        Route::post('/factors/destroy/{id}/', [ApiBuyerFactorController::class, 'destroy'])->name('orders.destroy');
        // -- End Buyer factors Routs-- //

    });



    Route::middleware('role:Seller')->prefix('seller')->group(function () {

        // // --Start Seller Orders Routs-- //
        // Route::get('/order', [ApiSellerOrderController::class, 'index'])->name('seller.orders.index');
        // Route::get('/order/{id}/show', [ApiSellerOrderController::class, 'show'])->name('seller.orders.show');
        // Route::post('/order/{id}/destroy', [ApiSellerOrderController::class, 'destroy'])->name('seller.orders.destroy');
        // // --End Seller Orders Orders Routs-- //

        // --Start Seller Products Routs-- //
        Route::get('/products', [ApiSellerProuctsController::class, 'index'])->name('seller.products.index');
        Route::post('/products', [ApiSellerProuctsController::class, 'store'])->name('seller.products.store');
        Route::get('/products/show/{id}', [ApiSellerProuctsController::class, 'show'])->name('seller.products.show');
        Route::any('/products/update/{id}', [ApiSellerProuctsController::class, 'update'])->name('seller.products.update');
        Route::post('/products/destroy/{id}', [ApiSellerProuctsController::class, 'destroy'])->name('seller.products.destroy');
        // --End Seller Products Routs-- //
    });
});
