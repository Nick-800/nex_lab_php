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
        $results = auth('user')->user()->results;
        return response()->json(["data" => $results], 200);
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'file' => 'required|file|mimes:pdf|max:2048',
        'test_id' => 'required|exists:tests,id',
    ]);

    $testId = $request->input('test_id');
    $test = Test::findOrFail($testId);
    $testCode = $test->test_code;
    $timestamp = Carbon::now()->setTimezone("Africa/Tripoli")->format("Y-m-d-H-i-s");
    $filename = "{$testCode}_{$timestamp}.pdf";

    $filePath = $request->file('file')->storeAs('results', $filename, 'public');

    $result = new UserResult([
        'file_path' => $filePath,
        'test_id' => $testId,
        'user_id' => auth('user')->id(),
    ]);

    auth('user')->user()->results()->save($result);

    return response()->json($result, 201);
}

    public function show($id)
    {
        $result = auth('user')->user()->results()->findOrFail($id);
        return response()->json($result);
    }

    public function destroy($id)
    {
        $result = auth('user')->user()->results()->findOrFail($id);
        Storage::disk('public')->delete($result->file_path);
        $result->delete();
        return response()->json(null, 204);
    }

    public function download($id)
    {
       $result = auth('user')->user()->results()->findOrFail($id);
       $filePath = $result->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return Storage::disk('public')->download($filePath);
    }

     public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $result = auth('user')->user()->results()->findOrFail($id);

        if (Storage::disk('public')->exists($result->file_path)) {
            Storage::disk('public')->delete($result->file_path);
        }

        $filePath = $request->file('file')->store('results', 'public');

        $result->update(['file_path' => $filePath]);

        return response()->json(["data " => $result], 200);
    }
}
