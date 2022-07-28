<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $payload);

    public function updateUser(int $userId, array $payload);

    public function deleteUser(int $userId);


}
