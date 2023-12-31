<?php

namespace Modules\User\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Http\Requests\Traits\ValidatesPhoneNumber;

class RegisterUserRequest extends FormRequest
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
            'fullname' => ['required', 'string'],
            'phone' => ['required', 'digits:12', Rule::unique('users')->whereNotNull('phone_verified_at')],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }
}
