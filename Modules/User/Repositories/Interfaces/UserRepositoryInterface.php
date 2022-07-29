<?php

namespace Modules\User\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getUserByPhone(string $phone);

    public function createUser(array $payload);

    public function updateUser(int $userId, array $payload);

    public function deleteUser(int $userId);
}
