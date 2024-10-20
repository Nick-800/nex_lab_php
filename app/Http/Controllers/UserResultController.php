<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserResult;
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
        // Validate the file
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Store the file
        $filePath = $request->file('file')->store('results', 'public');

        // Create a new result record
        $result = new UserResult([
            'file_path' => $filePath,
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
