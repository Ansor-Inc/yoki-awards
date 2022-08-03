<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsToken extends Model
{
    protected $table = 'sms_tokens';

    protected $fillable = [
        'phone',
        'code',
        'is_sent'
    ];
}
