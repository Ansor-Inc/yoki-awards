<?php

namespace Modules\Purchase\Payment\Drivers\Click\Requests;

class ClickPrepareRequest extends BaseClickRequest
{
    public function rules(): array
    {
        return [
            'click_trans_id' => ['required', 'integer'],
            'service_id' => ['required', 'integer'],
            'click_paydoc_id' => ['required', 'integer'],
            'merchant_trans_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'action' => ['required', 'integer'],
            'error' => ['required', 'integer'],
            'error_note' => ['required', 'string'],
            'sign_time' => ['required', 'string'],
            'sign_string' => ['required', 'string']
        ];
    }
}
