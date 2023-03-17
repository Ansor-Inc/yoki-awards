<?php

namespace Modules\User\Interfaces;

interface CanResetPasswordContract
{
    public function getPhoneForPasswordReset();
}
