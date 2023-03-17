<?php

namespace App\Components\Sms\Drivers;

use App\Components\Sms\Clients\TelegramBotClient;
use App\Components\Sms\Contracts\Sms;
use Exception;
use Illuminate\Http\Client\Response;

class TelegramBotDriver implements Sms
{
    protected TelegramBotClient $client;
    protected string $to;
    protected string $content;

    public function __construct(TelegramBotClient $client)
    {
        $this->client = $client;
    }

    public function to(string $phoneNumber): static
    {
        $this->to = $phoneNumber;

        return $this;
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): Response
    {
        $response = $this->client->sendMessage("Phone: {$this->to} \nContent: {$this->content}");

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        return $response;
    }
}
