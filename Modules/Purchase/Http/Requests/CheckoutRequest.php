<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Purchase\Payment\Enums\PaymentSystem;

class CheckoutRequest extends FormRequest
{
    public function rules()
    {
        return [
            'payment_system' => ['required', new Enum(PaymentSystem::class)],
            'from_balance' => ['sometimes', 'integer', 'min:0']
        ];
    }
}
