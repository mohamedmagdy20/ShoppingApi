<?php
use App\Http\Controllers\api\ClientAuthController;
use App\Http\Controllers\aoi\SuppliersController;
use App\Http\Controllers\api\CartController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->middleware(['auth:client-api','change_lang'])->group(function(){
    Route::group(['prefix'=>'cart'],function(){
        Route::get('index',[CartController::class,'index']);
        Route::post('create',[CartController::class,'create']);
        Route::delete('delete/{id}',[CartController::class,'delete']);
        Route::post('update',[CartController::class,'update']);
    });

});
Route::post('client/login',[ClientAuthController::class,'login']);


?>
