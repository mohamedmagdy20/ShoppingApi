<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Images;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    //
    public function index()
    {
        $data = Product::query()->with(['category' => function ($query) {
            $query->select('id', 'name_'.app()->getLocale().' as catName');
        },
        'supplier' => function ($query) {
            $query->select('id', 'name as supName','img');
        },
        'images'=>function($query)
        {
            $query->select('id','img as prodImg','product_id');
        }
        ])->get([
            'id',
            'name_'.app()->getLocale(),
            'description_'.app()->getLocale(),
            'categories_id','suppliers_id','price_in','price_out'
        ]);
       
        return response()->json([
            'products'=>$data,
            'state'=>true,
            'msg'=>'success'
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            'name_en'=>'required',
            'name_ar'=>'required',
            'price_in'=>'required|numeric',
            'price_out'=>'required|numeric',
            'description_en'=>'required',
            'description_ar'=>'required',

            'stock'=>"required|numeric"
        ];
        $validate = Validator::make($request->all(),$rules);

        if($validate->fails())
        {
            return response()->json(['error'=>$validate->errors()]);
        }
        if($request->hasFile('prod_img'))
        {
            $validate_img = Validator::make($request->all(),[
                'prod_img'=>['image']
            ]);

            if($validate_img->fails())
            {
              return response()->json(['error'=>$validator->errors()],401);
            }

            $images = $request->files->all();
            // return $images[0];
            $product = new Product();

            $product->name_en = $request->name_en;
            $product->name_ar = $request->name_ar;
            $product->description_en = $request->description_en;
            $product->description_ar = $request->description_ar;
            $product->price_in = $request->price_in;
            $product->price_out = $request->price_out;
            $product->price_in = $request->price_in;
            $product->stock = $request->stock;

            $product->categories_id = $request->categories_id;
            $product->suppliers_id = $request->suppliers_id;
            $product->save();
            
            $image = new Images(); 
            for($i = 0 ; $i<=count($images); $i++)
            {
                $im = $images[$i]; 
                $imgName = time().$im->getClientOriginalName();
                $im->move(public_path('images/products'),$imgName);

                $image->img = $imgName; 
                $image->product_id = $product->id;
                $image->save(); 
            }   
            // foreach($images as $img)
            // {
            //     return $img->file;
            //     // $imgName = time().img->getClientOriginalName();
            //     // img->move(public_path('images/products'),$imgName);

            //     // $image->img = $imgName; 
            //     // $image->product_id = $product->id;
            //     // $image->save(); 
            // }
             return response()->json([
                'msg'=>'success',
                'data'=>$image
             ], 201);
        }else{
            return response()->json([
                'message'=>'error occure'
            ],400);
        }
    }
}
