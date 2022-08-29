<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('', ['disk' => 'public']);

            return response()->json(['path' => url('storage/' . $path)], 200);
        }

        return response()->json(['message' => 'No file provided!'], 422);
    }
}
