<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTest;
use App\Models\Test;

use function PHPSTORM_META\map;

class UserTestController extends Controller
{
    public function store(Request $request)
    {
        $input = request()->validate([
            "test_id" => ["required", "numeric"],
            "booked_time" => ["required", "date"],
        ]);
        $test = Test::find($input['test_id']);
        if (!$test || $test->status !== 'available') {
            return response()->json(["error" => "Test not available"], 403);
        }

        UserTest::create([
            'user_id' => auth('user')->user()->id,
            'booked_time' => $input['booked_time'],
            'test_id' => $input['test_id'],
        ]);
        return response()->json(["data" => "success"]);
    }

        public function destroy($id)
    {
        $userTest = UserTest::where('user_id', auth('user')->user()->id)
        ->where('test_id', $id)->first();
        
        if (!$userTest) {
            return response()->json(["error" => "Test not found"], 404);
        }
        $userTest->delete();
        return response()->json(["data" => "success"]);
    }

    public function show($id)
    {
        $userTest = UserTest::where('user_id', auth('user')->user()->id)->where('test_id', $id)->first();
        if (!$userTest) {
            return response()->json(["error" => "Test not found"], 404);
        }
        return response()->json(["data" => $userTest]);
    }

    public function index()
    {
        $userTests = UserTest::where('user_id', auth('user')->user()->id)->get();

        $results= $userTests->map(function ($e){
            return[
                "id"=> $e->id,
        "user_id" =>$e ->user_id,
        "test_id" => $e ->test_id,
        "booked_time"=>$e->booked_time,
        "test_name"=>$e->test->test_name,
        "created_at"=> $e ->created_at ,
        "updated_at"=> $e ->updated_at
            ];
        });
        return $results;
    }
}
