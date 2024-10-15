<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request){
        $input=request()->validate([
            "name"=> ["required ","string"],
            "email"=> ["string","required","email",'unique:users,email'],
            "password"=> ["required","string"],
            "phone"=> ["required","string","min:10","max:10",'unique:users,phone'],
            "DOB"=>["string"],
            "gender"=> ["required","string","in:male,female"],
            'location' => ['nullable',]
            ]);
        User::create([
            'email'=>$input['email'],
            'name' => $input['name'],
            'phone' => $input['phone'],
            'gender' => $input['gender'],
            'DOB' => $input['DOB'] ?? null ,
            'location' => $input['location'] ?? null,
            'password' => Hash::make($input['password'])
        ]);
        return response()->json(["status"=> "success"]);
    }
    public function login(Request $request){
        $credentials = request(['email', 'password']);
        if(!$token = auth('api')->attempt($credentials)){
            return response()->json(['status'=> 'error']);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function Logout(Request $request){
        auth('api')->logout();

        return response()->json(['data' => 'Successfully logged out']);
    }
    public function user(){
        $user = User::findOrfail(auth('api')->user()->id);
        return response()->json(["the logged in user data"=> $user]);
    }

}
