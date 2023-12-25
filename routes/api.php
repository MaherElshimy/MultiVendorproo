<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
// GET: Retrieve all products
Route::get('/products', [ProductController::class, 'index']);

// GET: Retrieve a specific product by ID
Route::get('/products/{id}', [ProductController::class, 'show']);

// POST: Create a new product
Route::post('/products', [ProductController::class, 'create']);

// PUT/PATCH: Update a specific product by ID
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::patch('/products/{id}', [ProductController::class, 'update']);

// DELETE: Delete a specific product by ID
Route::delete('/products/{id}', [ProductController::class, 'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
