<?php

return [

    'payme' => [
        'key' => 'order_id',
        'login' => env('PAYME_MERCHANT_ID', ''),
        'password' => env('PAYME_SECRET', '')
    ]
];