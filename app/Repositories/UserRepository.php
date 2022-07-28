<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

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
}
