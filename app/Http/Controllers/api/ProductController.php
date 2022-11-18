<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Images;
use Illuminate\Support\Facades\Validator;
use Exception;
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

    public function show($id)
    {
        $product = Product::find($id);
        if($product)
        {
            return response()->json([
                'msg'=>'success',
                'state'=>true,
                'data'=>$product
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
              return response()->json(['error'=>$validate_img->errors()],401);
            }

            $images = $request->file('prod_img');
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

            foreach($images as $img)
            {
                $image = new Images(); 
          
                // return $img->file;
                $imgName = time().$img->getClientOriginalName();
                $img->move(public_path('images/products'),$imgName);

                $image->img = $imgName; 
                $image->product_id = $product->id;
                $image->save(); 
            }
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

    public function unlinkimage($path,$images)
    {   
        foreach($images as $image)
        {
            $filepath = public_path().$path.$image->img;
            unlink($filepath);
        }
        return true;
    }


    public function delete(Request $request,$id)
    {
        $product = Product::findOrFail($id);
        $images = Images::where('product_id',$id)->get();
        if($product && $images)
        {       
            $path = '//images//products//';
            $unlink = $this->unlinkimage($path,$images);
            if($unlink)
            {
                $product->delete();
                $images->delete();
                 return response()->json([
                    'msg'=>'product deleted',
                    'state'=>true
                 ], 200);
            }else{
                return response()->json([
                    'message'=>'error occur'
                ],400);
            }
        }else{
            return response()->json([
                'msg'=>'data not Found',
                'state'=>false
             ], 400);
        }


    }

    public function update(Request $request)
    {
        $rules = [
            'id'=>'required',
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
        $id = $request->id;
        $product = Product::findOrFail($id);
        $images = Images::where('product_id',$id)->get();
        $path = '//images//products//';


        if($product && $images)
        {
            if($request->hasFile('prod_img'))
            {

                $validate_img = Validator::make($request->all(),[
                    'prod_img'=>['image']
                ]);
    
                if($validate_img->fails())
                {
                  return response()->json(['error'=>$validate_img->errors()],401);
                }
                $this->unlink($path,$images);
                $newimages = $request->file('prod_img');
                foreach($newimages as $img)
                {
                    $image = new Images();          
                    $imgName = time().$img->getClientOriginalName();
                    $img->move(public_path('images/products'),$imgName);
                    $image->img = $imgName; 
                    $image->product_id = $product->id;
                    $image->save(); 
                }
                $product->update(
                    $validate->validated()
                );
            }
            else{
                $product->update(
                    $validate->validated()
                );
                return response()->json([
                    'msg'=>'product updated',
                    'state'=>true,
                    'data'=>$product
                ], 200);
            }
        }else{
            return response()->json([
                'msg'=>'data not found',
                'state'=>false
            ], 400);
        }
    }
    
}
