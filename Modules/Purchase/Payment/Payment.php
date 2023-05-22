<?php

namespace Modules\Purchase\Payment;

use Modules\Purchase\Payment\Contracts\PaymentDriverContract;
use Modules\Purchase\Payment\Drivers\Click\Click;
use Modules\Purchase\Payment\Drivers\Payme\Payme;
use Modules\Purchase\Payment\Drivers\Payze\Payze;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class Payment
{
    private PaymentDriverContract $driver;

    public function driver(PaymentSystem $paymentSystem): static
    {
        $this->driver = match ($paymentSystem) {
            PaymentSystem::PAYME => app(Payme::class),
            PaymentSystem::CLICK => app(Click::class),
            PaymentSystem::PAYZE => app(Payze::class)
        };

        return $this;
    }

    public function handle()
    {
        try {
            return $this->driver->run();
        } catch (PaymentException $exception) {
            return $exception->response();
        }
    }

    public function generateCheckoutLink(int $purchaseId, float $amount): string
    {
        return $this->driver->generateCheckoutLink($purchaseId, $amount);
    }
}
