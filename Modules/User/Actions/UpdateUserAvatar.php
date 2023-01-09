<?php

namespace Modules\User\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UpdateUserAvatar
{
    public function execute(Request $request): bool|string
    {
        $resizedImage = Image::make($request->file('image'))
            ->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $relativePath = "avatars/{$request->user()->id}/avatar.jpg";

        $uploaded = Storage::put($relativePath, $resizedImage->stream('jpg'), 'public');

        if (!$uploaded) {
            return false;
        }

        $absolutePath = Storage::url($relativePath);

        $request->user()->update(['avatar' => $absolutePath]);

        return $absolutePath;
    }
}
