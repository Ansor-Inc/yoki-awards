<?php

namespace App\Components\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static to(string $phoneNumber)
 * @method static content(string $content)
 * @method static send()
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}