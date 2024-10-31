<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{


    public function store(Request $request)
    {
        $input = request()->validate([
            "test_name" => ["required", "string"],
            "test_description" => ["required", "string"],
            "test_type" => ["required", "string"],
            "price" => ["required", "numeric"],
            "duration" => ["required", "numeric"],
            "available_slots" => ["required", "numeric"],
            "test_code" => ["required", "string"],
            "instructions" => ["required", "string"],
            "icon_id" => ["required", "numeric"],
            "preparation_required" => ["required", "string"],
            "status" => ["required", "string" , "in:available,not available"],
            "max_bookings_per_slot" => ["required", "numeric"],
        ]);
        Test::create([
            'test_name' => $input['test_name'],
            'test_description' => $input['test_description'],
            'test_type' => $input['test_type'],
            'price' => $input['price'],
            'duration' => $input['duration'],
            'available_slots' => $input['available_slots'],
            'test_code' => $input['test_code'],
            'instructions' => $input['instructions'],
            'icon_id' => $input['icon_id'],
            'preparation_required' => $input['preparation_required'],
            'status' => $input['status'],
            'max_bookings_per_slot' => $input['max_bookings_per_slot'],
        ]);
        return response()->json(["data" => "success"]);
    }
    public function update(Request $request, $id)
    {
        $input = request()->validate([
            "test_name" => ["required", "string"],
            "test_description" => ["required", "string"],
            "test_type" => ["required", "string"],
            "price" => ["required", "numeric"],
            "duration" => ["required", "numeric"],
            "available_slots" => ["required", "numeric"],
            "test_code" => ["required", "string"],
            "instructions" => ["required", "string"],
            "icon_id" => ["required", "numeric"],
            "preparation_required" => ["required", "string"],
            "status" => ["required", "string"],
            "max_bookings_per_slot" => ["required", "numeric"],
        ]);
        $test = Test::find($id);

}
   public function show($id){
        $test = Test::find($id);
        return response()->json($test);
    }

    public function destroy($id){

        $test = Test::find($id);
        $test->delete();
        return response()->json(["status" => "success"]);
   }
    public function index(){
        $tests = Test::all();
        return response()->json(["data" => $tests]);
    }

    public function available(){
        $tests = Test::where('status', 'available')->get();
        return response()->json($tests);
    }

    public function notAvailable(){
        $tests = Test::where('status', 'not available')->get();
        return response()->json($tests);
    }
}
