<?php

namespace Modules\Purchase\Payment\Drivers\Click\Response;

use Modules\Purchase\Payment\Exceptions\PaymentException;
use Modules\Purchase\Payment\PaymentResponse;

class Response extends PaymentResponse
{
    const SUCCESS = 0;
    const ERROR_SIGN_CHECK = -1;
    const ERROR_INVALID_AMOUNT = -2;
    const ERROR_ACTION_NOT_FOUND = -3;
    const ERROR_ALREADY_PAID = -4;
    const ERROR_ORDER_NOT_FOUND = -5;
    const ERROR_TRANSACTION_NOT_FOUND = -6;
    const ERROR_UPDATE_ORDER = -7;
    const ERROR_REQUEST_FROM = -8;
    const ERROR_TRANSACTION_CANCELLED = -9;
    const ERROR_VENDOR_NOT_FOUND = -10;

    public static function success(int $status, array $params)
    {
        $response = new static();

        $response->params['error'] = $status;
        $response->params['error_note'] = $response->getErrorNote($status);

        $response->params = array_merge($response->params, $params);

        throw new PaymentException($response);
    }

    public static function error(int $status = null)
    {
        $response = new static();

        $response->params['error'] = $status;
        $response->params['error_note'] = $response->getErrorNote($status);

        throw new PaymentException($response);
    }

    private function getErrorNote(int $status): string
    {
        return match ($status) {
            self::SUCCESS => "Successful request",
            self::ERROR_SIGN_CHECK => "Signature verification error",
            self::ERROR_INVALID_AMOUNT => "Invalid payment amount",
            self::ERROR_ACTION_NOT_FOUND => "The requested action is not found",
            self::ERROR_ALREADY_PAID => "The transaction was previously confirmed (when trying to confirm or cancel the previously confirmed transaction)",
            self::ERROR_ORDER_NOT_FOUND => "Do not find a user / order (check parameter merchant_trans_id)",
            self::ERROR_TRANSACTION_NOT_FOUND => "The transaction is not found (check parameter merchant_prepare_id)",
            self::ERROR_UPDATE_ORDER => "An error occurred while changing user data (changing account balance, etc.)",
            self::ERROR_REQUEST_FROM => "The error in the request from CLICK (not all transmitted parameters, etc.)",
            self::ERROR_TRANSACTION_CANCELLED => "The transaction was previously canceled (When you attempt to confirm or cancel the previously canceled transaction)",
            default => "ERROR_VENDOR_NOT_FOUND"
        };
    }
}
