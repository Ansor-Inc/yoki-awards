<?php

namespace Modules\Billing\Payment\Payme;

use App\Models\Purchase;
use App\Models\Transaction;
use Modules\Billing\Payment\DataFormat;
use Modules\Billing\Enums\PaymentSystem;
use Modules\Billing\Payment\Payme\MerchantApiMethods\CancelTransaction;
use Modules\Billing\Payment\Payme\MerchantApiMethods\CheckPerformTransaction;
use Modules\Billing\Payment\Payme\MerchantApiMethods\CheckTransaction;
use Modules\Billing\Payment\Payme\MerchantApiMethods\CreateTransaction;
use Modules\Billing\Payment\Payme\MerchantApiMethods\PerformTransaction;

class Payme
{
    use CheckPerformTransaction,
        CheckTransaction,
        CreateTransaction,
        PerformTransaction,
        CancelTransaction;

    public $config;
    public $request;
    public $response;
    public $merchant;

    public function __construct()
    {
        $this->config = config('billing.payme');
    }

    public function run()
    {
        $this->response = new Response();
        $this->request = new Request($this->response);
        $this->response->setRequest($this->request);
        $this->merchant = new Merchant($this->config, $this->response);
        // authorize session
        $this->merchant->authorize();

        // handle request
        switch ($this->request->method) {
            case 'CheckPerformTransaction':
                $this->checkPerformTransaction();
                break;
            case 'CheckTransaction':
                $this->checkTransaction();
                break;
            case 'CreateTransaction':
                $this->createTransaction();
                break;
            case 'PerformTransaction':
                $this->performTransaction();
                break;
            case 'CancelTransaction':
                $this->cancelTransaction();
                break;
            case 'GetStatement':
                $this->getStatement();
                break;
            default:
                $this->response->error(
                    Response::ERROR_METHOD_NOT_FOUND,
                    'Method not found.',
                    $this->request->method
                );
        }

    }

    protected function getPurchaseByKey()
    {
        return Purchase::query()->find($this->request->params['account'][$this->config['key']]);
    }

    public function validateParams(array $params)
    {
        // for example, check amount is numeric
        if (!is_numeric($params['amount'])) {
            $this->response->error(Response::ERROR_INVALID_AMOUNT, 'Incorrect amount.');
        }

        // assume, we should have order_id
        if (!isset($params['account'][$this->config['key']])) {
            $this->response->error(
                Response::ERROR_INVALID_ACCOUNT,
                Response::message('Неверный код Счет.', 'Billing kodida xatolik.', 'Incorrect object code.'),
                'key'
            );
        }

        return true;
    }

    public function findTransactionByParams($params)
    {
        if (!isset($params['id'])) $this->response->error(31008, 'No transaction id is provided.');

        return Transaction::query()
            ->where('payment_system', PaymentSystem::PAYME->value)
            ->where('system_transaction_id', $params['id'])
            ->first();
    }

    private function getStatement()
    {
        // validate 'from'
        if (!isset($this->request->params['from'])) {
            $this->response->error(Response::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'from');
        }

        // validate 'to'
        if (!isset($this->request->params['to'])) {
            $this->response->error(Response::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'to');
        }

        // validate period
        if (1 * $this->request->params['from'] >= 1 * $this->request->params['to']) {
            $this->response->error(Response::ERROR_INVALID_ACCOUNT, 'Incorrect period. (from >= to)', 'from');
        }

        // get list of transactions for specified period
        $transactions = $this->getReport($this->request->params['from'], $this->request->params['to']);

        // send results back
        $this->response->success(['transactions' => $transactions]);
    }

    public function getReport($from_date, $to_date)
    {
        $from_date = DataFormat::timestamp2datetime($from_date);
        $to_date = DataFormat::timestamp2datetime($to_date);

        $transactions = Transaction::query()->where('payment_system', PaymentSystem::PAYME)
            ->where('state', Transaction::STATE_COMPLETED)
            ->where('created_at', '>=', $from_date)
            ->where('created_at', '<=', $to_date)->get();
        // assume, here we have $rows variable that is populated with transactions from data store
        // normalize data for response
        $result = [];
        foreach ($transactions as $row) {
            $detail = $row['detail'];

            $result[] = [
                'id' => (string)$row['system_transaction_id'], // paycom transaction id
                'time' => 1 * $detail['system_time_datetime'], // paycom transaction timestamp as is
                'amount' => 1 * $row['amount'],
                'account' => [
                    'key' => 1 * $row[$this->config['key']], // account parameters to identify client/order/service
                    // ... additional parameters may be listed here, which are belongs to the account
                ],
                'create_time' => DataFormat::datetime2timestamp($detail['create_time']),
                'perform_time' => DataFormat::datetime2timestamp($detail['perform_time']),
                'cancel_time' => DataFormat::datetime2timestamp($detail['cancel_time']),
                'transaction' => (string)$row['id'],
                'state' => 1 * $row['state'],
                'reason' => isset($row['comment']) ? 1 * $row['comment'] : null,
                'receivers' => isset($row['receivers']) ? json_decode($row['receivers'], true) : null,
            ];
        }
        return $result;
    }

    public function getRedirectParams($model, $amount, $currency, $url)
    {
        $params = [
            'merchant' => $this->config['merchant_id'],
            'amount' => $amount * 100,
            'account[key]' => $model->id,
            'lang' => 'ru',
            'currency' => $currency,
            'callback' => $url,
            'callback_timeout' => 20000,
            'url' => "https://checkout.paycom.uz/",
        ];
        if ($this->hasDescription)
            $params['account[description]'] = 'Оплата за №' . $model->id;
        return $params;
    }
}