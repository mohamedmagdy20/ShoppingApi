<?php
use App\Http\Controllers\api\ClientAuthController;
use App\Http\Controllers\SuppliersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function(){
    Route::post('login',[ClientAuthController::class,'login']);
});


?>
