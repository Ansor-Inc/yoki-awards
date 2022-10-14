<?php

namespace App\Components\Sms\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PlaymobileClient
{
    public function __construct(
        protected string $host,
        protected int    $originator,
        protected string $username,
        protected string $password,
    )
    {
    }

    public function sendSms(string $phone, string $message): PromiseInterface|Response
    {
        $data = $this->prepareData($phone, $message);

        return $this->send($data);
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

    private function send($data): PromiseInterface|Response
    {
        return Http::asJson()
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders(['charset' => 'UTF-8'])
            ->post($this->host, $data);
    }
}