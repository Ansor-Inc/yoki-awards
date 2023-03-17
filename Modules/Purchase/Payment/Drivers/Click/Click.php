<?php

namespace Modules\Purchase\Payment\Drivers\Click;

use Modules\Purchase\Payment\Contracts\PaymentDriverContract;
use Modules\Purchase\Payment\Drivers\Click\Methods\Complete;
use Modules\Purchase\Payment\Drivers\Click\Methods\Prepare;
use Modules\Purchase\Payment\Drivers\Click\Requests\ClickCompleteRequest;
use Modules\Purchase\Payment\Drivers\Click\Requests\ClickPrepareRequest;
use Modules\Purchase\Payment\Drivers\Click\Response\Response as ClickResponse;

class Click implements PaymentDriverContract
{
    const ACTION_PREPARE = 0;
    const ACTION_COMPLETE = 1;

    public function run()
    {
        $action = request()->has('action') ? (int)request()->input('action') : null;

        match ($action) {
            self::ACTION_PREPARE => $this->prepare(),
            self::ACTION_COMPLETE => $this->complete(),
            default => ClickResponse::error(ClickResponse::ERROR_ACTION_NOT_FOUND)
        };
    }

    public function generateCheckoutLink(int $purchaseId, float $amount): string
    {
        return app(ClickCheckoutLinkGenerator::class)->generate($purchaseId, $amount);
    }

    private function prepare()
    {
        app(Prepare::class)->execute(app(ClickPrepareRequest::class));
    }

    private function complete()
    {
        app(Complete::class)->execute(app(ClickCompleteRequest::class));
    }
}
