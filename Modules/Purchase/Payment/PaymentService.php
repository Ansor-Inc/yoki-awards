<?php

namespace Modules\Purchase\Payment;

use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Payment\Click\Click;
use Modules\Purchase\Payment\Payme\Payme;

class PaymentService
{
    protected $paymentClass;

    public function driver(PaymentSystem $paymentSystem)
    {
        switch ($paymentSystem) {
            case PaymentSystem::CLICK:
                $this->paymentClass = new Click;
                break;
            case PaymentSystem::PAYME:
                $this->paymentClass = new Payme;
                break;
        }

        return $this;
    }

    public function handle()
    {
        try {
            return $this->paymentClass->run();
        } catch (PaymentException $e) {
            return $e->response();
        }
    }
}