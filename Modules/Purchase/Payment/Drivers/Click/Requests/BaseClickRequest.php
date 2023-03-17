<?php

namespace Modules\Purchase\Payment\Drivers\Click\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Purchase\Payment\Drivers\Click\Response\Response as ClickResponse;

class BaseClickRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        ClickResponse::error(ClickResponse::ERROR_REQUEST_FROM);
    }
}
