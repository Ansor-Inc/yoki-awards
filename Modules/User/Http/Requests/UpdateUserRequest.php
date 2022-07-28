<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\Gender;

class UpdateUserRequest extends FormRequest
{
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
            'id' => ['required'],
            'fullname' => ['required', 'string'],
            'phone' => ['required', 'string', Rule::unique('users')->ignore($this->id)],
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($this->id)],
            'region' => ['sometimes', 'string'],
            'birthdate' => ['sometimes', 'date'],
            'gender' => ['sometimes', new Enum(Gender::class)],
            'old_password' => ['sometimes', 'string'],
            'new_password' => ['sometimes', 'string', 'confirmed', 'min:8']
        ];
    }
}
