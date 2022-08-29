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
                return ['message' => 'Old password is incorrect!'];
            }
        }

        $user->update($data);

        return [
            'message' => 'User updated successfully!',
            'user' => UserResource::make($user)
        ];
    }
}