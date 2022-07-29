<?php

namespace Modules\User\Repositories;

use App\Models\User;
use Modules\User\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(array $payload)
    {
        return User::query()->create($payload);
    }

    public function updateUser(int $userId, array $payload)
    {
        return User::query()->where('id', $userId)->update($payload);
    }

    public function deleteUser(int $userId)
    {
        return User::query()->where('id', $userId)->delete();
    }

    public function getUserByPhone(string $phone)
    {
        return User::query()->where('phone', $phone)->first();
    }
}
