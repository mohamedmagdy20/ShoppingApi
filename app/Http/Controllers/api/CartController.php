<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function totalprice($carts)
    {
        $total =0;
        foreach($carts as $cart)
        {
            $res =  $cart->stock * $cart->product->price_out;
            $total+=$res;
        }
        return $total; 
    }

    public function index(Request $request)
    {

        $data = Cart::with(['product'=>function($query){
            $query->with('images');
        },'client'])->where('client_id',auth()->user()->id)->get();
        if($data)
        {
            $cartCount =  $data->count();

            $total = $this->totalprice($data);

            return response()->json([
               'msg'=>'success',
               'data'=>$data,
               'cartCount'=>$cartCount,
               'total'=>$total,
               'state'=>true
            ], 200);
        }else{
            return response()->json([
                'msg'=>'data not found',
                'state'=>false
            ], 400);
 
        }
    }
    public function create(Request $request)
    {
        $rules = [
            'product_id'=>'required',
            'stock'=>'required|numeric'
        ];
        $validate = Validator::make($request->all(),$rules);
        if($validate->fails())
        {return response()->json(['error'=>$validate->errors()]);}

        if(Cart::create(array_merge($validate->validated(),['client_id'=>auth()->user()->id])))
        {
            $product =  Product::where('id',$request->product_id)->with('images')->get();
            return response()->json([
                'msg'=>'product added to cart',
                'data'=>$product,
                'state'=>true
            ], 200);
        }else{
            return response()->json([
                'msg'=>'error Occure',
                'state'=>false
            ], 400);
        }

    }

    public function update(Request $request)
    {
        $rules = [
            'id'=>'required',
            'product_id'=>'required',
            'stock'=>'required|numeric'
        ];
        $validate = Validator::make($request->all(),$rules);
        if($validate->fails())
        {return response()->json(['error'=>$validate->errors()]);}
        $product = Cart::find($request->id);
        if($product)
        {
         $product =  $product->update(array_merge($validate->validated(),[
            'client_id'=>auth()->user()->id,
         ]));
         return response()->json([
            'msg'=>'cart Updated',
            'state'=>ture,
            'data'=>$product
        ], 200);
        }else{
            return response()->json([
                'msg'=>'error Occure',
                'state'=>false
            ], 400);
        }
    }
    public function delete($id)
    { 
        $cart = Cart::find($id);
        if($cart)
        {
            $isdeleted =  $cart->delete();
            if($isdeleted)
            {
                return response()->json([
                    'msg'=>'product removed',
                    'state'=>true
                ], 200); 
            }else{
                return response()->json([
                    'msg'=>'error Occure',
                    'state'=>false
                ], 400);
            }
        }else{
            return response()->json([
                'msg'=>'data not found',
                'state'=>false
            ], 400);
        }
    }

}
