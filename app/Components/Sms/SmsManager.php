<?php

namespace App\Components\Sms;

use App\Components\Sms\Clients\PlaymobileClient;
use App\Components\Sms\Clients\TelegramBotClient;
use App\Components\Sms\Drivers\PlaymobileDriver;
use App\Components\Sms\Drivers\TelegramBotDriver;
use Illuminate\Support\Manager;

class SmsManager extends Manager
{
    public function channel(string $name = null): mixed
    {
        return $this->driver($name);
    }

    public function createPlaymobileDriver(): PlaymobileDriver
    {
        return new PlaymobileDriver($this->createPlaymobileClient());
    }

    public function createTelegramBotDriver(): TelegramBotDriver
    {
        return new TelegramBotDriver($this->createTelegramBotClient());
    }

    public function getDefaultDriver(): mixed
    {
        return $this->config['sms.default'] ?? 'null';
    }

    protected function createPlaymobileClient(): PlaymobileClient
    {
        return new PlaymobileClient(
            $this->config['sms.drivers.playmobile.host'],
            $this->config['sms.drivers.playmobile.originator'],
            $this->config['sms.drivers.playmobile.username'],
            $this->config['sms.drivers.playmobile.password']
        );
    }

    protected function createTelegramBotClient(): TelegramBotClient
    {
        return new TelegramBotClient(
            $this->config['sms.drivers.telegram_bot.token'],
            $this->config['sms.drivers.telegram_bot.chat_id'],
            $this->config['sms.drivers.telegram_bot.parse_mode']
        );
    }
}
