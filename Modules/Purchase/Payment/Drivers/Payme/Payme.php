<?php

namespace Modules\Purchase\Payment\Drivers\Payme;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Purchase\Payment\Contracts\PaymentDriverContract;
use Modules\Purchase\Payment\Drivers\Payme\Methods\CancelTransaction;
use Modules\Purchase\Payment\Drivers\Payme\Methods\CheckPerformTransaction;
use Modules\Purchase\Payment\Drivers\Payme\Methods\CheckTransaction;
use Modules\Purchase\Payment\Drivers\Payme\Methods\CreateTransaction;
use Modules\Purchase\Payment\Drivers\Payme\Methods\GetStatement;
use Modules\Purchase\Payment\Drivers\Payme\Methods\PerformTransaction;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\DTO\CheckoutData;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class Payme implements PaymentDriverContract
{
    private Request $request;

    public function __construct(private readonly Merchant $merchant)
    {
    }

    /**
     * @throws PaymentException
     */
    public function run(): void
    {
        $this->request = request();

        //Authorize incoming request
        $this->merchant->authorize($this->request);

        //Get corresponding JSON-RPC method name
        $methodName = Str::camel($this->request->input('method'));

        if (method_exists($this, $methodName)) {
            $this->{$methodName}();
        } else {
            PaymeResponse::error(
                PaymeResponse::ERROR_METHOD_NOT_FOUND,
                'Method not found.',
                $methodName
            );
        }
    }

    /**
     * @throws PaymentException
     */
    private function checkPerformTransaction(): void
    {
        app(CheckPerformTransaction::class)->execute($this->request);
    }

    private function checkTransaction(): void
    {
        app(CheckTransaction::class)->execute($this->request);
    }

    private function createTransaction(): void
    {
        app(CreateTransaction::class)->execute($this->request);
    }

    private function performTransaction(): void
    {
        app(PerformTransaction::class)->execute($this->request);
    }

    private function cancelTransaction(): void
    {
        app(CancelTransaction::class)->execute($this->request);
    }

    private function getStatement(): void
    {
        app(GetStatement::class)->execute($this->request);
    }

    public function generateCheckoutLink(int $purchaseId, float $amount): string
    {
        return app(PaymeCheckoutLinkGenerator::class)->generate(CheckoutData::from([
            'purchaseId' => $purchaseId,
            'amount' => $amount
        ]));
    }
}
