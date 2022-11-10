<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
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

        if(!Auth::attempt($request->only(['email','password'])))
        {
            return response()->json(['error'=>"Invaild email or Password"],401);
        }

        $client =User::where('email',$request->email)->first();
        $token = $client->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'client'=>$client,
            'token'=>$token
        ]);

    }
}
