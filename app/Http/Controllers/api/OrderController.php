<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\orderDetails;
use Carbon\Carbon;
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

    public function createOrderDetails($order_id,$carts)
    {
        foreach($carts as $cart)
        {
            $orderDetails = new orderDetails();
            $orderDetails->order_id =  $order_id;
            $orderDetails->product_id = $cart->product_id;
            $orderDetails->stock = $cart->stock;
            $orderDetails->save();
        }
        return true;
    }
    public function create(Request $request)
    {
        //get products from cart 
        $carts = Cart::where('client_id',auth()->user()->id)->get();
        if($cart)
        {
            $order = new Orders();
            $order->client_id = auth()->user()->id;
            $order->save();  
            $orderDetails  = $this->createOrderDetails($order->id,$carts);
            if($orderDetails)
            {
                $carts = $carts->delete();
                return response()->json([
                    'msg'=>'Order Submited',
                    'data'=>$order->with('orderDetails')->get(),
                    'state'=>true
                ], 200);
            }
            else
            {
                return response()->json([
                    'msg'=>'Error Occure',
                    'state'=>false
                ], 400);
            }

        }
        else{
            return response()->json([
                'msg'=>'data no found',
                'state'=>false
            ], 400);
        }
    }

    // cencel if order pass less than one day 
    public function cancelOrder(Request $request,$id)
    {
        // return Carbon::now();
        $order = Orders::find($id);
        $time =Carbon::parse($order->updated_at);  
        $time_now = Carbon::now();
        if($time->lt($time_now->addDays()))
        {
            // you could cencel order //
            $order->update([
                'state'=>'Canceled'
            ]);
            return response()->json([
                'msg'=>'Order Cenceled',
                'state'=>true,
            ], 200);
        }else{
            return response()->json([
                'msg'=>'You counld not cenceld order',
                'state'=>true,
            ], 400);
        }
        
    }

    public function approveOrder($id)
    {
        $order = Orders::find($id);
        if($order)
        {
            $order->update([
                'state'=>'Approved'
            ]);
            return response()->json([
                'msg'=>'Order Apporved',
                'state'=>true,
            ], 200);
        }else{
            return response()->json([
                'msg'=>'data no found',
                'state'=>false
            ], 400);
        } 
    }
}
