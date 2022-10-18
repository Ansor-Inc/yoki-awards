<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Requests\Traits\ValidatesPhoneNumber;

class ResetPasswordRequest extends FormRequest
{
    use ValidatesPhoneNumber;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'digits:12', 'exists:users,phone'],
            'password_reset_token' => ['required', 'string'],
            'password' => ['required', 'min:8', 'confirmed']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
