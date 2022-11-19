<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Book\Enums\BookType;

class GetCompletedPurchasesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'type' => ['sometimes', 'string', new Enum(BookType::class)]
        ];
    }
}