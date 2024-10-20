<?php
namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Models\UserResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class UserResultController extends Controller
{
    public function index()
    {
        $results = auth('api')->user()->results;
        return response()->json(['data' => $results]);
    }


public function store(Request $request)
{
    // Validate the file and test_id
    $validated = $request->validate([
        'file' => 'required|file|mimes:pdf|max:2048',
        'test_id' => 'required|exists:tests,id',
    ]);

    // Generate a specific filename using test_id and current timestamp
    $testId = $request->input('test_id');
    $test = Test::findOrFail($testId);
    $testCode = $test->test_code;
    $timestamp = Carbon::now()->setTimezone("Africa/Tripoli")->format("Y-m-d-H-i-s");
    $filename = "{$testCode}_{$timestamp}.pdf";

    // Store the file with the specific name
    $filePath = $request->file('file')->storeAs('results', $filename, 'public');

    // Create a new result record
    $result = new UserResult([
        'file_path' => $filePath,
        'test_id' => $testId,
        'user_id' => auth('api')->id(),
    ]);

    // Save the result record
    auth('api')->user()->results()->save($result);

    return response()->json($result, 201);
}

    public function show($id)
    {
        // Fetch a specific result for the authenticated user
        $result = auth('api')->user()->results()->findOrFail($id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        // Delete a specific result for the authenticated user
        $result = auth('api')->user()->results()->findOrFail($id);
        Storage::disk('public')->delete($result->file_path);
        $result->delete();
        return response()->json(null, 204);
    }

    public function download($id)
    {
       $result = auth('api')->user()->results()->findOrFail($id);

        $filePath = $result->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return Storage::disk('public')->download($filePath);
    }

     public function update(Request $request, $id)
    {
        // Validate the new file
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Fetch the specific result for the authenticated user
        $result = auth('api')->user()->results()->findOrFail($id);

        // Delete the old file if it exists
        if (Storage::disk('public')->exists($result->file_path)) {
            Storage::disk('public')->delete($result->file_path);
        }

        // Store the new file
        $filePath = $request->file('file')->store('results', 'public');

        // Update the result record with the new file path
        $result->update(['file_path' => $filePath]);

        return response()->json($result);
    }
}
