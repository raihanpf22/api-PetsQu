<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//user
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/users/{id}', [UserController::class, 'show']);
Route::put('/users/edit/{id}', [UserController::class, 'update']);
Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);

// Product
Route::post('/products/create', [ProductsController::class, 'store']);
Route::get('/products', [ProductsController::class, 'index']);
Route::post('/products/{id}', [ProductsController::class, 'show']);
Route::put('/products/edit/{id}', [ProductsController::class, 'update']);
Route::delete('/products/delete/{id}', [ProductsController::class, 'destroy']);