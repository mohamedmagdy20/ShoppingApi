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
        $category = Category::all();
        return response()->json([
            'data'=>$category,
        ]);
    }

    public function create(Request $request)
    {
        $rule = [
            'name'=>'required'
        ];

        // $validate =  ($request->all(),$rule);
        $validate = Validator::make($request->all(),$rule);

        if($validate->fails())
        {
            return response()->json(['error'=>$validate->errors()]);
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
            'name'=>'required'
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
