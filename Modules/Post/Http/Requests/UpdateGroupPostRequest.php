<?php

namespace Modules\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\UserDegree;

class UpdateGroupPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['sometimes', 'string', 'max:200'],
            'body' => ['sometimes', 'string', 'max:1000'],
            'degree_scope' => ['sometimes', 'array'],
            'degree_scope.*' => new Enum(UserDegree::class),
            'image' => []
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
