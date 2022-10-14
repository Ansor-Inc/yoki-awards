<?php

namespace Modules\User\Services\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;

class SmsTokenServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'sms-token-service'; // same as bind method in service provider
    }
}