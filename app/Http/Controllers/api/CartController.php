<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cart;

class CartController extends Controller
{
    //

    public function index(Request $request)
    {
        // return auth()->user(->
        $data = Cart::with(['product'=>function($query){
            $query->with('images');
        },'client'])->where('client_id',auth()->user()->id)->get();
        if($data)
        {
            return response()->json([
               'msg'=>'success',
               'data'=>$data,
               'state'=>true
            ], 200);
        }else{
            return response()->json([
                'msg'=>'data not found',
                'state'=>false
            ], 400);
 
        }
    }
}
