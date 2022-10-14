<?php

return [

    'default' => env('SMS_DRIVER', 'telegram_bot'),

    'sms_code_lifetime' => (int)env('SMS_CODE_LIFETIME', 120),

    'drivers' => [
        'playmobile' => [
            'host' => env('PLAYMOBILE_HOST', ''),
            'originator' => (int)env('PLAYMOBILE_ORIGINATOR', 3700),
            'username' => env('PLAYMOBILE_USERNAME', ''),
            'password' => env('PLAYMOBILE_PASSWORD', '')
        ],

        'telegram_bot' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'parse_mode' => env('TELEGRAM_PARSE_MODE', 'html')
        ]
    ]
];
