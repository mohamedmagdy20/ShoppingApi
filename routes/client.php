<?php
use App\Http\Controllers\api\ClientAuthController;
use App\Http\Controllers\aoi\SuppliersController;
use App\Http\Controllers\api\CartController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->middleware(['auth:client-api','change_lang'])->group(function(){
    Route::get('cart',[CartController::class,'index']);
});
Route::post('client/login',[ClientAuthController::class,'login']);


?>
