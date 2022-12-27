<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitAppealRequest;
use App\Models\Appeal;

class AppealController extends Controller
{
    public function submit(SubmitAppealRequest $request)
    {
        Appeal::query()->create($request->sanitized());

        return response(['message' => 'Appeal sent successfully!']);
    }
}
