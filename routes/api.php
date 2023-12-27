<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;

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



/*
 * /////////////////////////////////////////////////////////
 * /////////////////// Public Routes ///////////////////////
 * ////////////////////////////////////////////////////////
 */

/*
 * ////////// Authentication routes ///////////////////////
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




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

    // Create a new category
    Route::post('/categories', [CategoryController::class, 'store']);

    // Update a specific category by ID
    Route::put('/categories/{id}', [CategoryController::class, 'update']);

    // Delete a specific category by ID
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // POST: Create a new product
    Route::post('/products', [ProductController::class, 'create']);

    // PUT/PATCH: Update a specific product by ID
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);

    // DELETE: Delete a specific product by ID
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);


    // WishLists Is Here.
    Route::post('/wishlist/add/{productId}', [WishlistController::class, 'addToWishlist']);
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'removeFromWishlist']);


    // Log Out
    Route::post('/logout', [AuthController::class, 'logout']);



});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
