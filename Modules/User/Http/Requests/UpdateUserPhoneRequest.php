<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Http\Requests\Traits\ValidatesPhoneNumber;

class UpdateUserPhoneRequest extends FormRequest
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
            'phone' => ['required', 'digits:12', Rule::unique('users')->except(auth()->id())],
            'code' => ['required', 'digits:4']
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
