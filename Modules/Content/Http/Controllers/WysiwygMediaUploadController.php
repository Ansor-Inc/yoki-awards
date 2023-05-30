<?php

namespace Modules\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class WysiwygMediaUploadController extends BaseController
{
    public function upload(Request $request)
    {
        $request->validate(['upload' => ['required']]);

        $temporaryFile = $request->file('upload');

        if (!$temporaryFile->isFile() || !in_array($temporaryFile->getMimeType(), ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'])) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid file provided'
                ]
            ]);
        }

        $relativePath = Storage::putFile("uploads", $temporaryFile, 'public');

        return response()->json(['url' => Storage::url($relativePath)]);
    }
}
