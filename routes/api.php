<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ClientAuthController;
use App\Http\Controllers\api\SuppliersController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[ClientAuthController::class,'register']);
Route::post('admin/login',[AdminAuthController::class,'login']);

Route::middleware(['auth:admin-api','change_lang'])->prefix('admin')->group(function(){
    Route::resource('suppliers',SuppliersController::class);
    Route::post('category/create',[CategoryController::class,'create']);

    Route::group(['prefix'=>'products'],function(){
        Route::post('store',[ProductController::class,'create']);
        Route::delete('delete/{id}',[ProductController::class,'delete']);
        Route::post('update',[ProductController::class,'update']);
    });

    Route::group(['prefix'=>'category'],function(){
        Route::post('store',[CategoryController::class,'create']);
        Route::post('update',[CategoryController::class,'update']);
    });

    Route::group(['prefix'=>'orders'],function(){
        Route::get('index',[OrderController::class,'index']);
    });
    Route::get('orders',[OrderController::class,'index']);


});
// Route::resource('category', CategoryController::class);


// public routes //
Route::get("orders/cencel/{id}",[OrderController::class,'cancelOrder']);
Route::get('category',[CategoryController::class,'index']);
Route::get('products/show/{id}',[ProductController::class,'show']);
Route::get('products/index',[ProductController::class,'index']);
Route::get('products/category/{id}',[ProductController::class,'productbyCategory']);
Route::get('products/supplier/{id}',[ProductController::class,'productbySupplier']);

require __DIR__ . '/client.php';
