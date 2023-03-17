<?php

declare(strict_types=1);

namespace App\Components\Sms\Drivers;

use App\Components\Sms\Clients\PlaymobileClient;
use App\Components\Sms\Contracts\Sms;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

class PlaymobileDriver implements Sms
{
    protected string $to;
    protected string $content;

    public function __construct(protected PlaymobileClient $client)
    {
    }

    /**
     * @throws Exception
     */
    public function send(): PromiseInterface|Response
    {
        $response = $this->client->sendSms($this->to, $this->content);

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        return $response;
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
}
