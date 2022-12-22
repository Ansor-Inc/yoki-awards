<?php

namespace Modules\Purchase\Actions\Payme;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;

class HandleRequestAction
{
    private mixed $config;
    private Request $request;

    public function __construct(private PaymeResponse $response)
    {
        $this->config = config('billing.payme');
    }

    public function execute(Request $request)
    {
        $this->request = $request;

        $this->authorize($request);

        $methodName = Str::camel($request->input('method'));

        if (method_exists($this, $methodName)) {
            $this->{$methodName}();
        } else {
            $this->response->error(
                PaymeResponse::ERROR_METHOD_NOT_FOUND,
                'Method not found.',
                $methodName
            );
        }
    }

    private function authorize(Request $request): void
    {
        $hasAuthHeader = $request->hasHeader('Authorization');

        $hasBasicAuthHeader = preg_match('/^\s*Basic\s+(\S+)\s*$/i', $request->header('Authorization'), $matches);

        if ($hasAuthHeader)
            $hasValidCredentials = base64_decode($matches[1]) == $this->config['login'] . ":" . $this->config['password'];
        
        if (!$hasAuthHeader || !$hasBasicAuthHeader || !$hasValidCredentials) {
            $this->response->error(PaymeResponse::ERROR_INSUFFICIENT_PRIVILEGE, 'Insufficient privilege to perform this method.');
        }
    }

    private function checkPerformTransaction()
    {
        app(CheckPerformTransactionAction::class)->execute($this->request);
    }

    private function checkTransaction()
    {
        app(CheckTransactionAction::class)->execute($this->request);
    }

    private function createTransaction()
    {
        app(CreateTransactionAction::class)->execute($this->request);
    }

    private function performTransaction()
    {
        app(PerformTransactionAction::class)->execute($this->request);
    }

    private function cancelTransaction()
    {
        app(CancelTransactionAction::class)->execute($this->request);
    }

    private function getStatement()
    {
        app(GetStatementAction::class)->execute($this->request);
    }
}
