<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

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
    // POST: Create a new product
    Route::post('/products', [ProductController::class, 'create']);

    // PUT/PATCH: Update a specific product by ID
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);

    // DELETE: Delete a specific product by ID
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Log Out
    Route::post('/logout', [AuthController::class, 'logout']);



});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
