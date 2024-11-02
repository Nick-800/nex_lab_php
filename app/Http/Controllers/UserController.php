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
        if(!$token = auth('user')->attempt($credentials)){
            return response()->json(['status'=> 'error'], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("user")->factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request){
        auth('user')->logout();

        return response()->json(['data' => 'Successfully logged out']);
    }
    public function user(){
        $user = User::findOrfail(auth('user')->user()->id);
        return response()->json($user);
    }

    public function refresh(){
        return response()->json([
            'access_token' => auth('user')->refresh( true,true),
            'token_type' => 'bearer',
            'expires_in' => auth('user')->factory()->getTTL() * 60
        ]);
    }

    public function update(Request $request, $id){
        $input = $request->validate([
            "name" => ["sometimes", "string"],
            "email" => ["sometimes", "string", "email", 'unique:users,email,' . $id],
            "password" => ["sometimes", "string"],
            "phone" => ["sometimes", "string", "min:10", "max:10", 'unique:users,phone,' . $id],
            "DOB" => ["sometimes", "string"],
            "gender" => ["sometimes", "string", "in:male,female"],
            'location' => ['nullable'],
        ]);

        $user = User::findOrFail($id);

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $user->update($input);

        return response()->json(["status" => "success", "user" => $user]);
    }
}
