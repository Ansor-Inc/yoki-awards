<?php

namespace Modules\Book\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Book\Enums\BookType;

class GetBooksRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:0'],
            'trending' => ['nullable'],
            'latest' => ['nullable'],
            'limit' => ['sometimes', 'integer', 'min:1'],
            'type' => ['sometimes', new Enum(BookType::class), 'string'],
            'free' => ['sometimes', 'boolean'],
            'special' => ['nullable']
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
