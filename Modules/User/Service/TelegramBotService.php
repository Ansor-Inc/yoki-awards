<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    const TOKEN = '5433785909:AAEDTO-yQI_SLhmRJFYvsvcAF4LLJFMeytw';
    const BASE_URL = 'https://api.telegram.org/bot' . self::TOKEN;
    const CHAT_ID = -726797860;

    public function __call(string $name, array $arguments)
    {
        return Http::post(self::BASE_URL . "/{$name}", $arguments[0]);
    }

    public function sendMessage(string $text = 'test')
    {
        return Http::post(self::BASE_URL . "/sendMessage", [
            'chat_id' => self::CHAT_ID,
            'text' => $text,
            'parse_mode' => 'html'
        ]);
    }

}