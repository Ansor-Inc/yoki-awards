<?php

namespace Modules\User\Actions;

use Modules\User\Interfaces\UserRepositoryInterface;

class RegisterUser
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(array $payload)
    {
        $registeredUser = $this->userRepository->registerUser($payload);

        if (isset($registeredUser)) {
            return $registeredUser->createToken('auth_token')->plainTextToken;
        }

        return false;
    }
}
