<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\UseCases\UpdatesUser;

class UserController extends Controller
{
    public function getMe(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }

    public function updateMe(UpdateUserRequest $request, UpdatesUser $useCase)
    {
        $data = $request->validated();
        return response()->json($useCase($data));
    }
}
