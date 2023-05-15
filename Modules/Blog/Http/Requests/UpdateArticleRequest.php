<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property mixed $articleWithoutScopes
 */
class UpdateArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'body' => ['sometimes', 'string'],
            'group_link' => ['sometimes', 'string'],
            'tags' => ['sometimes', 'array']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('own', $this->articleWithoutScopes);
    }
}
