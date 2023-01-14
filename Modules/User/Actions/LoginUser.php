<?php

namespace Modules\User\Actions;

use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;

class LoginUser
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(array $data)
    {
        $user = $this->userRepository->getUserByPhone($data['phone']);

        if ($user && Hash::check($data['password'], $user->password)) {

            if ($token = $user->currentDeviceToken()) {
                $token->delete();
            }

            return $user->createToken('auth_token')->plainTextToken;
        };

        return false;
    }
}
