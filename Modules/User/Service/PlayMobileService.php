<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Http;

class PlayMobileService
{
    protected static string $baseUrl = 'http://91.204.239.44/broker-api/send';

    public static function sendSms(string $phone, string $code): int
    {
        $data = [
            'messages' => [
                'recipient' => $phone,
                'message-id' => (string)random_int(10000, 99999),
            ],
            'sms' => [
                'originator' => 3700,
                'content' => [
                    'text' => "$code"
                ]
            ]
        ];
        $sendSms = Http::withHeaders([
            'Content-type' => 'application/json',
            'charset' => 'UTF-8'
        ])->withBasicAuth('wasaf2', 'EDs4Br65a7')
            ->post(self::$baseUrl, $data);

        return $sendSms->status();
    }
}
