<?php

namespace App\Contracts;

interface CanResetPasswordContract
{
    public function getPhoneForPasswordReset();
}