<?php

namespace Modules\Purchase\Payment\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Purchase\Payment\Drivers\Click\Click;
use Modules\Purchase\Payment\Drivers\Click\DTO\ClickConfig;
use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\Drivers\Payme\Merchant as PaymeMerchant;
use Modules\Purchase\Payment\Drivers\Payme\Payme;
use Modules\Purchase\Payment\Payment;

class PaymentServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->bindPaymeDriver();
        $this->bindClickDriver();

        $this->app->bind('payment', fn() => new Payment());
    }

    private function bindPaymeDriver()
    {
        $this->bindPaymeConfig();

        $this->app->bind(Payme::class, fn() => new Payme(
            new PaymeMerchant(app(PaymeConfig::class))
        ));
    }

    private function bindClickDriver()
    {
        $this->bindClickConfig();

        $this->app->bind(Click::class, fn() => new Click());
    }


    private function bindClickConfig()
    {
        $this->app->bind(ClickConfig::class, fn() => ClickConfig::from([
            'merchantId' => config('billing.click.merchant_id'),
            'serviceId' => config('billing.click.service_id'),
            'secretKey' => config('billing.click.secret_key'),
            'merchantUserId' => config('billing.click.merchant_user_id')
        ]));
    }

    private function bindPaymeConfig()
    {
        $this->app->bind(PaymeConfig::class, fn() => PaymeConfig::from([
            'merchantId' => config('billing.payme.merchant_id'),
            'key' => config('billing.payme.key'),
            'login' => config('billing.payme.login'),
            'password' => config('billing.payme.password')
        ]));
    }
}
