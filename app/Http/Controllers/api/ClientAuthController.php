<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ClientAuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $rule = [
            'email'=>'required|email',
            'password'=>'required'
        ];

        $validator = Validator::make($request->all(),$rule);

        if($validator->failed())
        {
            return response()->json([
                'error'=>$validator->errors()
            ],401);
        }

        if(!Auth::guard('client')->attempt($request->only(['email','password'])))
        {
            return response()->json(['error'=>"Invaild email or Password"],401);
        }

        $client =Client::where('email',$request->email)->first();
        $token = $client->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'client'=>$client,
            'token'=>$token
        ]);
    }

    public function register(Request $request)
    {
       $rule = [
            'name'=>["required"],
            'email'=>['required','email'],
            'password'=>['required']
       ];
       $validator = Validator::make($request->all(),$rule);
       if($validator->fails())
       {
            return response()->json(['error'=>$validator->errors()],401);
       }

       if($request->hasFile('profile_image'))
       {
             $validate_image = Validator::make($request->all(),[
                 'profile_image'=>['image']
             ]);
            
            if($validate_image->fails())
            {
              return response()->json(['error'=>$validator->errors()],401);
            }
            
            $imgName = time().$request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->move(public_path('images/clients'),$imgName);
            
            
            if(Client::create(array_merge($validator->validated(),[
                'profile_image'=>$imgName,
                'password'=>Hash::make($request->password)
            ])))
            {
                 return response()->json([
                     'message'=>'registered success'
                 ]);
            }
    }
    if(Client::create(array_merge($validator->validated(),
    [
        'password'=> Hash::make($request->password)
    ])))
    {
         return response()->json([
             'message'=>'registered success'
         ]);
    }else{
        return response()->json([
            'message'=>'error occure'
        ],400);
    }

    }
}
