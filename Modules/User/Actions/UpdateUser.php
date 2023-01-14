<?php

namespace Modules\User\Actions;

use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    public function execute(array $data)
    {
        $user = request()->user();

        if (isset($data['old_password']) && isset($data['new_password'])) {
            if (Hash::check($data['old_password'], $user->password)) {
                $user->update(['password' => Hash::make($data['new_password'])]);
            } else {
                return false;
            }
        }

        return tap($user)->update($data);
    }
}
