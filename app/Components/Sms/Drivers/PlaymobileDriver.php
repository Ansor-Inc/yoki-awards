<?php

namespace App\Components\Sms\Drivers;

use App\Components\Sms\Clients\PlaymobileClient;
use App\Components\Sms\Contracts\Sms;
use Exception;

class PlaymobileDriver implements Sms
{
    protected PlaymobileClient $client;
    protected string $to;
    protected string $content;

    public function __construct(PlaymobileClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    public function send()
    {
        $response = $this->client->sendSms($this->to, $this->content);

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        return $response;
    }

    public function to(string $phoneNumber)
    {
        $this->to = $phoneNumber;

        return $this;
    }

    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }
}