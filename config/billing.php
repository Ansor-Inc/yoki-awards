<?php

return [

    'payme' => [
        'key' => 'purchase_id',
        'merchant_id' => env('PAYME_MERCHANT_ID', ''),
        'login' => 'Paycom',
        'password' => env('PAYME_SECRET', ''),
        'ip_whitelist' => explode(',', env('PAYME_IP_WHITELIST', '*'))
    ],

    'click' => [
        'merchant_id' => (int)env('CLICK_MERCHANT_ID'),
        'service_id' => (int)env('CLICK_SERVICE_ID'),
        'secret_key' => (string)env('CLICK_SECRET_KEY'),
        'merchant_user_id' => (int)env('CLICK_MERCHANT_USER_ID'),
        'ip_whitelist' => explode(',', env('CLICK_IP_WHITELIST', '*'))
    ],

    'payze' => [
        'api_key' => env('PAYZE_API_KEY'),
        'api_secret' => env('PAYZE_API_SECRET'),
        'ip_whitelist' => explode(',', env('PAYZE_IP_WHITELIST', '*')),
        'verify_ssl' => true
    ]
];
