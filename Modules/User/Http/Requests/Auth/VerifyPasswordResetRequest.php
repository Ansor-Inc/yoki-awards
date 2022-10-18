<?php

namespace Modules\User\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Requests\Traits\ValidatesPhoneNumber;

class VerifyPasswordResetRequest extends FormRequest
{
    use ValidatesPhoneNumber;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => ['required', 'digits:12', 'exists:users,phone'],
            'code' => ['required', 'digits:4']
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'No users with this phone!',
        ];
    }

}
