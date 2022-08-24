<?php

namespace Modules\Group\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enums\UserDegree;

class CreateGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_category_id' => ['required', 'integer', 'exists:group_categories,id'],
            'member_limit' => ['required', 'integer', 'min:0'],
            'title' => ['required', 'string', 'unique:groups,title'],
            'degree' => ['required', 'string', new Enum(UserDegree::class)],
            'is_private' => ['required', 'boolean']
        ];
    }

    public function getSanitized(): array
    {
        $sanitized = $this->validated();
        $sanitized['owner_id'] = $this->user()->id;

        return $sanitized;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->groups()->count() >= 100) {
            return false;
        }
        return true;
    }
}
