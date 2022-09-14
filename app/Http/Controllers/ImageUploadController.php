<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class ImageUploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', File::image()->max(2 * 1024)],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', ['disk' => 'public']);
            return response()->json([
                'relative_path' => '/storage/' . $path,
                'absolute_path' => url('storage/' . $path)
            ], 200);
        }

        return response()->json(['message' => 'No image provided!'], 422);
    }
}
