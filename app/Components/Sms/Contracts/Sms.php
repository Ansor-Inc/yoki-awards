<?php

namespace App\Components\Sms\Contracts;

interface Sms
{
    public function to(string $phoneNumber);

    public function content(string $content);

    public function send();
}