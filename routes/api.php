<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;

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


// Only for admins
Route::middleware(['auth:admins'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

// Only for vendors
Route::middleware(['auth:vendors'])->group(function () {
    Route::post('/products', [ProductController::class, 'create']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});



Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);



Route::post('/vendor/register', [VendorController::class, 'register']);
Route::post('/vendor/login', [VendorController::class, 'login']);
Route::post('/vendor/logout', [VendorController::class, 'logout']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);




Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);


Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/search/{name}', [ProductController::class, 'searchByName']);


Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/wishlist/add/{productId}', [WishlistController::class, 'addToWishlist']);
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'removeFromWishlist']);

    Route::post('/orders/place', [OrderController::class, 'placeOrder']);
    Route::get('/orders/{id}', [OrderController::class, 'getOrderDetails']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);



});
