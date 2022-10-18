<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Http\Requests\Traits\ValidatesPhoneNumber;

class ResetPasswordSendCodeRequest extends FormRequest
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
            'phone' => ['required', 'digits:12', 'exists:users,phone']
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'No users with this phone!',
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
