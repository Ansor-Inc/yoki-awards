<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Exceptions\PaymentException;
use Modules\User\Entities\User;

trait ValidatesParams
{
    /**
     * @throws PaymentException
     */
    protected function validateParams(array $params): void
    {
        // for example, check amount is numeric
        if (!is_numeric($params['amount'])) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_AMOUNT, 'Incorrect amount.');
        }

        // assume, we should have order_id
        if (!isset($params['account'][$this->config->key])) {
            PaymeResponse::error(
                PaymeResponse::ERROR_INVALID_ACCOUNT,
                PaymeResponse::message('Неверный код Счет.', 'Billing kodida xatolik.', 'Incorrect object code.'),
                'key'
            );
        }
    }

    /**
     * @throws PaymentException
     */
    protected function validateTransactionId(array $params): void
    {
        if (!isset($params['id'])) {
            PaymeResponse::error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'No transaction id is provided.');
        }
    }
}
