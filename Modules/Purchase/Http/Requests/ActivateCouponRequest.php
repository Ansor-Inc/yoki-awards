<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivateCouponRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => ['required', 'string', 'size:8', Rule::exists('coupons', 'code')]
        ];
    }
}
