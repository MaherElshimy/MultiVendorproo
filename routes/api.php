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

// //// Only for users
// Route::middleware(['auth:sanctum', 'type.user'])->group(function () {
//     Route::get('/users/orders', [OrderController::class, 'orders']);
// });

/*
 * ////////// Authentication routes ///////////////////////
 */



// Only for admins
Route::middleware(['auth:admins'])->group(function () {
    // Create a new category
    Route::post('/categories', [CategoryController::class, 'store']);
    // Update a specific category by ID
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    // Delete a specific category by ID
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

// Only for vendors
Route::middleware(['auth:vendors'])->group(function () {
    // Delete the duplicated category routes from here
    // POST: Create a new product
    Route::post('/products', [ProductController::class, 'create']);
    // PUT/PATCH: Update a specific product by ID
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    // DELETE: Delete a specific product by ID
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




/*
 * /////////////////////////////////////////////////////////
 * /////////////////// Public Routes ///////////////////////
 * ////////////////////////////////////////////////////////
 */



/*//////////////////////////////////////////////////////////
 * //////////////// Special Catagory ///////////////////////
 */

// Show all categories
Route::get('/categories', [CategoryController::class, 'index']);

// Show a specific category by ID
Route::get('/categories/{id}', [CategoryController::class, 'show']);



/*//////////////////////////////////////////////////////////
 * //////////////// Special Products ///////////////////////
 */
// GET: Retrieve all products
Route::get('/products', [ProductController::class, 'index']);

// GET: Retrieve a specific product by ID
Route::get('/products/{id}', [ProductController::class, 'show']);

// Search : Search a specific product by name
Route::get('/products/search/{name}', [ProductController::class, 'searchByName']);


/*
 * /////////////////////////////////////////////////////////
 * //////////////// Protected Routes ///////////////////////
 * ////////////////////////////////////////////////////////
 */
Route::group(['middleware' => ['auth:sanctum']], function() {


    // WishLists Is Here.
    Route::post('/wishlist/add/{productId}', [WishlistController::class, 'addToWishlist']);
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'removeFromWishlist']);

    // Orders Is Here.
    Route::post('/orders/place', [OrderController::class, 'placeOrder']);
    Route::get('/orders/{id}', [OrderController::class, 'getOrderDetails']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);



});
