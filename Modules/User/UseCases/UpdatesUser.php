<?php

namespace Modules\User\UseCases;

use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Resources\UserResource;

class UpdatesUser
{
    public function __invoke(array $data)
    {
        $user = request()->user();

        if (isset($data['old_password']) && isset($data['new_password'])) {
            if (Hash::check($data['old_password'], $user->password)) {
                $user->update(['password' => Hash::make($data['new_password'])]);
            } else {
                return response(['message' => "Eski parol noto'g'ri!"], 442);
            }
        }

        $user->update($data);

        return response([
            'message' => "Foydalanuvchi ma'lumotlari muvaffaqiyatli o'zgartirildi",
            'user' => UserResource::make($user)
        ]);
    }
}