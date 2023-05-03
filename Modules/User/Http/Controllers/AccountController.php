<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Actions\UpdateUser;
use Modules\User\Actions\UpdateUserAvatar;
use Modules\User\Actions\UpdateUserPhone;
use Modules\User\Http\Requests\DestroyAccountRequest;
use Modules\User\Http\Requests\SetFcmTokenRequest;
use Modules\User\Http\Requests\UpdateUserAvatarRequest;
use Modules\User\Http\Requests\UpdateUserPhoneRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Transformers\BalanceResource;
use Modules\User\Transformers\UserResource;

class AccountController extends Controller
{
    public function getMe(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }

    public function updateMe(UpdateUserRequest $request, UpdateUser $updateUser)
    {
        if ($user = $updateUser->execute($request->validated())) {
            return response([
                'message' => "Foydalanuvchi ma'lumotlari muvaffaqiyatli o'zgartirildi",
                'user' => UserResource::make($user)
            ]);
        }

        return response(['message' => "Eski parol noto'g'ri!"], 442);
    }

    public function updatePhone(UpdateUserPhoneRequest $request, UpdateUserPhone $updateUserPhone)
    {
        if ($updateUserPhone->execute($request)) {
            return response(['message' => "Telefon raqami muvaffaqiyatli o'zgartirildi!"]);
        }

        return response(['message' => 'Yaroqsiz yoki muddati o‘tgan kod!'], 500);
    }

    public function updateAvatar(UpdateUserAvatarRequest $request, UpdateUserAvatar $updateUserAvatar)
    {
        if ($absolutePath = $updateUserAvatar->execute($request)) {
            return response([
                'message' => "Avatar muvaffaqiyatli oʻzgartirildi!",
                'avatar' => $absolutePath
            ]);
        }

        return $this->failed();
    }

    public function getBalance()
    {
        $user = request()->user();

        return response(BalanceResource::make($user), 200);
    }


    public function setFcmToken(SetFcmTokenRequest $request)
    {
        $request->user()->currentAccessToken()->update(['fcm_token' => $request->input('token')]);

        return response(['message' => 'Fcm token set successfully!']);
    }

    public function destroy(DestroyAccountRequest $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        $user->delete();

        return $this->success();
    }
}
