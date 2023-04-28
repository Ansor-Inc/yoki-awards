<?php

namespace Modules\Reaction\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Reaction\Http\Requests\ToggleLikeRequest;

class LikeController extends Controller
{
    public function toggle(ToggleLikeRequest $request)
    {
        $likeableType = $request->input('likeable_type');

        $likeableClassName = config('morph-relation-mapping')[$likeableType];

        $likeable = app($likeableClassName)->findOrFail($request->input('likeable_id'));

        $result = auth()->user()->toggleLike($likeable);

        return response([
            'has_liked' => !($result === true),
            'has_disliked' => ($result === true)
        ]);
    }
}
