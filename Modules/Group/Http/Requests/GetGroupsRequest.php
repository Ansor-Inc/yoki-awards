<?php

namespace Modules\Group\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetGroupsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => ['sometimes', 'numeric'],
            'category_id' => ['sometimes', 'numeric']
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
