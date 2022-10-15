<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ImageUploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate(['image' => ['required', File::image()->max(2 * 1024)]]);

        if ($request->hasFile('image')) {
            $relativePath = Storage::putFile("post-images", $request->file('image'), 'public');

            $absolutePath = Storage::url($relativePath);

            return response()->json(['path' => $absolutePath]);
        }

        return response()->json(['message' => 'No image provided!'], 422);
    }
}
