<?php

namespace Modules\Reaction\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Reaction\Http\Requests\ToggleDislikeRequest;

class DisLikeController extends Controller
{
    public function toggle(ToggleDislikeRequest $request)
    {
        $disLikeableType = $request->input('dislikeable_type');

        $disLikeableClassName = config('morph-relation-mapping')[$disLikeableType];

        $disLikeable = app($disLikeableClassName)->findOrFail($request->input('dislikeable_id'));

        $result = auth()->user()->toggleDisLike($disLikeable);

        return response([
            'has_liked' => ($result === true),
            'has_disliked' => !($result === true)
        ]);
    }
}
