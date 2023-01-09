<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Actions\UpdateUserAvatar;
use Modules\User\Http\Requests\UpdateUserAvatarRequest;
use Modules\User\Http\Requests\UpdateUserPhoneRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Services\Facades\SmsTokenServiceFacade;
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

        return $useCase($data);
    }

    public function updatePhone(UpdateUserPhoneRequest $request)
    {
        $phoneIsVerified = SmsTokenServiceFacade::phone($request->input('phone'))->check($request->input('code'));

        if ($phoneIsVerified) {
            $request->user()->update(['phone' => $request->input('phone')]);
            $request->user()->markPhoneAsVerified();
            return response(['message' => "Telefon raqami muvaffaqiyatli o'zgartirildi!"]);
        }

        return response(['message' => 'Yaroqsiz yoki muddati o‘tgan kod!'], 500);
    }

    public function updateAvatar(UpdateUserAvatarRequest $request, UpdateUserAvatar $updateUserAvatarAction)
    {
        $absolutePath = $updateUserAvatarAction->execute($request);

        if ($absolutePath) {
            return response([
                'message' => "Avatar muvaffaqiyatli oʻzgartirildi!",
                'avatar' => $absolutePath
            ]);
        }

        return $this->failed();
    }

    public function setFcmToken(Request $request)
    {
        $request->user()->update($request->only('fcm_token'));

        return response(['message' => 'FCM token has been set successfully!']);
    }
}
