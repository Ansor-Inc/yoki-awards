<?php

namespace Modules\User\Entities;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'user_agent',
        'ip',
        'fcm_token'
    ];

    protected static function booted()
    {
        static::creating(function ($token) {
            $token->ip = request()->ip();
            $token->user_agent = request()->userAgent();
        });
    }
}
