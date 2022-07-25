<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartController;

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
Route::get('/users', [UserController::class, 'index'])->middleware('jwt.verify');
Route::post('/users/{id}', [UserController::class, 'show'])->middleware('jwt.verify');
Route::put('/users/edit/{id}', [UserController::class, 'update'])->middleware('jwt.verify');
Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->middleware('jwt.verify');

// Admin
Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login',[AdminController::class, 'login']);
Route::post('/admin/logout',[AdminController::class, 'logout']);
Route::get('/admins', [AdminController::class, 'index'])->middleware('jwt.verify');
Route::put('/admin/edit/{id}', [AdminController::class, 'update'])->middleware('jwt.verify');
Route::delete('admin/delete/{id}', [AdminController::class, 'destroy'])->middleware('jwt.verify');

// Product
Route::post('/products/create', [ProductsController::class, 'store'])->middleware('jwt.verify');
Route::get('/products', [ProductsController::class, 'index']);
Route::post('/products/{id}', [ProductsController::class, 'show']);
Route::put('/products/edit/{id}', [ProductsController::class, 'update']);
Route::delete('/products/delete/{id}', [ProductsController::class, 'destroy']);

// Cart
Route::get('/cart', [CartController::class, 'index'])->middleware('jwt.verify');
Route::post('/addCart/{product_id}', [CartController::class, 'addCart'])->middleware('jwt.verify');