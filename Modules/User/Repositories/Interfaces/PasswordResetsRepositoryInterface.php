<?php

namespace Modules\User\Repositories\Interfaces;

use Modules\User\Contracts\CanResetPasswordContract;

interface PasswordResetsRepositoryInterface
{
    public function create(CanResetPasswordContract $user);

    public function exists(CanResetPasswordContract $user, $token);

    public function recentlyCreatedToken(CanResetPasswordContract $user);

    public function delete(CanResetPasswordContract $user);

    public function deleteExpired();
}