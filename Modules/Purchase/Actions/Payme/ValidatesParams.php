<?php

namespace Modules\Purchase\Actions\Payme;

use Modules\Purchase\Payment\Payme\Response as PaymeResponse;

trait ValidatesParams
{
    protected function validateParams(array $params)
    {
        // for example, check amount is numeric
        if (!is_numeric($params['amount'])) {
            $this->response->error(PaymeResponse::ERROR_INVALID_AMOUNT, 'Incorrect amount.');
        }

        // assume, we should have order_id
        if (!isset($params['account'][$this->config['key']])) {
            $this->response->error(
                PaymeResponse::ERROR_INVALID_ACCOUNT,
                PaymeResponse::message('Неверный код Счет.', 'Billing kodida xatolik.', 'Incorrect object code.'),
                'key'
            );
        }
    }

    protected function validateTransactionId(array $params)
    {
        if (!isset($params['id']))
            $this->response->error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'No transaction id is provided.');
    }
}
