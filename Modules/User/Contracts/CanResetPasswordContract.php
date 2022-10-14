<?php

namespace Modules\User\Contracts;

interface CanResetPasswordContract
{
    public function getPhoneForPasswordReset();
}