<?php

declare(strict_types=1);

namespace App\Components\Sms\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramBotClient
{
    const BASE_URL = 'https://api.telegram.org';

    public function __construct(
        protected string $token,
        protected int    $chatId,
        protected string $parseMode
    )
    {
    }

    public function sendMessage(string $content): Response
    {
        return Http::post("{$this->getBaseUrl()}/sendMessage", [
            'chat_id' => $this->chatId,
            'text' => $content,
            'parse_mode' => $this->parseMode
        ]);
    }

    protected function getBaseUrl(): string
    {
        return self::BASE_URL . "/bot{$this->token}";
    }
}
