<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class SmsToken extends Model
{
    protected $table = 'sms_tokens';

    protected $fillable = [
        'phone',
        'code'
    ];

    public function isExpired()
    {
        return $this->created_at?->diffInSeconds(now()) > config('sms.sms_code_lifetime');
    }

}
