<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('discount/{discountId}', DiscountController::class)->name('mytheresa.get-discount');
Route::get('category/{categoryId}', CategoryController::class)->name('mytheresa.get-category');
Route::get('product/{productId}', [ProductController::class, 'getProduct'])->name('mytheresa.get-product');
Route::get('products', [ProductController::class, 'getProducts'])->name('mytheresa.get-products');
