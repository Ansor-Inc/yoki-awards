<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;

class PlayMobileService
{
    private string $host;
    private string $username;
    private string $password;
    private int $originator;

    public function __construct()
    {
        $this->host = config('services.playmobile.host');
        $this->originator = config('services.playmobile.originator');
        $this->username = config('services.playmobile.username');
        $this->password = config('services.playmobile.password');
    }

    public static function sendSms(string $phone, string $code)
    {
        $service = new static();

        $data = $service->prepareData($phone, $code);

        return $service->send($data);
    }

    private function prepareData(string $phone, string $message): array
    {
        return [
            'messages' => [
                'recipient' => $phone,
                'message-id' => $this->getMessageId(),
            ],
            'sms' => [
                'originator' => $this->originator,
                'content' => [
                    'text' => $message
                ]
            ]
        ];
    }

    private function getMessageId(): string
    {
        return (string)random_int(10000, 99999);
    }

    private function send($data): string
    {
        return Http::asJson()
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders(['charset' => 'UTF-8'])
            ->post($this->host, $data)
            ->body();
    }
}
