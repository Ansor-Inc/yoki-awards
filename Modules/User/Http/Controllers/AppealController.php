<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Entities\Appeal;
use Modules\User\Http\Requests\ApplyToBeBloggerRequest;
use Modules\User\Http\Requests\SubmitAppealRequest;
use Modules\User\Transformers\AppealResource;

class AppealController extends Controller
{
    public function index(Request $request)
    {
        $appeals = $request->user()->appeals()->latest()->get();

        return AppealResource::collection($appeals);
    }

    public function submit(SubmitAppealRequest $request)
    {
        Appeal::query()->create($request->sanitized());

        return response(['message' => 'Appeal sent successfully!']);
    }

    public function applyToBeBlogger(ApplyToBeBloggerRequest $request)
    {
        if ($request->user()->applications()->exists()) {
            return response(['message' => 'You have already requested'], 403);
        }

        $request->user()->applications()->create($request->validated());

        return $this->success();
    }
}
