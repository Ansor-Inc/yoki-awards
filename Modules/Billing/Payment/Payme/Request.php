<?php

namespace Modules\Billing\Payment\Payme;

class Request
{
    public array $payload;
    public int $id;
    public string $method;
    public array $params;
    public int $amount;
    public $response;

    /**
     * Request constructor.
     * Parses request payload and populates properties with values.
     */
    public function __construct($response)
    {
        $this->response = $response;
        $this->payload = request()->all();

        if (!$this->payload) {
            $this->response->error(Response::ERROR_INVALID_JSON_RPC_OBJECT, 'Invalid JSON-RPC object.');
        }

        // populate request object with data
        $this->id = isset($this->payload['id']) ? 1 * $this->payload['id'] : null;
        $this->method = isset($this->payload['method']) ? trim($this->payload['method']) : null;
        $this->params = $this->payload['params'] ?? [];
        $this->amount = isset($this->payload['params']['amount']) ? 1 * $this->payload['params']['amount'] : null;

        // add request id into params too
        $this->params['request_id'] = $this->id;
    }

    public function account($param)
    {
        return isset($this->params['account'], $this->params['account'][$param]) ? $this->params['account'][$param] : null;
    }

}
