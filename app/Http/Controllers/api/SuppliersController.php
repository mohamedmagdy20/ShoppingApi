<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $supplier =Supplier::all();
        return response()->json([
            'data'=>$supplier
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'name'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'error'=>$validator->errors()
            ]);
        }

        if($request->hasFile('sup_img'))
        {
            $validator_img= Validator::make($request->all(),['img'=>['image']]);
            if($validator_img->fails())
            {
                return response()->json([
                    'error'=>$validator->errors()
                ],401);
            }
            $imgName = time().$request->file('sup_img')->getClientOriginalName();
            $request->file('sup_img')->move(public_path('images/suppliers'),$imgName);
         
            if(Supplier::create(array_merge($validator->validated(),['img'=>$imgName])))
            {
                return response()->json([
                    'message'=>'Supplier Added'
                ]);

            }
        }else
        {
            return response()->json([
                'message'=>'error occur'
            ],400);
         
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try{
            $supplier  =Supplier::find($id);
        }catch(Exception $e)
        {
            return response()->json(['msg'=>'Data Not Found'],404);
        }
        return response()->json([
            'msg'=>'success',
            'state'=>true,
            'data'=>$supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try{
            $supplier  =Supplier::find($id);
        }catch(Exception $e)
        {
            return response()->json(['msg'=>'Data Not Found'],404);
        }
        $rules = [
            'name'=>'required',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->failed())
        {
            return response()->json([
                'error'=>$validator->errors()
            ],401);
        }
        if($request->hasFile('sup_img'))
        {
            $validate_image = Validator::make($request->all(),[
                'sup_img'=>['image']
            ]);
            
            if($validate_image->fails())
            {
              return response()->json(['error'=>$validator->errors()],401);
            }

            $file_path = public_path().'images/suppliers'.$supplier->img;
            unlink($file_path);
            $imgName = time().$request->file('sup_name')->getClientOriginalName();
            $request->file('sup_img')->move(public_path('images/clients'),$imgName);

            if($supplier->update(array_merge($validator->validated(),[
                'sup_img'=>$imgName
            ])))
            {
                return response()->json([
                    'msg'=>'Updated Successfully',
                    'state'=>true
                ]);
            }
        }

        if($supplier->update($validator->validated()))
        {
            return response()->json([
                'msg'=>'Updated Successfully',
                'state'=>true
            ]);
        }
        else{
            return response()->json([
                'message'=>'error occur'
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $supplier  =Supplier::find($id);
        }catch(Exception $e)
        {
            return response()->json(['msg'=>'Data Not Found'],404);
        }
        $file_path = public_path().'images/suppliers'.$supplier->img;
        unlink($file_path);
        if($supplier->delete())
        {
            return response()->json([
                'msg'=>'deleted',
                'state'=>true
            ]);
        } 
    }
}
