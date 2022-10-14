<?php

namespace App\Components\Sms;

use App\Components\Sms\Clients\PlaymobileClient;
use App\Components\Sms\Clients\TelegramBotClient;
use App\Components\Sms\Drivers\PlaymobileDriver;
use App\Components\Sms\Drivers\TelegramBotDriver;
use Illuminate\Support\Manager;

class SmsManager extends Manager
{
    /**
     * Get a driver instance.
     *
     * @param string|null $name
     * @return mixed
     */
    public function channel($name = null)
    {
        return $this->driver($name);
    }

    public function createPlaymobileDriver()
    {
        return new PlaymobileDriver($this->createPlaymobileClient());
    }

    public function createTelegramBotDriver()
    {
        return new TelegramBotDriver($this->createTelegramBotClient());
    }


    /**
     * Get the default SMS driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config['sms.default'] ?? 'null';
    }

    protected function createPlaymobileClient()
    {
        return new PlaymobileClient(
            $this->config['sms.drivers.playmobile.host'],
            $this->config['sms.drivers.playmobile.originator'],
            $this->config['sms.drivers.playmobile.username'],
            $this->config['sms.drivers.playmobile.password']
        );
    }

    protected function createTelegramBotClient()
    {
        return new TelegramBotClient(
            $this->config['sms.drivers.telegram_bot.token'],
            $this->config['sms.drivers.telegram_bot.chat_id'],
            $this->config['sms.drivers.telegram_bot.parse_mode']
        );
    }


}