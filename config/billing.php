<?php

return [

    'payme' => [
        'key' => 'purchase_id',
        'merchant_id' => env('PAYME_MERCHANT_ID', ''),
        'login' => 'Paycom',
        'password' => env('PAYME_SECRET', '')
    ],

    'click' => [
        'merchant_id' => (int)env('CLICK_MERCHANT_ID'),
        'service_id' => (int)env('CLICK_SERVICE_ID'),
        'secret_key' => (string)env('CLICK_SECRET_KEY'),
        'merchant_user_id' => (int)env('CLICK_MERCHANT_USER_ID')
    ]
];
