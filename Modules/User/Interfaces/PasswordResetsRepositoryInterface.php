<?php

namespace Modules\User\Interfaces;

interface PasswordResetsRepositoryInterface
{
    public function create(CanResetPasswordContract $user);

    public function exists(CanResetPasswordContract $user, $token);

    public function recentlyCreatedToken(CanResetPasswordContract $user);

    public function delete(CanResetPasswordContract $user);

    public function deleteExpired();
}
