<?php

return [

    'payme' => [
        'key' => 'purchase_id',
        'merchant_id' => env('PAYME_MERCHANT_ID', ''),
        'login' => 'Paycom',
        'password' => env('PAYME_SECRET', '')
    ]
];
