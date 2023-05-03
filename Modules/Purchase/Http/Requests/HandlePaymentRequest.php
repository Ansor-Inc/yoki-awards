<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandlePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $whiteList = $this->paymentSystem->getIpWhiteList();

        if ($whiteList === ['*'])
            return true;

        return in_array($this->getClientIp(), $whiteList);
    }
}
