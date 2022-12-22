<?php

namespace Modules\Purchase\Actions;

use Illuminate\Http\Request;
use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Actions\Payme\HandleRequestAction as HandlePaymeRequestAction;
use Modules\Purchase\Exceptions\PaymentException;

class PaymentSystemRequestHandler
{
    public function execute(PaymentSystem $paymentSystem, Request $request)
    {
        try {
            match ($paymentSystem) {
                PaymentSystem::PAYME => app(HandlePaymeRequestAction::class)->execute($request),
                default => false,
            };
        } catch (PaymentException $e) {
            return $e->response();
        }

    }
}
