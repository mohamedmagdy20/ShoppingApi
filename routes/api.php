<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ClientAuthController;
use App\Http\Controllers\api\SuppliersController;
use App\Http\Controllers\api\ProductController;
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
    Route::resource('category', CategoryController::class);
    Route::resource('suppliers',SuppliersController::class);
});
Route::get('products/index',[ProductController::class,'index']);
Route::post('products/store',[ProductController::class,'create']);
require __DIR__ . '/client.php';
