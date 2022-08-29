<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\UpdateUserPhoneRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Service\Facades\SmsTokenServiceFacade;
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

    public function updatePhone(UpdateUserPhoneRequest $request)
    {
        $phoneIsVerified = SmsTokenServiceFacade::phone($request->input('phone'))->check($request->input('code'));

        if ($phoneIsVerified) {
            $request->user()->update(['phone' => $request->input('phone')]);
            $request->user()->markPhoneAsVerified();
            return response(['message' => 'Phone number updated successfully!']);
        }

        return response(['message' => 'Invalid or expired code!'], 500);
    }
}
