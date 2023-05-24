<?php

namespace Modules\Purchase\Payment\Drivers\Payze;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Modules\Purchase\Entities\Purchase;
use PayzeIO\LaravelPayze\Enums\Currency;
use PayzeIO\LaravelPayze\Enums\Language;
use PayzeIO\LaravelPayze\Exceptions\ApiCredentialsException;
use PayzeIO\LaravelPayze\Exceptions\PaymentRequestException;
use PayzeIO\LaravelPayze\Exceptions\UnsupportedCurrencyException;
use PayzeIO\LaravelPayze\Exceptions\UnsupportedLanguageException;
use stdClass;
use Throwable;

class JustPayWithProductInfo
{
    public const METHOD = 'justPay';

    protected float $amount = 0;
    protected string $currency = Currency::DEFAULT;

    protected string $lang = Language::DEFAULT;

    protected Purchase $purchase;
    protected ProductInfo $productInfo;

    public function product(ProductInfo $info): self
    {
        $this->productInfo = $info;

        return $this;
    }

    public function amount(float $amount): self
    {
        $this->amount = max($amount, 0);

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function language(string $lang): self
    {
        throw_unless(Language::check($lang), new UnsupportedLanguageException($lang));

        $this->lang = $lang;

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function currency(string $currency): self
    {
        $currency = strtoupper($currency);

        throw_unless(Currency::check($currency), new UnsupportedCurrencyException($currency));

        $this->currency = $currency;

        return $this;
    }

    public function for(Purchase $purchase): static
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     * @throws PaymentRequestException
     */
    public function process(): array
    {
        return $this->request(self::METHOD, [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'preauthorize' => false,
            'lang' => $this->lang,
//            'hookUrlV2' => route('payment-system.handle', [
//                'paymentSystem' => PaymentSystem::PAYZE->value,
//                'purchaseId' => $this->purchase->id
//            ]),
            'hookUrlV2' => 'https://5b5c-82-215-99-226.ngrok-free.app/billing/payze/handle?purchaseId=' . $this->purchase->id,
            'info' => [
                'image' => $this->productInfo->image,
                'name' => $this->productInfo->name
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     * @throws PaymentRequestException
     */
    public function request(string $method, array $data = []): array
    {
        $key = config('billing.payze.api_key');
        $secret = config('billing.payze.api_secret');

        throw_if(empty($key) || empty($secret), new ApiCredentialsException());

        try {
            $response = json_decode((new Client())->post('https://payze.io/api/v1', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'user-agent' => 'laravel-payze',
                ],
                'json' => [
                    'method' => $method,
                    'apiKey' => $key,
                    'apiSecret' => $secret,
                    'data' => $data ?: new stdClass(),
                ],
                'verify' => config('billing.payze.verify_ssl', true),
            ])->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            throw new PaymentRequestException($exception->getMessage());
        }

        throw_unless(empty($response['response']['error']), new PaymentRequestException($response['response']['error'] ?? 'Error'));

        return $response;
    }
}
