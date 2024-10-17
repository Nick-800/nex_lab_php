<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTest;
use App\Models\Test;

class UserTestController extends Controller
{
    public function store(Request $request)
    {
        $input = request()->validate([
            "test_id" => ["required", "numeric"],
        ]);
        $test = Test::find($input['test_id']);
        if (!$test || $test->status !== 'available') { // Assuming 'status' is the attribute to check
            return response()->json(["error" => "Test not available"], 403);
        }

        UserTest::create([
            'user_id' => auth('api')->user()->id,
            'test_id' => $input['test_id'],
        ]);
        return response()->json(["data" => "success"]);
    }

    public function destroy($id)
    {
        $userTest = UserTest::where('user_id', auth('api')->user()->id)->where('test_id', $id)->first();
        if (!$userTest) {
            return response()->json(["error" => "Test not found"], 404);
        }
        $userTest->delete();
        return response()->json(["data" => "success"]);
    }

    public function show($id)
    {
        $userTest =
        UserTest::where('user_id', auth('api')
         ->user()->id)
         ->where('test_id', $id)->first();
        if (!$userTest) {
            return response()->json(["error" => "Test not found"], 404);
        }
        return response()->json(["data" => $userTest]);
    }

    public function index()
    {
        $userTests = UserTest::where('user_id', auth('api')->user()->id)->get();
        return response()->json(["data" => $userTests]);
    }
}
