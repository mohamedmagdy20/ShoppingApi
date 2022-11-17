<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $category = Category::select('name_'.app()->getLocale())->get();
        return response()->json([
            'data'=>$category,
        ]);
    }

    public function create(Request $request)
    {
        $rule = [
            'name_en'=>'required',
            'name_ar'=>'required'
        ];

        // $validate =  ($request->all(),$rule);
        $validate = Validator::make($request->all(),$rule);

        if($validate->fails())
        {
            return response()->json(['error'=>$validate->errors()]);
        }
        if($request->hasFile('cat_img'))
        {
            $validate_img = Validator::make($request->all(),[
                'cat_img'=>['image']
            ]);

            if($validate_img->fails())
            {
              return response()->json(['error'=>$validate_img->errors()],401);
            }
            $imgName = time().$request->file('cat_img')->getClientOriginalName();
            // return $imgName;
            $request->file('cat_img')->move(public_path('images/categories'),$imgName);
            $data = array_merge($validate->validated(),['img'=>$imgName]);
            if(Category::create($data))
            {
                return response()->json([
                    'msg'=>'Category Stored',
                    'state'=>true,
                ]);
            }
        }else{
            return response()->json([
                'message'=>'error occure'
            ],400);
        }
    }

    public function update(Request $request)
    {
        try{
            $category = Category::find($request->id);
        }catch(Exception $e)
        {
            return response()->json([
                'message'=>'Category Not Found'
            ],401);
        }
        
        $rule = [
            'name_en'=>'required',
            'name_ar'=>'required'
        ];

        // $validate =  ($request->all(),$rule);
        $validate = Validator::make($request->all(),$rule);
        if($validate->fails())
        {
            return response()->json(['error'=>$validate->errors()]);
        }
        $data = [
            'name'=>$request->name
        ];

        $category->update($data);
        
        return response()->json([
            'message'=>'Data Updated'
        ],205);

    }
}
