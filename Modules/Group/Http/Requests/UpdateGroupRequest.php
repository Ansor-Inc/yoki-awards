<?php

namespace Modules\Group\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\UserDegree;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_category_id' => ['sometimes', 'integer', 'exists:group_categories,id'],
            'member_limit' => ['sometimes', 'integer', 'min:0'],
            'title' => ['sometimes', 'string'], 
            'degree' => ['sometimes', 'array'],
            'degree.*' => new Enum(UserDegree::class),
            'is_private' => ['sometimes', 'boolean'],
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
