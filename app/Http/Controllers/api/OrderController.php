<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\orderDetails;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::query()->with('clinet')->with('admin')->with('orderDetails',function($query){
            $query->with('product',function($query){
                $query->with('images');
            });
        })->get();   
        if($orders)
        {
            return response()->json(
                ['msg'=>'success',
            'orders'=>$orders,'state'=>true], 200);
        }else{
            return response()->json([
                'msg'=>'data not found',
                'state'=>false
            ], 400);
        }
    }

    public function clientOrders()
    {
        $orders = Orders::query()->with('clinet')->with('admin')->with('orderDetails',function($query){
            $query->with('product',function($query){
                $query->with('images');
            });
        })->where('clinet',auth()->user()->id)->get();   
        if($orders)
        {
            return response()->json(
                ['msg'=>'success',
            'orders'=>$orders,'state'=>true], 200);
        }else{
            return response()->json([
                'msg'=>'data not found',
                'state'=>false
            ], 400);
        }
    }
}
