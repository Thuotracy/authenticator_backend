<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function registeruser(request $request){
        //the below code gentlemen is meant to check whether a record exists
        //or not
        $user=User::where('email',$request['email'])->first();
        if($user){
        $response['status']=0;
        $response['message']="Email Already Registered";
        $response['code']=409;
        }
        //the code below is now meant to perform the post operation or create a
        //new resource if the user does not exist
        else{
        $user=User::create(
        [
        'name' =>$request->name,
        'email' =>$request->email,
        'password' =>bcrypt($request->password)
        ]
        );
        $response['status']=1;
        $response['message']="User Registered Successfully";
        $response['code']=200;
        }
        return response()->json($response);
        }

        public function login(request $request)
{
$credentials = $request->only('email', 'password');
try {
if (!$token = JWTAuth::attempt($credentials)) {
return response()->json([
'status' => 0,
'code' => 401,
'message' => 'Email or Password is incorrect',
'data' => null,
]);
}
} catch (JWTException $e) {
return response()->json([
'data' => null,
'code' => 500,
'message' => 'Could not create token',
]);
}
$user = auth()->user();
$data = [
'token' => $token,
'user' => [
'user_id' => $user->id,
'email' => $user->email,
],
];
return response()->json([
'data' => $data,
'status' => 1,
'code' => 200,
'message' => 'Login Successfully',
]);
}
}
